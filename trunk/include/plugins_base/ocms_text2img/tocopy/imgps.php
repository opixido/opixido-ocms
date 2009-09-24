<?php


define('CACHE_IS_ON',false);
header('content-type:text/html;charset=utf-8');

$cacheid = ''.md5($_SERVER['REQUEST_URI']).'.png';

$cachePath = './imgc/';

/*
$cachePath = '../include/cache/imgps/';
if(CACHE_IS_ON && file_exists($cachePath.$cacheid)) {
	//print $cache->cache_path;
	header('X-Cache-type:aggressive');
	header('Content-type:image/png');
	print (readfile($cachePath.$cacheid));
	die();
}

//
*/

setlocale(LC_ALL,'fr_FR.UTF8','fr_FR.UTF8@euro','fr','fr_FR');

$profile = array(
	'x'=>0,
	'y'=>3,
	'font'=>'./DINEA___.PFB',
	'font'=>'./storno.ttf',
	'text'=>'Lorem Ipsum',
	'textSize'=>17,
	'align'=>'l',
	'imgW'=>0,
	'imgH'=>21,
	'lineSpacing'=>10,
	'textColor'=>'FFFFFF',
	'bgColor'=>'000000',
	'maxHeight' => false,
	'bgColor'=> 'FFFFFF',
	'textColor'=> '000000'
	);



if($_REQUEST['profile']) {

	switch($_REQUEST['profile']){
		case 'titre_jaune' :
			$profile['bgColor']= 'FFFF01';
			break;
			
		case 'titre_noir' :
			$profile['bgColor']= '000000';
			$profile['textColor']= 'FFFFFF';
					
			break;
		case 'titre':
			$profile['imgW'] = 480;
			break;
			
		default:

		break;

	}
}

if($_REQUEST['nb'] == 2 && $_REQUEST['profile'] == 'principal') {
		$profile['textSize']=25;
}



//
$x = $_REQUEST['x'] ? $_REQUEST['x'] : $profile['x'];
$y = $_REQUEST['y'] ? $_REQUEST['y'] :$profile['y'];

$font = $_REQUEST['font'] ? $_REQUEST['font'] : $profile['font'];


$text = $_REQUEST['text'] ? stripslashes ( $_REQUEST['text'] ) : $profile['text'];
//$text = utf8_decode(mb_strtoupper(str_replace("’","'",($text)),'utf-8'));

//$text = urldecode($text);

$text = html_entity_decode($text,ENT_QUOTES,'iso-8859-1');
$text = utf8_encode($text);
$text = str_replace('&rsquo;',"'",$text);
//$text = mb_strtoupper($text,'utf-8');


$textSize = $_REQUEST['textSize'] ? $_REQUEST['textSize'] :$profile['textSize'];
$align = $_REQUEST['align'] ? $_REQUEST['align'] : $profile['align'];
$imgW = $_REQUEST['imgW'] ? $_REQUEST['imgW'] : $profile['imgW'];
$width = $_REQUEST['width'] ? $_REQUEST['width'] :$imgW-5;
$lineSpacing = $_REQUEST['lineSpacing'] ? $_REQUEST['lineSpacing'] :$profile['lineSpacing'];
$textColor = $_REQUEST['textColor'] ? $_REQUEST['textColor']:$profile['textColor'];
$bgColor=$_REQUEST['bgColor'] ? $_REQUEST['bgColor']:$profile['bgColor'];
$caps = $_REQUEST['caps'] ? $_REQUEST['caps']: $profile['caps'];
$maxHeight = $_REQUEST['maxHeight'] ? $_REQUEST['maxHeight']: $profile['maxHeight'];

$text = str_replace(('’'),"'",$text);
$text = str_replace('-','-',$text);


$x += 2;

	function fromhex($string,&$image) {
	 sscanf($string, "%2x%2x%2x", $red, $green, $blue);
	 return ImageColorAllocate($image,$red,$green,$blue);
	}	

	if($caps) {
		include('../include/global/lg.functions.php');
		$text = majuscules($text);
	}

   $textSize -=2;
   //Recalculate X and Y to have the proper top/left coordinates instead of TTF base-point
   $y += $textSize;
   $dimensions = imagettfbbox($textSize, 0, $font, " "); //use a custom string to get a fixed height.
   $x -= $dimensions[4]-$dimensions[0];
