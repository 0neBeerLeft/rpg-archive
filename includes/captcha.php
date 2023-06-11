<?php
	session_start();
	// image grootte enz ..
	$img_width = 150;
	$img_height = 50;
	
	$min_size = 15;
	$max_size = 16;
	
	$min_angle = -25;
	$max_angle = 25;
	
	$ttf_fonts = array();
	
	if(function_exists("imagefttext"))
	{
		$ttfdir = @opendir("captcha_fonts");
		
		if($ttfdir)
		{
			while($file = readdir($ttfdir))
			{
				if(is_file("captcha_fonts/".$file) && get_extension($file) == "ttf")
				{
					$ttf_fonts[] = "captcha_fonts/".$file;
				}
			}
		}
	}
	
	if(count($ttf_fonts) > 0)
	{
		$use_ttf = 1;
	}
	else
	{
		$use_ttf = 0;
	}
	
	if(gd_version() >= 2)
	{
		$im = imagecreatetruecolor($img_width, $img_height);
	}
	else
	{
		$im = imagecreate($img_width, $img_height);
	}
	
	if(!$im)
	{
		die("No GD support.");
	}
	
	$bg_color = imagecolorallocate($im, 255, 255, 255);
	imagefill($im, 0, 0, $bg_color);
	$to_draw = mt_rand(0, 2);
	
	if($to_draw == 1)
	{
		draw_circles($im);
	}
	elseif($to_draw == 2)
	{
		draw_squares($im);
	}
	else
	{
		draw_lines($im);
	}
	
	draw_dots($im);
	// hoeveel cijfers / letters
	$captcha_code = random_code(3);
	
	$_SESSION['captcha_code'] = $captcha_code;
	
	draw_string($im, $captcha_code);
	
	$border_color = imagecolorallocate($im, 0, 0, 0);
	imagerectangle($im, 0, 0, $img_width - 1, $img_height - 1, $border_color);
	
	header("Content-type: image/png");
	
	imagepng($im);
	imagedestroy($im);
	
	exit;
	
	function draw_lines(&$im)
	{
		global $img_width, $img_height;
		
		for($i = 10; $i < $img_width; $i+=10)
		{
			$color = imagecolorallocate($im, mt_rand(150, 255), mt_rand(150, 255), mt_rand(150, 255));
			imageline($im, $i, 0, $i, $img_height, $color);
		}
		
		for($i = 10; $i < $img_height; $i+=10)
		{
			$color = imagecolorallocate($im, mt_rand(150, 255), mt_rand(150, 255), mt_rand(150, 255));
			imageline($im, 0, $i, $img_width, $i, $color);
		}
	}
	
	function draw_circles(&$im)
	{
		global $img_width, $img_height;
		
		$circles = $img_width * $img_height / 100;
		
		for($i = 0; $i <= $circles; ++$i)
		{
			$color = imagecolorallocate($im, mt_rand(180, 255), mt_rand(180, 255), mt_rand(180, 255));
			$pos_x = mt_rand(1, $img_width);
			$pos_y = mt_rand(1, $img_height);
			$circ_width = ceil(mt_rand(1, $img_width) / 2);
			$circ_height = mt_rand(1, $img_height);
			
			imagearc($im, $pos_x, $pos_y, $circ_width, $circ_height, 0, mt_rand(200, 360), $color);
		}
	}
	
	function draw_dots(&$im)
	{
		global $img_width, $img_height;
		
		$dot_count = $img_width * $img_height / 5;
		
		for($i = 0; $i <= $dot_count; ++$i)
		{
			$color = imagecolorallocate($im, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
			imagesetpixel($im, mt_rand(0, $img_width), mt_rand(0, $img_height), $color);
		}
	}
	
	function draw_squares(&$im)
	{
		global $img_width, $img_height;
		
		$square_count = 30;
		
		for($i = 0; $i <= $square_count; ++$i)
		{
			$color = imagecolorallocate($im, mt_rand(150, 255), mt_rand(150, 255), mt_rand(150, 255));
			$pos_x = mt_rand(1, $img_width);
			$pos_y = mt_rand(1, $img_height);
			$sq_width = $sq_height = mt_rand(10, 20);
			$pos_x2 = $pos_x + $sq_height;
			$pos_y2 = $pos_y + $sq_width;
			imagefilledrectangle($im, $pos_x, $pos_y, $pos_x2, $pos_y2, $color);
		}
	}
	
	function draw_string(&$im, $string)
	{
		global $use_ttf, $min_size, $max_size, $min_angle, $max_angle, $ttf_fonts, $img_height, $img_width;
		
		if(empty($string))
		{
			return false;
		}
		
		$spacing = $img_width / strlen($string);
		$string_length = strlen($string);
		
		for($i = 0; $i < $string_length; ++$i)
		{
			if($use_ttf)
			{
				$font_size = mt_rand($min_size, $max_size);
				
				$font = array_rand($ttf_fonts);
				$font = $ttf_fonts[$font];
				
				$rotation = mt_rand($min_angle, $max_angle);
				
				$r = mt_rand(0, 200);
				$g = mt_rand(0, 200);
				$b = mt_rand(0, 200);
				$color = imagecolorallocate($im, $r, $g, $b);
				
				$dimensions = imageftbbox($font_size, $rotation, $font, $string[$i], array());
				$string_width = $dimensions[2] - $dimensions[0];
				$string_height = $dimensions[3] - $dimensions[5];
				
				$pos_x = $spacing / 4 + $i * $spacing;
				$pos_y = ceil(($img_height - $string_height / 2));
				
				if($pos_x + $string_width > $img_width)
				{
					$pos_x = $pos_x - ($pox_x - $string_width);
				}
				
				$shadow_x = mt_rand(-3, 3) + $pos_x;
				$shadow_y = mt_rand(-3, 3) + $pos_y;
				$shadow_color = imagecolorallocate($im, $r + 20, $g + 20, $b + 20);
				
				imagefttext($im, $font_size, $rotation, $shadow_x, $shadow_y, $shadow_color, $font, $string[$i], array());
				imagefttext($im, $font_size, $rotation, $pos_x, $pos_y, $color, $font, $string[$i], array());
			}
			else
			{
				$string_width = imagefontwidth(5);
				$string_height = imagefontheight(5);
				
				$pos_x = $spacing / 4 + $i * $spacing;
				$pos_y = $img_height / 2 - $string_height - 10 + mt_rand(-3, 3);
				
				if(gd_version() >= 2)
				{
					$temp_im = imagecreatetruecolor(15, 20);
				}
				else
				{
					$temp_im = imagecreate(15, 20);
				}
				
				$bg_color = imagecolorallocate($temp_im, 255, 255, 255);
				
				imagefill($temp_im, 0, 0, $bg_color);
				imagecolortransparent($temp_im, $bg_color);
				
				$r = mt_rand(0, 200);
				$g = mt_rand(0, 200);
				$b = mt_rand(0, 200);
				$color = imagecolorallocate($temp_im, $r, $g, $b);
				
				$shadow_x = mt_rand(-1, 1);
				$shadow_y = mt_rand(-1, 1);
				$shadow_color = imagecolorallocate($temp_im, $r + 50, $g + 50, $b + 50);
				
				imagestring($temp_im, 5, 1 + $shadow_x, 1 + $shadow_y, $string[$i], $shadow_color);
				imagestring($temp_im, 5, 1, 1, $string[$i], $color);
				
				imagecopyresized($im, $temp_im, $pos_x, $pos_y, 0, 0, 40, 55, 15, 20);
				
				imagedestroy($temp_im);
			}
		}
	}
	
	function gd_version()
	{
		static $gd_version;
		
		if($gd_version)
		{
			return $gd_version;
		}
		
		if(!extension_loaded("gd"))
		{
			return;
		}
		
		ob_start();
		phpinfo(8);
		$info = ob_get_contents();
		ob_end_clean();
		$info = stristr($info, "gd version");
		preg_match("/\d/", $info, $gd);
		$gd_version = $gd[0];
		
		return $gd_version;
	}
	
	function get_extension($file)
	{
		return strtolower(substr(strrchr($file, "."), 1));
	}
	
	function random_code($length = 4)
	{
		$value = "";
		//$charset = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
		$charset = array("0","1","2","3","4","5","6","7","8","9");
		
		for($i = 0; $i < $length; $i++)
		{
			$set = mt_rand(0, count($charset) - 1);
			$value .= $charset[$set];
		}
		
		return $value;
	}

?>