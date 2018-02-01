<?php defined('APP') or die;

/**
 * Class used for uploading files into server, received via thread creation/reply adding request.
 *
 * @author JokkeeZ
 * @version 1.1
 * @copyright Copyright © 2016 JokkeeZ
 */
class FileController extends Controller {
	
	/**
	 * Uploads received file into server.
	 * TODO: EXIF removing from file (.JPG)
	 *
	 * @return boolean
	 */
	public function upload():bool {
		// There is no file set with request so let's skip this stuff.
		if (empty($_FILES['file']['name'])) return false;

		$file = FILE_PATH . $this->get_name();

		// File already exists, so we can use already existing file without re-uploading it?
		//
		// ISSUE: If another file, let's say cat picture exists with name asd.png and user is uploading
		// car picture with same name... yeah you can figure out.. It's bad.
		// TODO: Maybe store files with random name? Only re-using same image is bit hard then.
		if (file_exists($file)) return true;

		if ($this->get_max_upload_size() > $_FILES['file']['size']) return false;

		return move_uploaded_file($_FILES['file']['tmp_name'], $file);
	}

	/**
	 * Gets file basename.
	 */
	public function get_name():string {
		return basename($_FILES['file']['name']);
	}

	/**
	 * Get's post_max_size and upload_max_filesize values and selects smallest number from them.
	 */
	private function get_max_upload_size() {
		return min(
			$this->size_to_bytes(ini_get('post_max_size')),
			$this->size_to_bytes(ini_get('upload_max_filesize'))
		);
	}

	/**
	 * Converts given value into bytes.
	 */
	private function size_to_bytes($size) {
		if (is_numeric($size))
			return $size;

		$suffix = substr($size, -1);
		$value = substr($size, 0, -1);

		$type = strtoupper($suffix);
		if ($type == 'P' || $type == 'T' || $type == 'G' || $type == 'M' || $type == 'K') {
			$value *= 1024;
		}
		return $value;
	}
}
