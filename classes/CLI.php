<?php

namespace Nest;

/**
 * Command Line Interface class for Nest.
 *
 * @package  Nest
 * @author   Dave Widmer <dave@davewidmer.net>
 */
class CLI
{
	/**
	 * @var string  The full path to the current directory.
	 */
	private $directory;

	/**
	 * @var string  The action to run
	 */
	private $action = null;

	/**
	 * @var array   A list of actions that can be run form the command line
	 */
	private $action_list = array(
		'create',
		'generate',
		'clean',
		'help'
	);

	/**
	 * @var array   A list of command line flags
	 */
	private $flags = array(
		"-v" => array("verbose", true)
	);

	/**
	 * @var boolean Show log messages to the screen?
	 */
	private $verbose = false;

	/**
	 * Parses out the command line variables
	 */
	public function __construct(array $vars)
	{
		$this->directory = getcwd().DIRECTORY_SEPARATOR;

		// Find the action to run
		if (isset($vars[1]))
		{
			if (in_array($vars[1], $this->action_list))
			{
				$this->action = $vars[1];
			}
		}

		// Parse the command line flags
		if (count($vars) > 2)
		{
			$this->parse_flags(array_slice($vars, 2));
		}
	}

	/**
	 * Runs the given action
	 */
	public function run()
	{		
		// Default to the help screen...
		if ($this->action === null)
		{
			$this->action = "help";
		}

		$this->{$this->action}();
	}

	/**
	 * Creates a new Nest site
	 */
	private function create()
	{
		$this->log("Creating Site at {$this->directory}");

		// The source directory to copy
		$source = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.
			"..".DIRECTORY_SEPARATOR.
			"files").DIRECTORY_SEPARATOR;

		$this->cp_r($source, $this->directory);

		$this->log("Nest site created!", true);
	}

	/**
	 * Copies all of the files to a given destination, recursively.
	 *
	 * @param string $source       The source path
	 * @param string $destination  The destination path
	 */
	private function cp_r($source, $destination)
	{
		$di = new \DirectoryIterator($source);
		foreach ($di as $entity)
		{
			if ( ! $entity->isDot())
			{
				if ($entity->isDir())
				{
					$name = $entity->getFilename();
					$path = $destination.$name.DIRECTORY_SEPARATOR;
					if ( ! is_dir($path))
					{
						$this->log("Creating Directory: {$name}");
						mkdir($path, 0755);
					}

					// Do a recursive copy
					$this->cp_r($source.$name.DIRECTORY_SEPARATOR, $path);
				}
				else
				{
					$name = $entity->getFileName();
					$this->log("Copying: {$name}");
					copy($entity->getRealPath(), $destination.$name);
				}
			}
		}
	}

	/**
	 * Generates the whole site into static html files.
	 */
	private function generate()
	{
		$this->log("Generating Site");

		$config = parse_ini_file($this->directory."config.ini");
		$nest = new \Nest\Core($this->directory, new \Nest\Config($config));

		// Find all of the source files
		$files = $this->find_files($nest->wiki_path(), "/{$config['extension']}$/");

		foreach ($files as $entity)
		{
			$name = str_replace($nest->wiki_path(), "", $entity->getPathname());
			$name = str_replace($config['extension'], ".html", $name);

			$filename = $this->directory.$name;
			$dir = dirname($filename);

			if ( ! is_dir($dir))
			{
				$this->log("Creating directory: {$dir}");
				mkdir($dir, 0755);
			}

			$this->log("Saving file: {$name}");
			$fp = fopen($filename, "w+");
			fwrite($fp, $nest->execute($name));
			fclose($fp);
		}

		$this->log("Site Generated", true);
	}

	/**
	 * Uses the directory and regex pattern to find all files in a directory.
	 *
	 * @param  string $directory  The directory to search
	 * @param  string $pattern    Regex pattern to use
	 * @return \FileIterator
	 */
	private function find_files($directory, $pattern)
	{
		$recursive = new \RecursiveDirectoryIterator($directory);
		$iterator = new \RecursiveIteratorIterator($recursive);
		return new \RegexIterator($iterator, $pattern);
	}

	/**
	 * Cleans out all of the files that were created by a generate call.
	 */
	private function clean()
	{
		$this->log("Cleaning started");

		$config = parse_ini_file($this->directory."config.ini");
		$nest = new \Nest\Core($this->directory, new \Nest\Config($config));

		// Find all of the source files
		$files = $this->find_files($nest->wiki_path(), "/{$config['extension']}$/");

		// Keep a list of all directories
		$directories = array();

		foreach ($files as $entity)
		{
			$name = str_replace($nest->wiki_path(), "", $entity->getPathname());
			$name = str_replace($config['extension'], ".html", $name);

			$filename = $this->directory.$name;
			$dir = dirname($filename).DIRECTORY_SEPARATOR;

			if ( ! in_array($dir, $directories))
			{
				$directories[] = $dir;
			}

			if (is_file($filename))
			{
				$this->log("Removing file: {$filename}");
				unlink($filename);
			}
		}

		// Now cleanup the directories
		foreach ($directories as $dir)
		{
			$path = str_replace($this->directory, "", $dir);
			if ($path !== "")
			{
				if (is_dir($dir))
				{
					$this->log("Removing directory: {$dir}");
					rmdir($dir);
				}
			}
		}

		$this->log("Cleanup completed", true);
	}

	/**
	 * The Nest help menu.
	 */
	private function help()
	{
		$this->verbose = true; // Flip on verbose
		$this->log("---------------------");
		$this->log("Nest CLI usage");
		$this->log("---------------------");
		$this->log("nest [create|generate|help] [flags]");
		$this->log("---------------------");
		$this->log("create:   Creates a new Nest site");
		$this->log("generate: Generates a static html version of the site");
		$this->log("clean:    Cleans up a generated site");
		$this->log("help:     This help menu");
		$this->log("---------------------");
		$this->log("Flags");
		$this->log("---------------------");
		$this->log("-v: Displays verbose output of the script.");
		$this->log("");
	}

	/**
	 * Parses the command line flags.
	 *
	 * @param array $flags The command line flags
	 */
	private function parse_flags(array $flags)
	{
		foreach ($flags as $flag)
		{
			if (array_key_exists($flag, $this->flags))
			{
				list($prop, $value) = $this->flags[$flag];
				$this->{$prop} = $value;
			}
		}
	}

	/**
	 * Logs a message.
	 * If the verbose flag is set, the message will be output to the screen.
	 *
	 * @param string  $msg    The message to log
	 * @param boolean $force  Show the message, not matter the verbose flag?
	 */
	private function log($msg, $force = false)
	{
		if ($this->verbose OR $force)
		{
			echo $msg."\n";
		}
	}

}
