<?php

namespace Nest\Tests;

/**
 * PHPUnit tests for the \Nest\Core class.
 *
 * @package  Nest
 * @author   Dave Widmer <dave@davewidmer.net>
 */
class CoreTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var \Nest\Core  The nest instance used in the tests.
	 */
	public $nest;

	/**
	 * Setup the variables
	 */
	public function setUp()
	{
		$docroot = __DIR__.DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR;
		$this->nest = new \Nest\Core($docroot);
	}

	/**
	 * Make sure the docroot is set.
	 */
	public function testDocroot()
	{
		$this->assertNotNull($this->nest->get_docroot());
	}

	/**
	 * Test the execute
	 */
	public function testExecute()
	{
		$this->assertNotEmpty($this->nest->execute());
	}

	/**
	 * Make sure an error is thrown when a file isn't found.
	 *
	 * @expectedException  \Nest\Exception
	 */
	public function testFileNotFoundException()
	{
		$this->nest->execute("nonexistantpage");
	}
}
