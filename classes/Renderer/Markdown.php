<?php

namespace Nest\Renderer;

include \Nest\Core::find_file("vendor", "markdown".DIRECTORY_SEPARATOR."markdown");

/**
 * Markdown Renderer
 *
 * @package  Nest
 * @author   Dave Widmer <dave@davewidmer.net>
 */
class Markdown implements \Nest\Renderer
{
	/**
	 * Renders the Markdown source into HTML
	 *
	 * @param  string $source  The source text
	 * @return string          The rendered html output 
	 */
	public function render($source)
	{
		return Markdown($source);
	}

}
