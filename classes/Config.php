<?php

namespace Nest;

/**
 * Configuration for the Nest library.
 *
 * @package  Nest
 * @author   Dave Widmer <dave@davewidmer.net>
 */
class Config
{
	/**
	 * @var string  The base_url for the site
	 */
	public $base_url = "/";

	/**
	 * @var string  The system url
	 */
	public $system_url = "";

	/**
	 * Creates a new configuration object
	 *
	 * @param array $data The data for the configuration.
	 */
	public function __construct(array $data)
	{
		foreach ($data as $key => $value)
		{
			$this->{$key} = $value;
		}
	}

}