/*
   if(!$imgW) {
   		$imgW = $dimensions[3]-$dimensions[0];
   		$text = $imgW . $text;
   }
*/
   	if($imgW <= 0) {
		$dimensions = imagettfbbox($textSize,0,$font,$text);
		$imgW = $dimensions[4] - $dimensions[0] + 5;
		//$text = implode('-',$dimensions);
		$width = $imgW -5;
	}

   $text = str_replace ("\r", '', $text); //Remove windows line-breaks
   $srcLines = explode ("\n", $text); //Split text into "lines"
   $dstLines = Array(); // The destination lines array.
   foreach ($srcLines as $currentL) {
       $line = '';
       $words = explode (" ", $currentL); //Split line into words.
       $nword = array();
       foreach ($words as $word) {
       	$word = explode('-',$word);
       	$nbc = count($word);
       	foreach($word as $k=>$w) {
       		if($nbc>1 && $k<($nbc-1)) {
       			$w .= '-';
       		}
       		$nword[] = $w;
       	}
       }
       $words = $nword;
       foreach ($words as $word) {
           $dimensions = imagettfbbox($textSize, 0, $font, $line.$word);
           $lineWidth = $dimensions[4] - $dimensions[0]; // get the length of this line, if the word is to be included
           if ($lineWidth > $width && !empty($line) ) { // check if it is too big if the word was added, if so, then move on.
           		/*if(substr($word,-1) == '-') {
           			$dstLines[] = ''.trim($line);
           		} else {*/
           			$dstLines[] = ' '.trim($line);
           		//}
                //Add the line like it was without spaces.
               $line = '';
           }
           if(substr($word,-1) == '-') {
           		$line .= $word.'';
           } else {
           		$line .= $word.' ';
           }
       }

       	$dstLines[] =  ' '.trim($line); //Add the line when the line ends.
   }

   if(trim($dstLines[count($dstLines)-1]) == '' ) {
   		array_pop($dstLines);
   }


   //Calculate lineheight by common characters.
   $dimensions = imagettfbbox($textSize, 0, $font, "MXQJPmxéqàjp123É"); //use a custom string to get a fixed height.
   $lineHeight = $dimensions[1] - $dimensions[5] ; // get the height of this line
   $lineHeight -= $lineSpacing;




	$imgHeight = ($lineHeight)*count($dstLines)+$y/2;
	if(($maxHeight)) {
		if($imgHeight > $maxHeight) {
			$imgHeight =   $maxHeight ;
		}
	}

	if($_SERVER['SERVER_ADDR'] != '192.168.1.234' && $align == 'c') {
		$imgHeight+=1;
	}

	$img = imageCreateTrueColor($imgW,$imgHeight);

	//$color = imagecolorallocate($img, 255, 255, 255);
	$color = $textColor = fromhex($textColor,$img);
	$bgColor = fromhex($bgColor,$img);

	imagefill($img,0,0,$bgColor);

//	imageSaveAlpha($img, true);
//	ImageAlphaBlending($img, false);


   $align = strtolower(substr($align,0,1)); //Takes the first letter and converts to lower string. Support for Left, left and l etc.
   foreach ($dstLines as $nr => $line) {
       if ($align != "l") {
           $dimensions = imagettfbbox($textSize, 0, $font, $line);
           $lineWidth = $dimensions[4] - $dimensions[0]; // get the length of this line
           if ($align == "r") { //If the align is Right
               $locX = $x + $width - $lineWidth;
           } else { //If the align is Center
               $locX = $x + ($width/2) - ($lineWidth/2);
           }
       } else { //if the align is Left
       		/*if($x < 0)
       			$x = 0;*/
           $locX = $x;
       }
       $locY = $y + ($nr * $lineHeight);
       //Print the line.

       imagettftext($img, $textSize, 0, $locX, $locY, $color, $font, $line);
   }





	header('Content-type:image/png;charset=utf-8');
	//imagegif($img);
	//imagegif($img,$cache->cache_path);
	//imagepng($img);
//	imagepng($img,$cache->cache_path);
	imagepng($img,$cachePath.$cacheid);
	@chmod($cachePath.$cacheid,0777);
	readfile($cachePath.$cacheid);
	//die();

	ImageDestroy ( $img );
	die();



