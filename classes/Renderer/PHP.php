<?php

namespace Nest\Renderer;

/**
 * PHP Renderer
 *
 * @package  Nest
 * @author   Dave Widmer <dave@davewidmer.net>
 */
class PHP implements \Nest\Renderer
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
		extract($data, EXTR_SKIP);
		ob_start();
		include $file;
		return ob_get_clean();
	}
}
