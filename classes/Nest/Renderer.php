<?php

namespace Nest;

/**
 * The renderer interface.
 *
 * @package  Nest
 * @author   Dave Widmer <dave@davewidmer.net>
 */
interface Renderer
{
	/**
	 * Renders the source into HTML
	 *
	 * @param  string $file  The path to the file to render
	 * @param  mixed  $data  Any additional data to use when rendering
	 * @return string        The rendered html output
	 */
	public function render($file, $data = array());
}
