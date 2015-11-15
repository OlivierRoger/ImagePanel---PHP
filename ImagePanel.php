<?php

if ($argc < 3)
  {
    echo "Missing Argument : Argument is like ./imagepanel.php [options] lien1 base";
  }
else
  {
    $option = $argv[1]; 
    $ma_base = $argv[$argc - 1];
    $j = 2;
    $pattern = "/([a-z\-_0-9\/\:\.]*\.(jpg|jpeg|png|gif))/i";
    $size_x = 0;
    $cor_x = 10;
    $cor_y = 10;
    $calc_size = 20;
    $result_count = 0;
    $result_content = "";
    $i = 0;
    while ($j < ($argc - 1))
      {
	$file_name = $argv[$j];
	$handle = fopen($file_name, "r");
	$content = fread($handle, 200000);
	$count_img = preg_match_all($pattern, $content, $matches);
	$result_content ="$result_content . $content";
	$result_count = ($count_img + $result_count);
	$j++;	
	fclose($handle);
      }
    echo "$result_count \n";
    $count_img = preg_match_all($pattern, $result_content, $matches);
    
    echo "Il y a $count_img nombres d'images \n";
    
    while ($i < $result_count)
      {//Code retour pour size si fichier non existant
	$size_img = getimagesize($matches[0][$i]);
	$calc_size = $size_img[1] + $calc_size;
	if ($size_x < $size_img[0])
	  $size_x = $size_img[0];
	echo $size_img[1];
	echo $matches[0][$i];
	echo "\n";
	$i++;
      }

    echo "Hauteur = $calc_size, Largeur = $size_x \n"; 
    $i = 0;
    $image = imagecreatetruecolor($size_x, $calc_size);
    $white = imagecolorallocate($image, 255, 255, 255);
    imagefilledrectangle($image, 0, 0, $size_x, $calc_size, $white);
    while ($i < $result_count)
      {
	if (preg_match("/.jpg/i", $matches[0][$i]) == 1)
	  $image_next = imagecreatefromjpeg($matches[0][$i]);
	elseif (preg_match("/.gif/i", $matches[0][$i]) == 1)
	  $image_next = imagecreatefromgif($matches[0][$i]);
	elseif (preg_match("/.png/i", $matches[0][$i]) == 1)
	  $image_next = imagecreatefrompng($matches[0][$i]);
	else
	  echo "Warning extension problem !";
	imagecopy($image, $image_next, $cor_x, $cor_y, 0, 0, imagesx($image_next), imagesy($image_next));
	$cor_y = $cor_y + imagesy($image_next);
	$i++;
      }
    imagejpeg($image, './$ma_base.jpg');

    imagedestroy($image);
  }
