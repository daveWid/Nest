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
	 * @param MarkdownExtra_Parser  The markdown instance to use when rendering the text
	 */
	private $md;

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
		$this->md = $this;
	}

	/**
	 * Renders the Markdown source into HTML
	 *
	 * @param  string $source  The source text
	 * @return string          The rendered html output 
	 */
	public function render($source)
	{
		return $this->md->transform($source);
	}

	/**
	 * Add the current base url to all local links.
	 *
	 * URLs containing "://" are left untouched
	 *
	 * @param   string  span text
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
	 * @param   string  span text
	 * @return  string
	 */
	public function doImageURL($text)
	{
		$pattern = '~(!\[.+?\]\()(?!\w++://)(\S*(?:\s*+".+?")?\))~';
		return preg_replace($pattern, '$1'.\Nest\Core::$base_url.'$2', $text);
	}

}
