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
	 * @param  string $source  The source text
	 * @return string          The rendered html output 
	 */
	public function render($source);
}
