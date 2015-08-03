<?php

namespace GDM\TypeCastor;

use GDM\ClassHelper\ClassInfo;
use GDM\Interfaces\Createable;

class Types {

	const STRING		 = "string";
	const INT			 = "int";
	const BOOL		 = "boolean";
	const FLOAT		 = "float";
	const Createable	 = "creatable";

	static protected $aliases = array("bool" => "boolean");

	public static function GetType($type) {
		$result		 = null;
		$cleanType	 = preg_replace('/\[\]$/', "", trim($type));
		if (isset(static::$aliases[$cleanType])) {
			$cleanType = static::$aliases[$cleanType];
		}

		foreach (ClassInfo::create(__CLASS__)->getConstants() as $value) {
			if (strtolower($cleanType) === strtolower($value)) {
				$result = $value;
				break;
			}
		}

//		if (is_null($result)) {
//			echo '<pre class="debug"> "$cleanType" ' . print_r($cleanType, true) . '</pre>';
//			echo '<pre class="debug"> "class_exists(' . $cleanType . ') ' . print_r(class_exists($cleanType) ? "Yes" : "No", true) . '</pre>';
//			echo '<pre class="debug"> "' . $cleanType . ' instanceof \GDM\Interfaces\Createable" ' . print_r($cleanType instanceof \GDM\Interfaces\Createable ? "Yes" : "No", true) . '</pre>';
//			echo '<pre class="debug"> " is_subclass_of(' . $cleanType . ', \GDM\Interfaces\Createable)" ' . print_r(is_subclass_of($cleanType, '\GDM\Interfaces\Createable') ? "Yes" : "No", true) . PHP_EOL . '</pre>';
//		}
		if (is_null($result) && class_exists($cleanType) && ($cleanType instanceof \GDM\Interfaces\Createable || is_subclass_of($cleanType, '\GDM\Interfaces\Createable'))) {
			$result = self::Createable;
		}
		if (is_null($result)) {
			throw new \Exception('"' . $type . '" is not a valid ' . __CLASS__);
		}

		return $result;
	}

	public static function IsValidType($type) {
		return !is_null(static::GetType($type));
	}

}
