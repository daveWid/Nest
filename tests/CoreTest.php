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
	 * @var string     The path to the "fake" docroot for testing...
	 */
	public $docroot;

	/**
	 * Setup the variables
	 */
	public function setUp()
	{
		$this->docroot = dirname(__FILE__).DIRECTORY_SEPARATOR."public".DIRECTORY_SEPARATOR;
		$config = parse_ini_file($this->docroot."config.ini");

		// Taken from index.php (Need a better way to set this really)
		$config['system_path'] = (substr($config['system_path'], 0, 1) === DIRECTORY_SEPARATOR) ?
			$system :
			realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.$config['system_path']);
		$config['system_path'] = rtrim($config['system_path'], DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

		$this->nest = new \Nest\Core($this->docroot, new \Nest\Config($config));
	}

	/**
	 * Make sure the nest object is created correctly.
	 */
	public function testConstruct()
	{
		$this->assertInstanceOf("\\Nest\\Core", $this->nest);
	}

	/**
	 * Make sure the docroot is correct
	 */
	public function testDocRoot()
	{
		$this->assertSame($this->docroot, $this->nest->docroot());
	}

	/**
	 * Test to make sure the wiki path is pointing at the _wiki folder
	 */
	public function testWikiPath()
	{
		$this->assertSame($this->docroot."_wiki".DIRECTORY_SEPARATOR, $this->nest->wiki_path());
	}

	/**
	 * Test to make sure the url function is setting the correct site url.
	 */
	public function testUrl()
	{
		$this->assertSame("/css/style.css", \Nest\Core::url("css/style.css"));
	}

	/**
	 * Test the file finding function.
	 */
	public function testFindFile()
	{
		// The file was found
		$this->assertNotSame(false, \Nest\Core::find_file("vendor", "markdown".DIRECTORY_SEPARATOR."markdown"));

		// And not found
		$this->assertFalse(\Nest\Core::find_file("vendor", "notexisting"));
	}

	/**
	 * Test the execute
	 */
	public function testExecute()
	{
		$this->assertNotEmpty($this->nest->execute());
	}
}
