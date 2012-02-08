<?php

namespace Nest\Renderer;

include \Nest\Core::find_file("vendor", "textile".DIRECTORY_SEPARATOR."classTextile");

/**
 * Textile Renderer.
 *
 *
 * @package  Nest
 * @author   Dave Widmer <dave@davewidmer.net>
 */
class Textile extends \Textile implements \Nest\Renderer
{
	/**
	 * Creates a new Textile renderer.
	 */
	public function __construct()
	{
		parent::Textile();
	}

	/**
	 * Adds the base url to the pages if the url doesn't start with / or http(s)
	 *
	 * @param  string $text  The url to "shelve"
	 * @return string        The reference to inject into the text
	 */
	public function shelveURL($text)
	{
		if (preg_match("/^((\w+:\/\/)|\/)/", $text) === 0)
		{
			$text = \Nest\Core::$base_url.$text;
		}

		return parent::shelveURL($text);
	}

	/**
	 * Renders the source into HTML
	 *
	 * @param  string $file  The path to the file to render
	 * @param  mixed  $data  Any additional data to use when rendering
	 * @return string        The rendered html output
	 */
	public function render($file, $data = array())
	{
		return $this->TextileThis(file_get_contents($file));
	}

}
