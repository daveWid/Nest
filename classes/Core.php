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
	 * @var string The path to the vendor folder.
	 */
	private static $library_path = null;

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
		$this->public_path = dirname($path).DIRECTORY_SEPARATOR;
		$this->wiki_path = $this->public_path."_wiki".DIRECTORY_SEPARATOR;
		$this->config = $config;

		$renderer = "\\Nest\\Renderer\\".$config->renderer;
		$this->renderer = new $renderer;
	}

	/**
	 * Runs the actions that builds the page.
	 *
	 * @return string   The html to display.
	 */
	public function execute()
	{
		$file = (isset($_SERVER['PATH_INFO'])) ?
			$_SERVER['PATH_INFO'] :
			"/" ;

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
		return $this->render($path);
	}

	/**
	 * Renders the content as a full html page.
	 *
	 * @param  string $file  The file path to the content to render
	 * @return string        The rendered content
	 */
	private function render($file)
	{
		// View data
		$data = array(
			'content' => $this->renderer->render(file_get_contents($file))
		);

		// Import the view variables to local namespace
		extract($data, EXTR_SKIP);

		// Capture the view output
		ob_start();

		try
		{
			include $this->get_layout();
		}
		catch (Exception $e)
		{
			// Delete the output buffer
			ob_end_clean();

			// Re-throw the exception
			throw $e;
		}

		// Get the captured output and close the buffer
		return ob_get_clean();
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
			static::find_file("view", "layout");
	}

	/**
	 * Gets the path to the error page
	 *
	 * @return string  Full server path to the error page.
	 */
	private function get_error()
	{
		$wiki = $this->wiki_path."error".$this->config->extension;
		
		return (is_file($wiki)) ?
			$wiki :
			static::find_file("view", "error", $this->config->extension);
	}

}
