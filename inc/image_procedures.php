<?php

	function crop_pic($src, $w, $h) {
		
		$image = new HVImage( $src );
		$image->resize_fit_or_bigger( $w, $h );
		$image->crop( $w, $h, array(
			'type'=>'aligns',
			'vertical'=>'center'
		));
		$image->save($src);

	}
	
	
	
	/* EZ NEM FOG KELLENI */
	function crop_product_pic() {
		global $file, $moveto;
		
			$image = new HVImage( $file['tmp_name'] );
			$image->resize_fit_or_bigger( 170, 116 );
			$image->crop( 170, 116, array(
				'type'=>'aligns',
				'vertical'=>'center'
			));
			$image->save($moveto);
	}
	function crop_big_product_pic() {
		global $file;
		
			$image = new HVImage( $file['tmp_name'] );
			$image->resize_fit_or_bigger( 275, 500 );
			$image->crop( 275, 500, array(
				'type'=>'aligns',
				'vertical'=>'center'
			));
			$image->save($file['tmp_name'].'_2');
	}

?>