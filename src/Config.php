<?php
/**
 * Created by PhpStorm.
 * User: bosunski
 * Date: 14/10/2018
 * Time: 1:36 PM
 */

namespace AutoVh;


use Illuminate\Support\Collection;

final class Config
{
	private $configs;

	/**
	 * Config constructor.
	 *
	 * @param Collection $configs
	 */
	public function __construct(Collection $configs)
	{
		$this->configs = $configs;
	}

	/**
	 * @return array
	 */
	public function getConfigs(): Collection {
		return $this->configs;
	}

	/**
	 * @param $key
	 *
	 * @return string
	 */
	public function getConfig($key): string
	{
		return $this->configs->get($key) ?? null;
	}
}