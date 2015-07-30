<?php

namespace GDM\ClassHelper;

use ReflectionClass;
use Reflector;

class ClassInfo {

	public $class;

	/**
	 *
	 * @var ReflectionClass
	 */
	protected $reflectionClass;

	public function __construct($class) {
		$this->class			 = $class;
		$this->reflectionClass	 = new ReflectionClass($class);
	}

	/**
	 * 
	 * @return Reflector
	 */
	public static function create($class) {
		return new ClassInfo($class);
	}

	public function getClassHierarchy() {
		$parent		 = get_parent_class($this->class);
		$hierarchy[] = $parent;
		while ($parent) {
			$parent = get_parent_class($parent);
			if (!empty($parent)) {
				$hierarchy[] = $parent;
			}
		}
		return $hierarchy;
	}

	public function getMergedArrayStatic($propName) {
		$values		 = array();
		$class		 = $this->class;
		$hierarchy	 = array_merge(array($this->class), $this->getClassHierarchy());

		foreach (array_reverse($hierarchy) as $class) {
			$vars = get_class_vars($class);
			if (isset($vars[$propName]) && is_array($vars[$propName])) {
				$values = array_merge($values, $vars[$propName]);
			}
		}
		return $values;
	}

	public function getConstants() {
		return $this->reflectionClass->getConstants();
	}

}
