<?php

class Image{
	private $img_name;

	public $IMG;
	private $base64_string;
    
    function __construct($filename=''){
        if(!empty($filename)){
            self::image_create_from_url($filename);
        }
    }

	public function create_image_from_base64($base_64){
		$this->base64_string=$base_64;
		self::create_imgage_from_base64_string();
	}

	public function create_image_from_file($img_content){
        echo "Ok";
        file_get_contents($img_content);
		$this->base64_string = base64_encode($img_content);
		self::create_imgage_from_base64_string();
	}

    function imagecreatefromjpeg( $filename ) {
        $this->IMG = imagecreatefromjpeg($filename);
    }

    function image_create_from_url( $filename ) {
        $filename = $_SERVER['DOCUMENT_ROOT'].$filename;
        if (!file_exists($filename)) {
            throw new InvalidArgumentException('File "'.$filename.'" not found.');
        }
        switch ( strtolower( pathinfo( $filename, PATHINFO_EXTENSION ))) {
            case 'jpeg':
            case 'jpg':
                $this->IMG = imagecreatefromjpeg($filename);
            break;

            case 'png':
                $this->IMG = imagecreatefrompng($filename);
            break;

            case 'gif':
                $this->IMG = imagecreatefromgif($filename);
            break;

            default:
                throw new InvalidArgumentException('File "'.$filename.'" is not valid jpg, png or gif image.');
            break;
        }
    }

	private function create_imgage_from_base64_string(){
		if(!$this->IMG = imagecreatefromstring(base64_decode($this->base64_string))){
			throw new Exception('The image type is unsupported, the data is not in a recognised format, or the image is corrupt and cannot be loaded.');
		}
	}

	public function resize_image($new_width, $new_height){
		$new_img = imagecreatetruecolor($new_width, $new_height);
		imagecopyresampled($new_img, $this->IMG, 0, 0, 0, 0, $new_width, $new_height, imagesx($this->IMG), imagesy($this->IMG));
		$tmp_obj = new Image();
		$tmp_obj->IMG=$new_img;
		return $tmp_obj;
	}

	public function add_watermark($src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h){
		if(!imagecopy($this->IMG, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h)){
			throw new Exception('Problem while adding watermark');
		}
	}


	/**
	*	angle - Rotation angle, in degrees. The rotation angle is interpreted as the number of degrees to rotate the image anticlockwise.
	*	bgd_color - Specifies the color of the uncovered zone after the rotation
	*	ignore_transparent - If set and non-zero, transparent colors are ignored (otherwise kept).
	**/
	public function rotate($angle , $bgd_color=0, $ignore_transparent = 0){
		$angle=(int)$angle;
		$this->IMG = imagerotate($this->IMG, $angle , $bgd_color, $ignore_transparent);
		if(!$this->IMG){
			throw new Exception('Problem while rotating img');
		}
	}

	public function save($path, $new_img_name, $new_img_ext, $quality=70, $replace_file=false){
		$path = $_SERVER['DOCUMENT_ROOT'].$path;
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
		$tmp_name=$new_img_name;

        $new_img_ext=(empty($new_img_ext))?"":'.'.$new_img_ext;
        if(!$replace_file){
            $try=0;
            while (file_exists($path . $tmp_name . $new_img_ext)) {
                $tmp_name = $new_img_name."(".$try.")";
                $try++;
            }   
        }
		if(!imagejpeg ($this->IMG, $path.$tmp_name.$new_img_ext, $quality)){
			throw new Exception('Problem while saving photo');
		}
		return $tmp_name.$new_img_ext;
	}

	public function get_img_size(){
		return array(imagesx($this->IMG), imagesy($this->IMG));
	}

	function __destruct(){
        if($this->IMG){
            imagedestroy($this->IMG);
        }
	}



}

?>
