<?php

namespace Nest\Engine;

// We need this for the MarkdownExtra_Parser class...
include_once realpath(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."Markdown.php");

/**
 * Markdown Renderer
 *
 * @package  Nest
 * @author   Dave Widmer <dave@davewidmer.net>
 */
class Markdown extends \MarkdownExtra_Parser implements \Nest\Engine
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
		return $this->transform(file_get_contents($file));
	}

}
