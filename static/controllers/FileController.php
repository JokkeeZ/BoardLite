<?php defined('APP') or die;

/**
 * Class used for uploading files into server, received via thread creation/reply adding request.
 *
 * @author JokkeeZ
 * @version 1.0
 * @copyright Copyright © 2016 JokkeeZ
 */
class FileController {
	
	/**
	 * Uploads received file into server.
	 * TODO: EXIF removing from file (.JPG)
     *
	 * @return boolean
	 */
	public function upload() {
		
		// There is no file set with request so let's skip this stuff.
		if (empty($_FILES['file']['name'])) return false;

		// Let's create path for file.
		$file = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . basename($_FILES['file']['name']);
		
		// File already exists, so we can use already existing file without re-uploading it?
        //
        // ISSUE: If another file, let's say cat picture exists with name asd.png and user is uploading
        // car picture with same name... yeah you can figure out.. It's bad.
        // TODO: Maybe store files with random name? Only re-using same image is bit hard then.
		if (file_exists($file)) return true;
		
		// File is bigger than allowed file size in PHP ini config.
		if ($this->getMaximumFileUploadSize() > $_FILES['file']['size']) return false;
		
		// Well it didn't exist and was correct size, so let's try to upload it.
		return move_uploaded_file($_FILES['file']['tmp_name'], $file);
	}

	private function getMaximumFileUploadSize() {
		return min(
			$this->convertSizeToBytes(ini_get('post_max_size')),
			$this->convertSizeToBytes(ini_get('upload_max_filesize'))
		);
	}
	
	private function convertSizeToBytes($size) {
		if (is_numeric($size)) {
			return $size;
		}
		
		$suffix = substr($size, -1);
		$value = substr($size, 0, -1);
		
		$type = strtoupper($suffix);
		if ($type == 'P' || $type == 'T' || $type == 'G' || $type == 'M' || $type == 'K') {
			$value *= 1024;
		}
		return $value;
	}
}
