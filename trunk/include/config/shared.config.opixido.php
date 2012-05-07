<?php

global $_bdd_host,$_bdd_type,$_bdd_pwd,$_bdd_bdd,$_bdd_user;
/**
 * Connexion à la base de donnée
 */
$_bdd_user = 'user';
$_bdd_pwd = 'lasergun';
$_bdd_bdd = 'canalu_1';
$_bdd_host = 'localhost';
$_bdd_type = 'mysqli';

$_Gconfig['debugIps'][] = '192.168.1.';
$_Gconfig['debugIps'][] = '82.239.70.160';
$_Gconfig['debugIps'][] = '82.127.60.74';

ini_set("display_errors", "On");

$_Gconfig['chgrpFiles'] = 'www-data';
$_Gconfig['chmodFiles'] = 0755;

$_Gconfig['solr']['url'] = 'http://127.0.0.1:8989/solr/';

$_Gconfig['rtmp'] = 'rtmp://streamer.cerimes.fr/vod/canalu/videos/';
$_Gconfig['httpStream'] = 'http://streamer.cerimes.fr/canalu/';
$_Gconfig['httpDwl'] = 'http://www.canal-u.tv/';
$_Gconfig['urlDiapoDirect'] = 'http://www.canal-u.tv/var/canalu/storage/diapos-direct/';

$_Gconfig['solr']['solarium'] = array(
    'adapteroptions' => array(
        'host' => '127.0.0.1',
        'port' => 8989,
        'path' => '/solr/',
    )
);


error_reporting(E_ALL);