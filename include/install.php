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

class genInstall
{

    /**
     * Simpleform
     *
     * @var Simpleform
     */
    var $f1;

    /**
     * Simpleform
     *
     * @var Simpleform
     */
    var $f2;

    function __construct()
    {

        define('IN_INSTALL', true);

        $this->setForms();

        $this->doForm = 1;

        session_start();

        echo '<link rel="stylesheet" type="text/css" href="css/install.css" />';

        if ($_REQUEST['sqlSource']) {

            global $co;
            echo path_concat($GLOBALS['gb_obj']->include_path, '', $_REQUEST['sqlSource']);
            $quers = importSqlFile(path_concat($GLOBALS['gb_obj']->include_path, '', $_REQUEST['sqlSource']));

            $res = true;
            foreach ($quers as $sql) {
                if (!DoSql($sql)) {
                    $res = false;
                    echo("<p>Query: " . trim(nl2br(htmlentities($sql))) . "</p>\n");
                    echo("<p>MySQL: " . $co->errorMsg() . "</p>\n");
                    debug($sql);
                }
            }


            echo 'SOURCE';
        }

        if ($this->f1->isSubmited() && $this->f1->isValid()) {

            $this->createConfigFile();

            $GLOBALS['gb_obj']->includeConfig();
            require_once(ADODB_DIR . 'adodb.inc.php');

            global $co;
            echo '<div class="start">';
            $co = ADONewConnection($_POST['bdd_type']);
            echo '</div>';
            if (!$co) {
                $this->error('Database type is not supported');
            } else {
                if (!$_POST['bdd_creer']) {
                    echo '<div class="start">';
                    $connec = $co->Connect($_POST['bdd_host'], $_POST['bdd_user'], $_POST['bdd_pwd'], $_POST['bdd_bdd']);
                    $co->Execute('SET collation-connection = utf8');
                    $co->Execute('SET character-set-client = utf8');
                    $co->Execute('SET character-set-connection = utf8');
                    $co->Execute('SET character-set-results = utf8');
                    $co->Execute('SET NAMES  utf8');

                    echo '</div>';
                    if (!$connec) {
                        $this->error('Error connecting to MySQL Server');
                    } else {
                        $this->configServer();
                    }
                } else {
                    echo '<div class="start">';
                    $connec = $co->Connect($_POST['bdd_host'], $_POST['bdd_user'], $_POST['bdd_pwd']);
                    echo '</div>';
                    if (!$connec) {
                        $this->error('Error connecting to MySQL Server');
                    } else {
                        $res = DoSql('DROP DATABASE `' . $_POST['bdd_bdd'] . '`');
                        $res = DoSql('CREATE DATABASE ' . $_POST['bdd_bdd']);
                        if ($res) {
                            $co->disconnect();
                            $connec = $co->Connect($_POST['bdd_host'], $_POST['bdd_user'], $_POST['bdd_pwd'], $_POST['bdd_bdd']);
                            $co->Execute('SET collation-connection = utf8');
                            $co->Execute('SET character-set-client = utf8');
                            $co->Execute('SET character-set-connection = utf8');
                            $co->Execute('SET character-set-results = utf8');
                            $co->Execute('SET NAMES  utf8');
                            $this->configServer();
                        } else {
                            $this->error('Can\'t create Database');
                        }
                    }
                }
            }
        }


        if ($this->f2->isSubmited() && $this->f2->isValid()) {


            if (!defined('LG_TEMP') && $_SESSION['LG_TEMP']) {
                define('LG_TEMP', $_SESSION['LG_TEMP']);
            }

            DoSql('TRUNCATE s_admin');
            DoSql('INSERT INTO s_admin (admin_nom,admin_login,admin_pwd) VALUES (' . sql($_POST['admin_login']) . ',' . sql($_POST['admin_login']) . ',' . sql(password_hash($_POST['admin_pwd'], PASSWORD_BCRYPT)) . ')');

            $adminId = InsertId();

            DoSql('REPLACE INTO s_trad (trad_id,trad_fr) VALUES ("base_title",' . sql($_POST['site_nom']) . ')');
            DoSql('REPLACE INTO s_admin_trad (admin_trad_id,admin_trad_fr) VALUES ("base_title",' . sql($_POST['site_nom']) . ')');

            DoSql("UPDATE `s_rubrique` SET
							 rubrique_url_" . LG_TEMP . " = " . sql(';' . $_SERVER['HTTP_HOST'] . ';') . ", 
							 rubrique_titre_" . LG_TEMP . " = " . sql($_POST['site_nom']) . "
							 WHERE ocms_version = 1");


            global $genMessages;

            $genMessages->gen();

            $this->doForm = 3;
        } else {
            try {
                @mkdir('../c', 0755);
                @mkdir('../admin/c', 0755);
                @mkdir('../include/cache', 0755);
                @mkdir('../include/cache_agr', 0755);
                @mkdir('../thumb/cache', 0755);
                @mkdir('../fichier', 0755);
            } catch (Exception $e) {

            }
        }
    }

    public function checkConfig()
    {

        echo '<table>';
        $this->errorVersion('PHP', 'PHP 5 ou sup&eacute;rieur est n&eacute;cessaire', version_compare(phpversion(), '5'));

        $this->errorVersion('GD', 'GD 2 ou sup&eacute;rieur est n&eacute;cessaire', version_compare(phpversion('gd'), '2'));

        $this->infoVersion('JPEG', 'Le support JPEG n\'est pas activ&eacute; cela emp&eacute;chera le redimensionnement automatique de ces images', function_exists('imagecreatefromjpeg'));

        $this->infoVersion('PNG', 'Le support PNG n\'est pas activ&eacute; cela emp&eacute;chera le redimensionnement automatique de ces images', function_exists('imagecreatefrompng'));

        $this->infoVersion('GIF', 'Le support GIF n\'est pas activ&eacute; cela emp&eacute;chera le redimensionnement automatique de ces images et la cr&eacute;ation automatique de textes en image', function_exists('imagecreatefromgif'));

        $this->infoVersion('PS', 'Le support POSTSCRIPT n\'est pas activ&eacute; cela emp&eacute;chera la cr&eacute;ation automatique de textes en image', function_exists('imagepsloadfont'));

        $this->infoVersion('TRUETYPE', 'Le support TRUETYPE n\'est pas activ&eacute; cela emp&eacute;chera la cr&eacute;ation automatique de textes en image', function_exists('imagettftext'));

        $this->errorVersion('MYSQL', 'La librairie MySQL est n&eacute;cessaire pour la Base de donnée', (function_exists('mysql_query') || function_exists('mysqli_query')));

        $this->errorVersion('MBSTRING', 'La librairie MBSTRING est n&eacute;cessaire pour la gestion de l\'UTF-8', version_compare(phpversion('mbstring'), '1'));

        $this->errorVersion('MCRYPT', 'La librairie MCRYPT 2.4+ est n&eacute;cessaire pour la gestion de l\'UTF-8', version_compare(phpversion('mcrypt'), '2.4'));

        $this->errorVersion('MYSQL', 'Ni MYSQL ni MYSQLi ne sont install&eacute;s', max(version_compare(phpversion('mysql'), '1'), version_compare(phpversion('mysqli'), '1')));

        $this->errorVersion('SESSION', 'Le support des sessions n\'est pas activ&eacute;', function_exists('session_start'));

        $this->infoVersion('short_open_tag', 'Les short_open_tagne sont pas activ&eacute;s', ini_get('short_open_tag'));

        $this->errorVersion('CONFIGW', 'Impossible d\'ecrire dans le dossier config', is_writable($GLOBALS['gb_obj']->include_path . '/config'));

        $this->errorVersion('CACHE', 'Impossible d\'ecrire dans le dossier cache', is_writable($GLOBALS['gb_obj']->include_path . '/cache'));

        $this->errorVersion('FICHIERS', 'Impossible d\'ecrire dans le dossier d\'upload "fichiers"', is_writable($GLOBALS['gb_obj']->include_path . '/../fichier'));

        echo '</table>';

        if ($this->errorsConf) {
            die();
        }
    }

    function errorVersion($obj, $txt, $vrai)
    {

        if (!$vrai) {
            $this->errorsConf = true;
            echo '<tr style="background:#cc0000;color:white;"><td>' . $obj . '</td><td>' . $txt . '</td></tr>';
        } else {
            echo '<tr style="background:white;color:green;"><td>' . $obj . '</td><td>OK</td></tr>';
        }
    }

    function infoVersion($obj, $txt, $vrai)
    {
        if (!$vrai) {
            echo '<tr style="background:yellow;color:black;"><td>' . $obj . '</td><td>' . $txt . '</td></tr>';
        } else {
            echo '<tr style="background:white;color:green;"><td>' . $obj . '</td><td>OK</td></tr>';
        }
    }

    function createConfigFile()
    {

        $contenu = file_get_contents(path_concat($GLOBALS['gb_obj']->include_path, 'config', 'config.server.php.base'));
        if (!$contenu) {
            $this->error('Can\'t find configuration file : config.server.php.base');
            return;
        }
        $_POST['CRYPTO_KEY'] = md5(time() . '.' . microtime() . '.' . rand(0, 10000000)) . rand(0, 10000000);
        $_POST['current_ip'] = $_SERVER['SERVER_ADDR'];
        $_POST['UNIQUE_SITE'] = uniqid();
        $vars = array('bdd_bdd', 'bdd_user', 'bdd_pwd', 'bdd_type', 'bdd_host', 'CRYPTO_KEY', 'ADMIN_URL', 'WEB_URL', 'session_cookie_server', 'current_ip', 'UNIQUE_SITE');
        foreach ($vars as $var) {
            $contenu = str_replace('**' . $var . '**', $_POST[ $var ], $contenu);
        }


        $locale = '<?php

    global $sqlTime, $baseWebPath, $_Gconfig,$_bdd_host,$_bdd_type,$_bdd_pwd,$_bdd_bdd,$_bdd_user;

    $_bdd_user = \'' . $_POST['bdd_user'] . '\';
    $_bdd_pwd = \'' . $_POST['bdd_pwd'] . '\';
    $_bdd_bdd = \'' . $_POST['bdd_bdd'] . '\';
    $_bdd_host = \'' . $_POST['bdd_host'] . '\';
    $_bdd_type = \'' . $_POST['bdd_type'] . '\';

    $_Gconfig[\'debugIps\'] =array(\'' . $_SERVER['REMOTE_ADDR'] . '\');

        ';

        $contenu = str_replace('**LG_DEF**', $_POST['LG_DEF'], $contenu);

        define('LG_DEF', $_POST['LG_DEF']);
        define('LG_TEMP', $_POST['LG_DEF']);


        $_SESSION['LG_TEMP'] = LG_TEMP;


        $res1 = file_put_contents(path_concat($GLOBALS['gb_obj']->include_path, 'config', 'config.locale.php'), $locale);
        if (!$res1) {
            $this->error('Can\'t write to configuration file config.locale');
            return;
        }
        $res2 = file_put_contents(path_concat($GLOBALS['gb_obj']->include_path, 'config', 'config.server.php'), $contenu);
        if (!$res2) {
            $this->error('Can\'t write to configuration file config.server');
            return;
        }
    }

    function configServer()
    {


        if ($_POST['bdd_import']) {
            global $co;
            //$res =  importSqlFile(path_concat($GLOBALS['gb_obj']->include_path,'config','INSTALL.SQL'));
            $quers = importSqlFile(path_concat($GLOBALS['gb_obj']->include_path, 'config', 'INSTALL.SQL'));
            $res = true;
            //var_dump($quers);
            foreach ($quers as $sql) {
                if (!DoSql($sql)) {
                    $res = false;

                    echo("<p>Query: " . trim(nl2br(htmlentities($sql))) . "</p>\n");
                    echo("<p>MySQL: " . $co->errorMsg() . "</p>\n");
                    echo($sql);
                }
            }


            if ($this->errmsg) {
                $this->error($this->errmsg);
            }
            if (!$res) {
                $this->error('Error during Import');
                return;
            }
        }
        $this->info('SQL configuration OK');

        DoSql('REPLACE s_param SET param_valeur = ' . sql($_POST['mail_type']) . ' WHERE param_id = "mail_type"');
        DoSql('REPLACE s_param SET param_valeur = ' . sql($_POST['mail_host']) . ' WHERE param_id = "mail_host"');
        DoSql('REPLACE s_trad SET trad_' . LG_DEF . ' = ' . sql($_POST['mail_from']) . ' WHERE trad_id = "mail_from"');
        DoSql('REPLACE s_trad SET trad_' . LG_DEF . ' = ' . sql($_POST['mail_from_name']) . ' WHERE trad_id = "mail_from_name"');

        $this->doForm = 2;
    }

    function error($txt)
    {

        print('<div class="error" style="">' . $txt . '</div>');
    }

    function info($txt)
    {

        print('<div class="info" style="">' . $txt . '</div>');
    }

    function setForms()
    {

        $this->f1 = new simpleForm('', 'post', 'page1');

        $this->f1->add('html', '<p><h2>Installation de l\'OCMS</h2> Veuillez saisir ci-dessous les informations sur la base de donn&eacute;e</p><p>&nbsp;</p>', '');

        $this->f1->add('fieldset', 'Infos BDD');
        $this->f1->add('text', 'localhost', 'Serveur', 'bdd_host');
        $this->f1->add('text', 'root', 'Nom d\'utilisateur', 'bdd_user');
        $this->f1->add('text', '', 'Mot de passe', 'bdd_pwd');
        $this->f1->add('text', 'ocms', 'Base de donn&eacute;e', 'bdd_bdd');

        $this->f1->add('select', array('1' => 'Oui', '0' => 'Non'), 'Cr&eacute;er la base ?', 'bdd_creer', '', false, array(0));
        $this->f1->add('select', array('1' => 'Oui', '0' => 'Non'), 'Importer le dump SQL ?', 'bdd_import', '', false, array(1));

        $this->f1->add('select', array('mysql' => 'mysql', 'mysqli' => 'mysqli'), 'Type de connexion', 'bdd_type', '', false, array('mysql'));

        $this->f1->add('text', 'fr', 'Langue par d&eacute;faut', 'LG_DEF');

        //$this->f1->add('html','<p>&nbsp;</p>','');

        $this->f1->add('endfieldset', '');

        $this->f1->add('fieldset', 'Infos Mail');
        //$this->f1->add('text',mkPasswdLen(256),'Clef de cryptage','CRYPTO_KEY');
        /* $this->f1->add('text','http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']).'/','URL de l\'admin','ADMIN_URL');
          $this->f1->add('text','http://'.$_SERVER['HTTP_HOST'].str_replace('/admin','',dirname($_SERVER['SCRIPT_NAME'])).'/','URL du site','WEB_URL');
          $this->f1->add('text',$_SERVER['HTTP_HOST'],'Serveur pour les cookies','session_cookie_server');
         */

        $this->f1->add('select', array('mail' => 'php : mail()', 'smtp' => 'Envoi smtp'), 'Envoi d\'email via', 'mail_type', '', true, array('mail'));

        $this->f1->add('text', 'localhost', 'Serveur SMTP', 'mail_host');
        $this->f1->add('text', 'root@' . $_SERVER['HTTP_HOST'], 'Adresse expéditrice', 'mail_from');
        $this->f1->add('text', '' . $_SERVER['HTTP_HOST'], 'Nom expéditeur', 'mail_from_name');


        //$this->f1->add('html','<p>&nbsp;</p>','');

        $this->f1->add('endfieldset', '');


        $this->f1->add('submit', ' > Suivant', '', 'next');


        $this->f2 = new simpleForm('', 'post', 'page2');

        $this->f2->add('html', '<p><h2>Installation de l\'OCMS</h2> Veuillez saisir ci-dessous le nom d\'utilisateur et le mot de passe du premier administrateur</p><p>&nbsp;</p>', '');

        $this->f2->add('fieldset', 'Infos administrateur');
        $this->f2->add('text', '', 'Nom d\'utilisateur', 'admin_login');
        $this->f2->add('text', '', 'Mot de passe', 'admin_pwd');
        //$this->f2->add('html','<p>&nbsp;</p>','');

        $this->f2->add('fieldset', 'Infos Site');
        $this->f2->add('text', '', 'Nom du site', 'site_nom');

        $this->f2->add('endfieldset');

        $this->f2->add('submit', ' > Suivant', '', 'next');
    }

    function gen()
    {


        echo '<div id="install">';


        if ($this->doForm == 1) {
            $this->checkConfig();
            echo $this->f1->gen();
        } else if ($this->doForm == 2)
            echo $this->f2->gen();
        else if ($this->doForm == 3)
            $this->info('Configuration OK <a href="?" style="text-decoration:underline">aller dans l\'admin</a>');


        echo '</div>';
    }

}

