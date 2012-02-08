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
	 * @throws \Nest\Exception
	 *
	 * @param  string $file  The path to the file to render
	 * @param  array  $data  Any additional data to use when rendering
	 * @return string        The rendered html output
	 */
	public function render($file, array $data = array())
	{
		if ( ! is_file($file))
		{
			throw new \Nest\Exception("File Not Found: {$file}");
		}
		
		extract($data, EXTR_SKIP);
		ob_start();
		include $file;
		return ob_get_clean();
	}
}
