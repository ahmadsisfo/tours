<?php
class Madmintoolsimage extends Model {
	public function resize($filename, $width, $height) {
		if (!is_file(DI_IMAGE . $filename)) {
			return ;
		}
		
		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		$old_image = $filename;
		$new_image = 'cache/' . substr($filename, 0, strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

		if (!is_file(DI_IMAGE . $new_image) || (filectime(DI_IMAGE . $old_image) > filectime(DI_IMAGE . $new_image))) {
			$path = '';

			$directories = explode('/', dirname(str_replace('../', '', $new_image)));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir(DI_IMAGE . $path)) {
					@mkdir(DI_IMAGE . $path, 0777);
				}
			}

			list($width_orig, $height_orig) = getimagesize(DI_IMAGE . $old_image);

			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image(DI_IMAGE . $old_image);
				$image->resize($width, $height);
				$image->save(DI_IMAGE . $new_image);
			} else {
				copy(DI_IMAGE . $old_image, DI_IMAGE . $new_image);
			}
		}

		if ($this->request->server['HTTPS']) {
			return HTTPS_ASSETS . 'image/' . $new_image;
		} else {
			return HTTP_ASSETS . 'image/' . $new_image;
		}
	}
}