<?php

global $_bdd_host,$_bdd_type,$_bdd_pwd,$_bdd_bdd,$_bdd_user;
/**
 * Connexion à la base de donnée
 */
$_bdd_user = 'canalu';
$_bdd_pwd = 'HTH545Fe';
$_bdd_bdd = 'canalu';
$_bdd_host = 'localhost';
$_bdd_type = 'mysqli';

$_Gconfig['debugIps'][] = '78.192.187.121';

ini_set("display_errors", "On");

$_Gconfig['chgrpFiles'] = 'canalu';
$_Gconfig['chmodFiles'] = 0755;

$_Gconfig['solr']['url'] = 'http://127.0.0.1:8080/solr/canalu/';

$_Gconfig['rtmp'] = 'rtmp://streamer.cerimes.fr/vod/canalu/videos/';
$_Gconfig['httpStream'] = 'http://streamer.cerimes.fr/canalu/';
$_Gconfig['httpDwl'] = 'http://www.canal-u.tv/';
$_Gconfig['urlDiapoDirect'] = 'http://canaludev.canal-u.tv/var/canalu/storage/diapos-direct/';


$_Gconfig['solr']['solarium'] = array(
    'adapteroptions' => array(
        'host' => '127.0.0.1',
        'port' => 8080,
        'path' => '/solr/canalu/',
    )
);



error_reporting(E_ALL);

