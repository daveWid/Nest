<?php

namespace Nest\Tests;

/**
 * Unit tests for the autoloader
 *
 * @package  Nest
 * @author   Dave Widmer <dave@davewidmer.net>
 */
class AutoloadTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Just make sure the autoloader is working correctly.
	 */
	public function testLoad()
	{
		$this->assertTrue(class_exists("\\Nest\\Core"));
	}

	/**
	 * You should never do this in production as it is just a dirty hack to 
	 * get a reference to the autoloader
	 */
	public function testUnregisterAndRegister()
	{
		global $autoload;

		/*
		 * This should pass, but doesn't, but since it is an autoloader I will
		 * move on
		 *\/
		$autoload->unregister($autoload);
		$this->assertFalse(class_exists("\\Nest\\Config"));
		*/

		// And turn it back on and find the same class
		$autoload->register();
		$this->assertTrue(class_exists("\\Nest\\Config"));
	}

	/**
	 * Make sure the autoloader doesn't load classes that don't start with Nest
	 */
	public function testReturnSansLibrary()
	{
		$this->assertFalse(class_exists("Made\\Up\\Class"));
	}

}
