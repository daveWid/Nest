<?php

namespace Nest\Engine;

/**
 * Mustache Renderer.
 *
 * @package  Nest
 * @author   Dave Widmer <dave@davewidmer.net>
 */
class Mustache implements \Nest\Engine
{
	/**
	 * @var \Mustache  The mustache renderer
	 */
	private $mustache;

	/**
	 * Adds some extra flavor into the Markdown parser
	 */
	public function __construct()
	{
		$this->mustache = new \Mustache;
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
		return $this->mustache->render(file_get_contents($file), $data);
	}

}
