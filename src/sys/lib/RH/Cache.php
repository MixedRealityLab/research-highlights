<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2015 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH;

/**
 * Object cache controller
 *
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class Cache implements \RH\Singleton {

	/**
	 * Load a Model from the cache, or generate it from scratch. By default, this
	 * will also cache the generated model.
	 * 
	 * @param string $file Filename for the cache
	 * @param \RH\Model\AbstractModel $mModel Model to populate/restore from
	 * 	cache
	 * @param function $generateFn Function to generate the Model data if it 
	 * 	is not cached
	 * @param bool $cache Cache the model if it is generated.
	 * @param int $time Time in seconds that the cache lasts
	 * @return \RH\Model\AbstractModel
	 */
	public static function load ($file, \RH\Model\AbstractModel &$mModel, $generateFn, $cache = true, $time = CACHE_GENERAL) {
		$file = DIR_CAC . '/' . $file;
		\clearstatcache (true, $file);

		if (!\is_file ($file) || (\is_file ($file) && \filemtime ($file) + CACHE_GENERAL < \date ('U'))) {
			$generateFn ($mModel);
			if ($cache) {
				@\file_put_contents ($file, $mModel->serialize ());
				@\chmod ($file, 0777);
			}
		} else {
			$str = @\file_get_contents ($file);
			$mModel->unserialize ($str);
		}

		return $mModel;
	}

	/**
	 * Store a Model in the cache.
	 * 
	 * @param string $file Filename for the cache
	 * @param \RH\Model\AbstractModel $mModel Model store
	 */
	public static function store ($file, \RH\Model\AbstractModel &$mModel) {
		$file = DIR_CAC . '/' . $file;
		@\file_put_contents ($file, $mModel->serialize ());
		@\chmod ($file, 0777);
	}

	/**
	 * Clear a cache
	 * 
	 * @param string $file Filename of the cache to delete
	 */
	public static function clear ($file) {
		$file = DIR_CAC . '/' . $file;

		if (\is_file ($file)) {
			\unlink ($file);
		}

	}
}