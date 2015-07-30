<?php

namespace GDM\TypeCastor;

use GDM\Framework\Types\String;

class Castor {

	public static function Cast($format, $value) {
		$result = null;
		if (Types::IsValidType($format)) {
			$cleanType = Types::GetType($format);
			if ($cleanType == Types::STRING) {
				$result = (string) $value;
			} else if ($cleanType == Types::INT) {
				$result = (int) $value;
			} else if ($cleanType == Types::FLOAT) {
				$result = (float) $value;
			} else if ($cleanType == Types::BOOL) {
				$result = String::create($value)->toBool();
			} else if ($cleanType == Types::Createable) {
				if (preg_match('/\[\]$/', $format) && (is_array($value) || $value instanceof \Traversable)) {
					$result	 = array();
					$format	 = preg_replace('/\[\]$/', '', $format);
//					echo '<pre class="debug"> "$value"' . PHP_EOL . print_r($value, true) . PHP_EOL . '</pre>';
					foreach ($value as $item) {
//						echo '<pre class="debug"> "$item"' . PHP_EOL . print_r($item, true) . PHP_EOL . '</pre>';
//						echo '<pre class="debug"> "Create ' . $format . ' from " ' . PHP_EOL . print_r($item, true) . PHP_EOL . '</pre>';
						$result[] = $format::CreateFrom($item);
					}
				} else {
					$result = $format::CreateFrom($value);
				}
			}
		}

		return $result;
	}

}
