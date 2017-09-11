<?php

if(!empty($_REQUEST['c'])) {
    unset($_REQUEST['c']);
}
if(empty($_REQUEST)) {
   $x = explode('?',($_SERVER['REQUEST_URI']));
    $_SERVER['REQUEST_URI'] = $x[0];
   $x = explode('/src/',$x[0]);

   $params = explode('/',$x[0]);

   $src = '/'.urldecode($x[1]);
   $_GET = $_REQUEST = array();
   $_GET['src'] = $_REQUEST['src'] = $src;
   $_SERVER['QUERY_STRING'] = 'src='.$src;
   foreach($params as $v) {
        $x = explode('__',$v);
        if(!empty($x[1])) {
           $_GET[$x[0]] = $_REQUEST[$x[0]] = $x[1];
           $_SERVER['QUERY_STRING'] .= '&'.$x[0].'='.$x[1];
       }
   }
}

require('phpThumb.php');