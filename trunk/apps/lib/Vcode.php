<?php
namespace Lib;
class Vcode{
	public $text = '';
	public $font = 'Arial';
	public $fontsize = 18;
	public $fontcolor = 'gray';
	public $glow_radius = 15;
	public $glow = array(
			    "#ff0000",
			    "#ff8800",
			    "#ffff00"
			);
	public $offset = 1;

	public function __construct($text=''){
		if($text==''){
			$this->text = $this->getCode();
		}else{
			$this->text = $text;
		}
		$_SESSION['image_random_value'] = $text;

		
	}

	public function getCode(){
		return mt_rand(1000, 9999);
	}

	public function validateCode($code){
		return $_SESSION['image_random_value'] == $code;
	}

	public function output(){
		$pallete = new \Imagick;
		$pallete->newimage(45, 25, "#000000");
		$pallete->setimageformat("gif");
		$draw = new \imagickdraw();
		$draw->setgravity(\imagick::GRAVITY_CENTER);
		// $draw->setfont($this->font);
		$draw->setfontsize($this->fontsize);


		foreach ($this->glow as $var) 
		{
		    $draw->setfillcolor($var);
		    $pallete->annotateImage($draw, 0, $this->offset, 0, $this->text);
		    $pallete->annotateImage($draw, 0, $this->offset, 0, $this->text);
		    $pallete->BlurImage($this->glow_radius, $this->glow_radius);
		}

		$draw->setfillcolor($this->fontcolor);
		$pallete->annotateImage($draw, 0, $this->offset, 0, $this->text);
		$pallete->setImageFormat("gif");
		return $pallete;
	}
}





// header("Content-Type: image/gif");
// echo $pallete;