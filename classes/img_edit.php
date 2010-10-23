<?php
class img_edit
{
	var $path; // путь к файлу
	var $extension; // расширение

	var $img_resource;
	var $new_resource;

	var $width;
	var $height;

	var $width_n;
	var $height_n;

	function img_edit($path){
		$this->path = $path;
		$im = getimagesize($path);
		$this->width = $im['0'];
		$this->height = $im['1'];
		$this->extension = $im['2'];
		$this->_load_img();
	}

	/**
	* создаёт и возвращает ресурс картинки из файла
	*/
	function _load_img(){
		switch($this->extension){
			case "IMAGETYPE_JPEG":
				$ires = imagecreatefromjpeg($this->path);
				break;
			case "IMAGETYPE_GIF":
				$ires = imagecreatefromgif($this->path);
				break;
			case "IMAGETYPE_PNG":
				$ires = imagecreatefrompng($this->path);
				break;
			default:
				$ires = imagecreatefromjpeg($this->path);
				break;
		}
		$this->img_resource = $ires;
		return $this->img_resource;
	}

	/**
	* считает "качество", больше - хуже
	*/
	function _qualify($type, $quality){
		$quality = abs($quality);
		switch($type){
			case "width":
				$min = $this->width_n;
				$max = $this->width;
				break;
			case "height":
				$min = $this->height_n;
				$max = $this->height;
				break;
		}
		$size = $min + abs(($max - $min) / $quality);
		return round($size);
	}

	/**
	* красиво ресайзит, возвращает ресурс картинки
	*/
	function _resize($quality = 1){
		$thumb = imagecreatetruecolor($this->width_n, $this->height_n);
		imagecolortransparent($thumb, imagecolorallocate($thumb, 0, 0, 0));
		imagealphablending($thumb, false);
		imagesavealpha($thumb, true);
		// here we create new resized img
		if($quality != 1){
			$new_image = imagecreatetruecolor($this->_qualify('width', $quality), $this->_qualify('height', $quality));
			imagecolortransparent($new_image, imagecolorallocate($new_image, 0, 0, 0));
			imagealphablending($new_image, false);
			imagesavealpha($new_image, true);
			imagecopyresized($new_image, $this->img_resource, 0, 0, 0, 0, $this->_qualify('width', $quality), $this->_qualify('height', $quality), $this->width, $this->height);
		}else{
			$new_image = $this->img_resource;
		}
		// and resample it ...
		imagecopyresampled($thumb, $new_image, 0, 0, 0, 0, $this->width_n, $this->height_n, $this->_qualify('width', $quality), $this->_qualify('height', $quality));
		imagedestroy($new_image);
		$this->new_resource = $thumb;
		return $this->new_resource;
	}

	function set_wh($width, $height = 0){
		$this->width_n = $width;
		$this->height_n = (($height == 0) ? $this->height / ($this->width / $this->width_n) : $height );
	}

	function out($todo = 'show', $q = 3, $path = null){
		if(!$q){ $q = 3; }
		$this->_resize($q);
		switch($todo){
			case 'show':
				header('Content-type: image/jpeg');
				imagejpeg($this->new_resource, null, 100);
				break;
			case "save":
			default:
				header('Content-type: image/jpeg');
				imagejpeg($this->new_resource, $path, 100);
				break;
		}
	}
}

?>
