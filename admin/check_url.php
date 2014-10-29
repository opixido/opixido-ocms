<?php

#
# This file is part of oCMS.
#
# oCMS is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# oCMS is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with oCMS. If not, see <http://www.gnu.org/licenses/>.
#
# @author Celio Conort / Opixido 
# @copyright opixido 2009
# @link http://code.google.com/p/opixido-ocms/
# @package ocms
#



define('IN_ADMIN', true);

error_reporting(E_ALL & ~E_NOTICE);

require_once('../include/include.php');


/* On aura toujours besoin de ca */

$gb_obj = new genBase();

$gb_obj->includeConfig();

if (!empty($_REQUEST['lg'])) {
    $lg = $_SESSION['lg'] = $_REQUEST['lg'];
} else if (!empty($_SESSION['lg'])) {
    $lg = $_SESSION['lg'];
} else {
    $lg = LG_DEF;
}

define('LG', $lg);


$gb_obj->includeBase();

$gb_obj->includeGlobal();
ini_set('default_socket_timeout', 10);
global $context;
$context = stream_context_create(array(
    'http' => array(
        'timeout' => 10,
    )
        ));

function checkUrl($row, $first = true) {
    global $context, $co;
    $site = $row['url'];
    global $timeCheck;
    set_time_limit(60);
    $type = 'ok';
    $err = '';

    $tt = microtime(true);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $site);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $options = array(
        CURLOPT_RETURNTRANSFER => true, // return web page
        CURLOPT_HEADER => false, // don't return headers
        CURLOPT_FOLLOWLOCATION => true, // follow redirects
        CURLOPT_ENCODING => "", // handle all encodings
        CURLOPT_USERAGENT => "Mozilla/5.0 (X11; Windows i686; rv:2.0.1) Gecko/20100101 Firefox/9.0.1", // who am i
        CURLOPT_AUTOREFERER => true, // set referer on redirect
        CURLOPT_REFERER => 'http://www.google.fr/search?hl=en&safe=off&output=search&sclient=psy-ab&q=test&btnG=&gbv=1&',
        CURLOPT_CONNECTTIMEOUT => 20, // timeout on connect
        CURLOPT_TIMEOUT => 20, // timeout on response
        CURLOPT_MAXREDIRS => 1 // stop after 2 redirects
    );
    curl_setopt_array($ch, $options);
    $res = curl_exec($ch);
    $err = curl_errno($ch);
    $errmsg = curl_error($ch);
    $header = curl_getinfo($ch);

    $newUrl = $header['url'];
    $nnewUrl = parse_url($newUrl);
    $oldUrl = parse_url($row['site_url']);
    if ($nnewUrl['host'] != $oldUrl['host']) {
        $err .= ' REDIRECT ETRANGE  : ' . $oldUrl['host'] . ' => ' . $nnewUrl['host'] . ' ';
    }

    $tt = microtime(true) - $tt;

    if (!$res || $err) {
        $err .= $errmsg;
        $err .= 'CONNECT';
    }

    if (strlen($res) < 500) {
        $err .= ' SHORT ' . strlen($res) . ' : ' . $res;
    }

    if ($err) {
        $type = 'error';
    }

    curl_close($ch);

    $text = array();
    $text['N/A'] = "Ikke HTTP";
    $text[OK] = "Valid hostname";
    $text[FEJL] = "Invalid hostname";
    $text[Dd] = "No response";
    $text[100] = "Continue";
    $text[101] = "Switching Protocols";
    $text[200] = "OK";
    $text[201] = "Created";
    $text[202] = "Accepted";
    $text[203] = "Non-Authoritative Information";
    $text[204] = "No Content";
    $text[205] = "Reset Content";
    $text[206] = "Partial Content";
    $text[300] = "Multiple Choices";
    $text[301] = "Moved Permanently";
    $text[302] = "Found";
    $text[303] = "See Other";
    $text[304] = "Not Modified";
    $text[305] = "Use Proxy";
    $text[307] = "Temporary Redirect";
    $text[400] = "Bad Request";
    $text[401] = "Unauthorized";
    $text[402] = "Payment Required";
    $text[403] = "Forbidden";
    $text[404] = "Not Found";
    $text[405] = "Method Not Allowed";
    $text[406] = "Not Acceptable";
    $text[407] = "Proxy Authentication Required";
    $text[408] = "Request Timeout";
    $text[409] = "Conflict";
    $text[410] = "Gone";
    $text[411] = "Length Required";
    $text[412] = "Precondition Failed";
    $text[413] = "Request Entity Too Large";
    $text[414] = "Request-URI Too Long";
    $text[415] = "Unsupported Media Type";
    $text[416] = "Requested Range Not Satisfiable";
    $text[417] = "Expectation Failed";
    $text[500] = "Internal Server Error";
    $text[501] = "Not Implemented";
    $text[502] = "Bad Gateway";
    $text[503] = "Service Unavailable";
    $text[504] = "Gateway Timeout";
    $text[505] = "HTTP Version Not Supported";


    $okcode = array(200, 302, 301, 'OK', '');
    $tryagain = array(204, 205, 400, 403, 405, 501, 500, 502, 404);
    $code = $header['http_code'];

    $sql = 'SELECT * FROM s_badlinks WHERE '
            . 'bad_table LIKE ' . sql($row['table']) . ' '
            . 'AND bad_id = ' . sql($row['id']) . ' '
            . 'AND bad_champ = ' . sql($row['champ']) . ' '
            . 'AND bad_url = ' . sql($row['url']) . '  ';
    //$res = $dbh->prepare($sql);

    $req = $co->getRow($sql);

    if (!in_array($code, $okcode)) {
        //$dbh = new PDO('mysql:host=127.0.0.1;dbname=www_ined_fr', 'www', '2014:adrdsw');
        $row['code'] = $code;
        /* verife et maj si exist
         * right here and right now!
         */


        if (empty($req['id']) || !$req) {
            /*
             * existe pas on insert
             */
            $sql = 'INSERT INTO s_badlinks(id,bad_table,bad_id,bad_champ,bad_url,bad_code,bad_last_date) VALUES("", '
                    . '' . sql($row['table']) . ', '
                    . '' . sql($row['id']) . ', '
                    . '' . sql($row['champ']) . ', '
                    . '' . sql($row['url']) . ', '
                    . '' . sql($row['code']) . ','
                    . 'NOW() ) ';
            $res = $co->execute($sql);
        } else {
            /*
             * sinon on update!
             */
            $sql = 'UPDATE s_badlinks SET bad_last_date = NOW(), bad_code = ' . sql($row['code']) . ', bd_nb = ' . sql($req['bad_nb'] + 1) . ' WHERE id = ' . sql($req['id']) . ' ';
            $res = $co->execute($sql);
        }
    } else if (!empty($req['id'])) {
        $co->execute('DELETE FROM s_badlinks WHERE id = ' . sql($req['id']));
    }
    /*
      if ($err && $first) {
      sleep(5);
      $code = checkUrl($row, false);
      return $code;
      } */
    global $CURLOG;
    /*
      if ($CURLOG || $err) {
      $CURLOG = ($err) . "\n" . ($CURLOG) . "\n" . ($res);
      }
     */
    $CURLOG = '';
}

echo 'PARSING : ' . $argv[1] . "\n";

$row = array('url' => $argv[1], 'table' => $argv[2], 'id' => $argv[3], 'champ' => $argv[4]);
checkUrl($row);
