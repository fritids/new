<?php
class WPACImage{
	public static function resizeTo($newW,$newH,$origFileName,$newFileName=null){
		
		$loc=$imgNameAll=$origFileName;
		/*if($newFileName!=null){
			$loc=$imgNameAll=$newFileName;
		}*/
		$fileType=substr($origFileName, strrpos($origFileName, ".")+1);

		if($fileType=="jpg"){
			$src = imagecreatefromjpeg($loc);
		}
		if($fileType=="png"){
			$src = imagecreatefrompng($loc);
		}
		$size = getimagesize($loc);
		$origWidth= $size[0];
		$origHeight = $size[1];
		
		
		$new_width=$newW;
		if($new_width=="0" || $new_width=="auto"){
			$new_width=$newH/$origHeight*$origWidth;
			$newW=$new_width;
		}
		
		$new_height=$newH;
		if($new_height=="0" || $new_height=="auto"){
			$new_height=$newW/$origWidth*$origHeight;
			$newH=$new_height;
		}
		echo $new_width." - ".$new_height;
		
		$xscale=$origWidth/$new_width;
		$yscale=$origHeight/$new_height;

		if ($yscale<$xscale){
			$new_width = round($origWidth * (1/$yscale));
			$new_height = round($origHeight * (1/$yscale));
		}
		else {
			$new_width = round($origWidth * (1/$xscale));
			$new_height = round($origHeight * (1/$xscale));
		}
		
		if($fileType=="jpg"){
			$tempdest = imagecreatetruecolor($new_width, $new_height);
			imagecopyresampled($tempdest, $src, 0, 0, 0, 0, $new_width, $new_height, $origWidth, $origHeight);
			$dest = imagecreatetruecolor($newW, $newH);
			if($new_width>$newW){
				imagecopyresampled($dest, $tempdest, 0, 0, ($new_width-$newW)/2, 0, $newW,$newH, $newW, $newH);
			}else{
				imagecopyresampled($dest, $tempdest, 0, 0, 0, ($new_height-$newH)/2, $newW,$newH, $newW, $newH);
			}
			if($newFileName!=null){
				imagejpeg($dest, $newFileName, 95);
			}else{
				imagejpeg($dest, $origFileName, 95);
			}
			//file_put_contents("temp1.txt",$origFileName." | ".$fileType);
		}
		if($fileType=="png"){
			$tempdest = imagecreatetruecolor($new_width, $new_height);
	        imagealphablending($tempdest, false);
	        $color = imagecolorallocatealpha($tempdest, 0, 0, 0, 127);
	        imagefill($tempdest, 0, 0, $color);
			imagecopyresampled($tempdest, $src, 0, 0, 0, 0, $new_width, $new_height, $origWidth, $origHeight);
			
			
			$src = imagecreatefrompng($loc);
			$trnprt_indx = imagecolortransparent($src);
			$dest = imagecreatetruecolor($newW, $newH);
	        imagealphablending($dest, false);
	        $color = imagecolorallocatealpha($dest, 0, 0, 0, 127);
	        imagefill($dest, 0, 0, $color);
	        imagesavealpha($dest, true);
			
			if($new_width>$newW){
				imagecopyresampled($dest, $tempdest, 0, 0, ($new_width-$newW)/2, 0, $newW,$newH, $newW, $newH);
			}else{
				imagecopyresampled($dest, $tempdest, 0, 0, 0, ($new_height-$newH)/2, $newW,$newH, $newW, $newH);
			}
			if($newFileName!=null){
				imagepng($dest, $newFileName, 0);
			}else{
				imagepng($dest, $origFileName, 0);
			}
			
		}
		imagedestroy($dest);
		imagedestroy($src);
		imagedestroy($tempdest);
	}
}
?> 