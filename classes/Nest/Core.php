<?php

namespace Nest;

/**
 * The core class in the Nest library.
 *
 * This is where all of the magic happens!
 *
 * @package  Nest
 * @author   Dave Widmer <dave@davewidmer.net>
 */
class Core
{
	/**
	 * @var string The path to the document root for the project
	 */
	private $docroot = null;

	/**
	 * @var array  A list of supported rendering engines
	 */
	private $engines = array(
		'php' => "\\Nest\\Engine\\PHP",
		'md' => "\\Nest\\Engine\\Markdown",
		'markdown' => "\\Nest\\Engine\\Markdown",
		'textile' => "\\Nest\\Engine\\Textile",
		'mustache' => "\\Nest\\Engine\\Mustache"
	);

	/**
	 * Creates a new Nest object. If a configuration array isn't passed in
	 * then the system will try and load config.ini from the docroot.
	 *
	 * @param string $path    The path to the public server folder
	 */
	public function __construct($path)
	{
		$this->docroot = rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
	}

	/**
	 * Gets the full path to the docroot (public folder).
	 *
	 * @return string
	 */
	public function get_docroot()
	{
		return $this->docroot;
	}

	/**
	 * Gets the full path to the "_site" folder that holds to pages.
	 *
	 * @return string
	 */
	public function get_site_path()
	{
		return $this->docroot."_site".DIRECTORY_SEPARATOR;
	}

	/**
	 * The rendering engines you can use.
	 *
	 * @return array
	 */
	public function get_engines()
	{
		return $this->engines;
	}

	/**
	 * Runs the actions that builds the page.
	 *
	 * @param  string $file    The path to execute (null uses path_info)
	 * @param  string $layout  The path to the template file to use.
	 * @return string          The html to display.
	 */
	public function execute($file = null, $layout = "layout")
	{
		if ($file === null)
		{
			$file = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : "/" ;
		}

		// Get the page content first.
		$page = $this->find_file($file);
		$content = $this->render($page);

		// Now mix in into the layout and return
		$layout = $this->find_file($layout);
		return $this->render($layout, array(
			'content' => $content
		));
	}

	/**
	 * Locates a given file.
	 *
	 * @throws \Nest\Execption  Thrown when a page is not found
	 *
	 * @param  string $file  The filename of the view to find
	 * @return mixed         The (string) filename OR (boolean) false
	 */
	private function find_file($file)
	{
		if (substr($file, -1) === "/")
		{
			$file .= "index.html";
		}

		$base = preg_replace("/.html$/", "", ltrim($file, "/"));

		$exts = implode(",", array_keys($this->engines));
		$pattern = $this->get_site_path().$base.".{".$exts."}";
		$found = glob($pattern, GLOB_BRACE);

		if (empty($found))
		{
			throw new \Nest\Exception("The {$file} page could not be found");
		}

		return $found[0];
	}

	/**
	 * Takes a full server path and returns the rendered content.
	 *
	 * @param  string $path  The path to the resource
	 * @param  array         Parameters to add to the rendering engine
	 * @return string        Rendered HTML
	 */
	private function render($path, $params = array())
	{
		$ext = pathinfo($path, PATHINFO_EXTENSION);
		$engine = new $this->engines[$ext];

		return $engine->render($path, $params);
	}

}
