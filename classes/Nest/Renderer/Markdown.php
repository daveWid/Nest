<?php

namespace Nest\Renderer;

include \Nest\Core::find_file("vendor", "markdown".DIRECTORY_SEPARATOR."markdown");

/**
 * Markdown Renderer
 *
 * @package  Nest
 * @author   Dave Widmer <dave@davewidmer.net>
 */
class Markdown extends \MarkdownExtra_Parser implements \Nest\Renderer
{
	/**
	 * Adds some extra flavor into the Markdown parser
	 */
	public function __construct()
	{
		// doImage is 10, add image url just before
		$this->span_gamut['doImageURL'] = 9;

		// doLink is 20, add base url just before
		$this->span_gamut['doBaseURL'] = 19;

		parent::MarkdownExtra_Parser();
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
		return $this->transform(file_get_contents($file));
	}

	/**
	 * Add the current base url to all local links.
	 *
	 * URLs containing "://" are left untouched
	 *
	 * @param   string  $text  The text to check
	 * @return  string
	 */
	public function doBaseURL($text)
	{
		$pattern = '~(?<!!)(\[.+?\]\()(?!\w++://)(?!#)(\S*(?:\s*+".+?")?\))~';
		return preg_replace($pattern, '$1'.\Nest\Core::$base_url.'$2', $text);
	}

	/**
	 * Add the current base url to all local images.
	 *
	 * URLs containing "://" are left untouched
	 *
	 * @param   string  $text  The text to check
	 * @return  string
	 */
	public function doImageURL($text)
	{
		$pattern = '~(!\[.+?\]\()(?!\w++://)(\S*(?:\s*+".+?")?\))~';
		return preg_replace($pattern, '$1'.\Nest\Core::$base_url.'$2', $text);
	}

}
