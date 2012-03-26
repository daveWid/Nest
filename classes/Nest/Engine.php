<?php

namespace Nest;

/**
 * An interface that all rendering engines must follow.
 *
 * @package  Nest
 * @author   Dave Widmer <dave@davewidmer.net>
 */
interface Engine
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
