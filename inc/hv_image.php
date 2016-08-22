<?php
 
/*
	HV-Image
*/
 
class HVImage {
	
	var $image;
	var $image_type;
	
	function __construct( $filename ) {
		$image_info = getimagesize( $filename );
		$this->image_type = $image_info[2];
		
		if( $this->image_type == IMAGETYPE_JPEG )
			$this->image = imagecreatefromjpeg( $filename );
		elseif( $this->image_type == IMAGETYPE_GIF )
			$this->image = imagecreatefromgif( $filename );
		elseif( $this->image_type == IMAGETYPE_PNG )
			$this->image = imagecreatefrompng( $filename );
	
	}
	
	function save( $filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null ) {
		if ( $image_type == IMAGETYPE_JPEG )
			imagejpeg( $this->image, $filename, $compression );
		elseif ( $image_type == IMAGETYPE_GIF )
			imagegif( $this->image, $filename );
		elseif ( $image_type == IMAGETYPE_PNG )
			imagepng( $this->image, $filename );
		
		if ( $permissions != null )
			chmod( $filename, $permissions );
	}
	
	function output( $image_type=IMAGETYPE_JPEG ) {
		if ( $image_type == IMAGETYPE_JPEG )
			imagejpeg( $this->image );
		elseif( $image_type == IMAGETYPE_GIF )
			imagegif( $this->image );
		elseif( $image_type == IMAGETYPE_PNG )
			imagepng( $this->image );
	}
	
	function getWidth() { return imagesx( $this->image ); }
	
	function getHeight() { return imagesy( $this->image ); }
	
	function resizeToHeight( $height ) {
		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width,$height);
	}
	function resizeToWidth( $width ) {
		$ratio = $width / $this->getWidth();
		$height = $this->getheight() * $ratio;
		$this->resize( $width, $height );
	}
	
	function scale( $scale ) {
		$width = $this->getWidth() * $scale/100;
		$height = $this->getheight() * $scale/100;
		$this->resize( $width, $height );
	}
	
	function resize( $width, $height ) {
		$new_image = imagecreatetruecolor( $width, $height );
		imagecopyresampled( $new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight() );
		$this->image = $new_image;
	}

	/*
		Resizes the image's dimensions based on the parameters, having one fitting exactly,
		and the other one being bigger than the given value.
	*/
	function resize_fit_or_bigger($width, $height) {

		$w = $this->getWidth();
		$h = $this->getHeight();

		$ratio = $width / $w;
		if (($h*$ratio)<$height)
			$ratio = $height / $h;
		
		$h *= $ratio;
		$w *= $ratio;

		$this->resize($w, $h);
	}

	/*
		Resizes the image's dimensions based on the parameters, having one fitting exactly,
		and the other one being smaller than the given value.
	*/
	function resize_fit_or_smaller($width, $height) {

		$w = $this->getWidth();
		$h = $this->getHeight();

		$ratio = $width / $w;
		if (($h*$ratio)>$height)
			$ratio = $height / $h;
		
		$h *= $ratio;
		$w *= $ratio;

		$this->resize($w, $h);
	}
	
	function crop( $width, $height, $mode = array()) {
		/*
			$mode parameter's structure:
			$mode = array(
				'type'=>'offsets'/'aligns'
				,'left'/'right'/'top'/'bottom'=>{value}
				,'vertical'/'horizontal'=>'left'/'center'/'right'
			)
		*/
		$thumb = imagecreatetruecolor( $width, $height);
		$w = $this->getWidth();
		$h = $this->getHeight();
		if ($mode['type']=='aligns') {

			if (isset($mode['horizontal'])) {
				if ($mode['horizontal']=='right')
					$y = $w - $width;
				else if ($mode['horizontal']=='center')
					$x = ($w/2) - ($width/2);
				else
					$x = 0;
			} else
				$x = 0;

			if (isset($mode['vertical'])) {
				if ($mode['vertical']=='bottom')
					$y = $h - $height;
				else if ($mode['vertical']=='center')
					$y = ($h/2) - ($height/2);
				else
					$y = 0;
			} else
				$y = 0;

		} else {
			if (isset($mode['left']))
				$x = $mode['left'];
			else if (isset($mode['right']))
				$y = $w - $width - $mode['right'];
			else
				$x = 0;

			if (isset($mode['top']))
				$y = $mode['top'];
			else if (isset($mode['bottom']))
				$y = $h - $height - $mode['bottom'];
			else
				$y = 0;
		}

		if ($x<0)
			$x = 0;
		if ($y<0)
			$y = 0;

		imagecopy( $thumb, $this->image, 0, 0, $x, $y, $width, $height );
		$this->image = $thumb;
	}
	
	function rotate( $angle=45 ) { $this->image = imagerotate( $this->image, $angle, 0 ); }
	
	function setMinDimensions( $width, $height ){
		
		$w = $this->getWidth();
		$h = $this->getHeight();
		if ( $w < $width ) {
			$ratio = $width/$w;
			$w = $width;
			$h *= $ratio;
		}
		if ( $h < $height ) {
			$ratio = $height/$h;
			$h = $height;
			$w *= $ratio;
		}
		
		if ( $h < $height )
			$h = $height;
		if ( $w < $width )
			$w = $width;
		
		$this->resize( $w, $h );
	
	}
	
	function setMaxDimensions( $width, $height ){
		
		$w = $this->getWidth();
		$h = $this->getHeight();
		if ( $w > $width ) {
			$ratio = $width/$w;
			$w = $width;
			$h *= $ratio;
		}
		if ( $h > $height ) {
			$ratio = $height/$h;
			$h = $height;
			$w *= $ratio;
		}
		
		if ( $h > $height )
			$h = $height;
		if ( $w > $width )
			$w = $width;
		
		$this->resize( $w, $h );
	}
 

}
?>