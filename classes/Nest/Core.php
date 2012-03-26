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
	 * @var string The base path for the library.
	 */
	public static $system_path = null;

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
		$found = false;
		$path = static::$system_path.$dir.DIRECTORY_SEPARATOR.$file.$ext;
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
	 * @var array  A list of supported renderers
	 */
	public $renderers = array(
		'php' => "\\Nest\\Renderer\\PHP",
		'md' => "\\Nest\\Renderer\\Markdown",
		'markdown' => "\\Nest\\Renderer\\Markdown",
		'textile' => "\\Nest\\Renderer\\Textile",
		'mustache' => "\\Nest\\Renderer\\Mustache"
	);

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
		static::$system_path = $this->config->system_path;
	}

	/**
	 * Runs the actions that builds the page.
	 *
	 * @param  string $file   The path to execute (null uses path_info)
	 * @return string         The html to display.
	 */
	public function execute($file = null)
	{
		$this->file_path = $this->locate_view($file) ?:
			$this->get_default("error", ".md");

		$ext = pathinfo($this->file_path, PATHINFO_EXTENSION);

		$renderer = new $this->renderers[$ext];
		$content = $renderer->render($this->file_path);

		// Switch the renderer to php if it isnt already
		if ($ext !== "php")
		{
			$renderer = new \Nest\Renderer\PHP;
		}

		return $renderer->render($this->get_default("layout"), array(
			'content' => $content
		));
	}

	/**
	 * Locates a view that is used as the main content.
	 *
	 * @param  string $file  The filename of the view to find
	 * @return mixed         The (string) filename OR (boolean) false
	 */
	private function locate_view($file)
	{
		if ($file === null)
		{
			$file = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : "/" ;
		}

		if (substr($file, -1) === "/")
		{
			$file .= "index.html";
		}

		$base = preg_replace("/.html$/", "", ltrim($file, "/"));

		$exts = implode(",", array_keys($this->renderers));
		$pattern = $this->wiki_path().$base.".{".$exts."}";
		$found = glob($pattern, GLOB_BRACE);

		return (empty($found)) ? false : $found[0];
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
	 * Gets the path for the default layout/error page.
	 *
	 * This looks in the _wiki directory first and if not found, grabs the file
	 * from the system/views directory.
	 *
	 * @param  string $file  The filen ame to find
	 * @param  string $ext   The file extension
	 * @return string        The full path
	 */
	private function get_default($file, $ext = ".php")
	{
		$path = $this->wiki_path.$file.$ext;

		if ( ! is_file($path))
		{
			$path = static::find_file("views", $file, $ext);
		}

		return $path;
	}

}
