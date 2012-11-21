<?php

namespace Dxapp\Service;

use AsseticBundle\Service as AsseticService,
	Assetic\Factory,
	Assetic\Asset\AssetInterface,
	Assetic\AssetWriter;

class ThemeAssets extends AsseticService
{

	private function cache(AssetInterface $asset)
	{
		return $this->configuration->getCacheEnabled() ? new AssetCache($asset, new FilesystemCache($this->configuration->getCachePath())) : $asset;
	}

	private function initFilters(array $filters)
	{
		$result = array();

		$fm = $this->getFilterManager();

		foreach ($filters as $alias => $options)
		{
			$option = null;
			if (is_array($options))
			{
				if (!isset($options['name']))
				{
					throw new \InvalidArgumentException(
							'Filter "' . $alias . '" required option "name"'
					);
				}

				$name = $options['name'];
				$option = isset($options['option']) ? $options['option'] : null;
			}
			elseif (is_string($options))
			{
				$name = $options;
				unset($options);
			}

			if (is_numeric($alias))
			{
				$alias = $name;
			}

			if (!$fm->has($alias))
			{
				$filter = new $name($option);
				if (is_array($option))
				{
					call_user_func_array(array($filter, '__construct'), $option);
				}

				$fm->set($alias, $filter);
			}

			$result[] = $alias;
		}

		return $result;
	}

	public function renderThemeAssets($name, $assets)
	{
		$conf = (array) $assets;

		$factory = new Factory\AssetFactory($conf['root_path']);
		$factory->setAssetManager($this->getAssetManager());
		$factory->setFilterManager($this->getFilterManager());
		$factory->setDebug($this->configuration->isDebug());

		$collections = (array) $conf['collections'];
		foreach ($collections as $name => $options)
		{
			$assets = isset($options['assets']) ? $options['assets'] : array();
			$filters = isset($options['filters']) ? $options['filters'] : array();
			$options = isset($options['options']) ? $options['options'] : array();
			$options['output'] = isset($options['output']) ? $options['output'] : $name;

			$filters = $this->initFilters($filters);

			/** @var $asset \Assetic\Asset\AssetCollection */
			$asset = $factory->createAsset($assets, $filters, $options);

			# allow to move all files 1:1 to new directory
			# its particulary usefull when this assets are images.
			if (isset($options['move_raw']) && $options['move_raw'])
			{
				foreach ($asset as $key => $value)
				{
					$name = md5($value->getSourceRoot() . $value->getSourcePath());
					$value->setTargetPath($value->getSourcePath());
					$value = $this->cache($value);
					$this->assetManager->set($name, $value);
				}
			}
			else
			{
				$asset = $this->cache($asset);
				$this->assetManager->set($name, $asset);
			}
		}

		$writer = new AssetWriter($this->configuration->getWebPath());
		$writer->writeManagerAssets($this->assetManager);
	}

}