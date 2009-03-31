<?php

/*
$_GET['q'] = 95;
$_GET['w'] = 95;
$_GET['h'] = 95;
$_GET['src'] = '/home/zouzou/bourrine/fichier/b_image/50/image_img_collines.jpg';
$_GET['f'] = 'jpeg';
*/

$q = $_SERVER["QUERY_STRING"];
$q = str_replace('_XYZ_','&',$q);

parse_str($q,$_GET);

//$_SERVER["QUERY_STRING"] = 'q=95&zc=1&w=72&h=72&src=/home/zouzou/bourrine/fichier/b_image/50/image_img_collines.jpg&f=jpeg&bg=FFFFFF';
include('index.php');


?>