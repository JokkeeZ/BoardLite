<?php

/**
 * Class used for uploading files into server, received via thread creation/reply adding request.
 *
 * @author JokkeeZ
 * @version 1.1
 *
 * @copyright Copyright © 2016 - 2018 JokkeeZ
 * @license Licensed under MIT License.
 */
class File extends Controller
{
	/**
	 * Uploads received file into server.
	 *
	 * @return bool If file was upload successfully, returns true; otherwise false.
	 */
	public function upload() : bool
	{
		// There is no file set with request so let's skip this stuff.
		if (empty($_FILES['file']['name']))
			return false;

		$this->generateFileName();

		$file = FILE_PATH . $this->getName();

		if (file_exists($file))
			return true;

		if ($this->getMaxUploadSize() > $_FILES['file']['size'])
			return false;

		return move_uploaded_file($_FILES['file']['tmp_name'], $file);
	}

	/**
	 * Gets file basename.
	 *
	 * @param string $suffix
	 * @return string Returns file basename.
	 */
	public function getName($suffix = null) : string
	{
		return basename($_FILES['file']['name'], $suffix);
	}

	/**
	 * Generates new name for the file.
	 */
	public function generateFileName()
	{
		$extension = '.' . pathinfo($this->getName(), PATHINFO_EXTENSION);
		$fileName = $this->getName($extension);

		$fileName = md5(time() . $fileName);
		$_FILES['file']['name'] = $fileName . $extension;
	}

	/**
	 * Get's post_max_size and upload_max_filesize values and selects smallest number from them.
	 *
	 * @return array|mixed
	 */
	private function getMaxUploadSize()
	{
		return min(
			$this->sizeToBytes(ini_get('post_max_size')),
			$this->sizeToBytes(ini_get('upload_max_filesize'))
		);
	}

	/**
	 * Converts given value into bytes.
	 *
	 * @param int $size
	 * @return int Returns size as bytes.
	 */
	private function sizeToBytes($size)
	{
		if (is_numeric($size))
			return $size;

		$suffix = substr($size, -1);
		$value = substr($size, 0, -1);

		$type = strtoupper($suffix);
		if ($type == 'P' || $type == 'T' || $type == 'G' || $type == 'M' || $type == 'K')
			$value *= 1024;

		return $value;
	}
}
