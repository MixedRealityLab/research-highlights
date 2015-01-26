<?php

/**
 * Research Highlights engine
 * 
 * Copyright (c) 2014 Martin Porcheron <martin@porcheron.uk>
 * See LICENCE for legal information.
 */

namespace RH\Submission;

/**
 * A user's submission.
 * 
 * @author Martin Porcheron <martin@porcheron.uk>
 */
class Submission extends \RH\AbstractModel {

	/** @var Images List of Images in the Submission to save */
	private $images;

	/**
	 * Construct the data object, with initial data values, if any.
	 * 
	 * @param mixed[] $data Data to construct initial object with
	 * @return New AbstractModel
	 */
	public function __construct($data = array()) {
		parent::__construct ($data);
		$this->images = new Images();
	}

	/**
	 * Take this submission and make substitutes for the keywords.
	 * 
	 * @param \RH\User\User $oUser User to make modifications for.
	 * @return Submission
	 */
	public function makeSubsts (\RH\User\User $oUser) {
		foreach ($this as $key => $value) {
			 $this->$key = $oUser->makeSubsts ($value);
		}

		return $this;
	}

	/**
	 * Add an image to be saved to disk.
	 * 
	 * @param string $filename name of the image
	 * @param string $data Image data
	 * @return void
	 */
	public function addImage ($filename, $data) {
		$image = new Image(array ('filename' => $filename, 'data' => $data));
		$this->images[] = $image;
	}

	/**
	 * Save this submission to the file system.
	 * 
	 * @return true
	 * @throws \RH\Error\System if something went wrong
	 */
	public function save () {
		$ext = Controller::DAT_FILE_SUF;
		$dir = DIR_DAT . '/' . $this->cohort . '/' . $this->saveAs  . '/' . date ('U') .'/';

		if (@mkdir ($dir, 0777, true) === false) {
			throw new \RH\Error\System ('Could not create directory to save input to');
		}

		foreach ($this->images as $image) {
			$filename = $dir . $image->filename;
			$data = $dir . $image->data;

			if (@\file_put_contents ($filename, $data) === false) {
				throw new \RH\Error\System ('Could not save image ' . $image->filename . ' to the system');
			}
		}

		foreach ($this as $key => $value) {
			if (@\file_put_contents ($dir . $key . $ext, $value) === false) {
				throw new \RH\Error\System ('Could not save ' . $key . ' to the system');
			}
		}

		return true;
	}

}