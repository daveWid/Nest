<?php

namespace Nest\Engine;

/**
 * Textile Renderer.
 *
 *
 * @package  Nest
 * @author   Dave Widmer <dave@davewidmer.net>
 */
class Textile extends \Textile implements \Nest\Engine
{
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
