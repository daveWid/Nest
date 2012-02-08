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
	 * @var string The base url for all files on the site.
	 */
	public static $base_url;

	/**
	 * @var string The path to the vendor folder.
	 */
	private static $library_path = null;

	/**
	 * Gets a url for an asset.
	 *
	 * @param  string $path The url of the asset to find
	 * @return string       The url with the base path prepended
	 */
	public static function url($path = "")
	{
		return static::$base_url.$path;
	}

	/**
	 * Finds a file from the library file sytstem.
	 *
	 * @param  string $dir   The directory to look in
	 * @param  string $file  The file to find
	 * @param  string $ext   The file extension
	 * @return string        The full file path
	 */
	public static function find_file($dir, $file, $ext = ".php")
	{
		if (static::$library_path === null)
		{
			static::$library_path = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR."..").DIRECTORY_SEPARATOR;
		}

		$found = false;
		$path = static::$library_path.$dir.DIRECTORY_SEPARATOR.$file.$ext;
		if (is_file($path))
		{
			$found = $path;
		}

		return $found; 
	}

	/**
	 * @var string The path to the public folder
	 */
	private $public_path;

	/**
	 * @var string The full path to all of the wiki files.
	 */
	private $wiki_path;

	/**
	 * @var \Nest\Config  The configuration object
	 */
	private $config;

	/**
	 * @var \Nest\Renderer The renderer to use for the file
	 */
	private $renderer;

	/**
	 * @var string The name of the file that was requested to be loaded.
	 */
	private $file_path;

	/**
	 * Creates a new Core object
	 *
	 * @param string       $path    The path to the public server folder
	 * @param \Nest\Config $config  The site configuration
	 */
	public function __construct($path, \Nest\Config $config)
	{
		$this->public_path = rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
		$this->wiki_path = $this->public_path."_wiki".DIRECTORY_SEPARATOR;
		$this->config = $config;

		static::$base_url = $this->config->base_url;

		$renderer = "\\Nest\\Renderer\\".$config->renderer;
		$this->renderer = new $renderer;
	}

	/**
	 * Runs the actions that builds the page.
	 *
	 * @param  string   The path to execute (if null, then path_info is used)
	 * @return string   The html to display.
	 */
	public function execute($file = null)
	{
		if ($file === null)
		{
			$file = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : "/" ;
		}

		if (substr($file, -1) === "/")
		{
			$file .= "index.html";
		}

		$this->file_path = preg_replace("/.html$/", $this->config->extension, ltrim($file, "/"));

		// See if the file is actually there
		$path = (is_file($this->wiki_path.$this->file_path)) ?
			$this->wiki_path.$this->file_path :
			$this->get_error();

		// Now we need the layout...
		$php = new \Nest\Renderer\PHP;

		return $php->render($this->get_layout(), array(
			'content' => $this->renderer->render($path)
		));
	}

	/**
	 * Gets the full path to the docroot (public folder).
	 *
	 * @return string
	 */
	public function docroot()
	{
		return $this->public_path;
	}

	/**
	 * Gets the full path to the "_wiki" folder that holds to pages.
	 *
	 * @return string
	 */
	public function wiki_path()
	{
		return $this->wiki_path;
	}

	/**
	 * Gets the path to the layout.
	 *
	 * @return string  The path to the layout file
	 */
	private function get_layout()
	{
		$layout = $this->wiki_path."layout.php";

		return (is_file($layout)) ?
			$layout :
			static::find_file("views", "layout");
	}

	/**
	 * Gets the path to the error page
	 *
	 * @return string  Full server path to the error page.
	 */
	private function get_error()
	{
		$path = $this->wiki_path."error".$this->config->extension;
		
		if ( ! is_file($path))
		{
			// If the default error file, the change to markdown and find the file
			$this->renderer = new \Nest\Renderer\Markdown;
			$path = static::find_file("views", "error", ".md");
		}

		return $path;
	}

}
