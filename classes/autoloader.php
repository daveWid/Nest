<?php

namespace Nest;

/**
 * Class autoloader for the Nest library
 *
 * @package  Nest
 * @author   Dave Widmer <dave@davewidmer.net>
 */
class Autoloader
{
	/**
	 * @var string  The path to load classes
	 */
	private $path;

	/**
	 * Attempts to autoload a class if
	 *
	 * @param class $class  The name of the class to autoload.
	 */
	public function autoload($class)
	{
		if (preg_match('/^Nest/i', $class) !== 1)
		{
			return; // Only autoload classes in this library
		}

		$class = str_replace("\\", DIRECTORY_SEPARATOR, substr($class, 4));

		$path = $this->path.$class.".php";
		if (is_file($path))
		{
			include $path;
		}
	}

	/**
	 * Registers the autoloader
	 */
	public function register()
	{
		spl_autoload_register(array($this, "autoload"));
		$this->path = dirname(__FILE__).DIRECTORY_SEPARATOR;
	}

	/**
	 * Unregisters the autoloader
	 */
	public function unregister()
	{
		spl_autoload_unregister(array($this, "autoload"));
	}

	/**
	 * Clean-up
	 */
	public function __destruct()
	{
		$this->unregister();
	}

}
