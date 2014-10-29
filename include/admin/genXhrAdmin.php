<?php

/* * *********************
 *
 *   Popup d'administration via le front office
 *
 * ******************** */

class genXhrAdmin {

    /**
     *
     * @var simpleAdmin
     */
    public $sa;
    public $real_rub_id = false;
    public $real_fk_rub = false;
    public $insideRealRubId = false;

    function __construct($table, $id) {

        $this->table = $table;
        $this->id = $id;
        $this->sa = new smallAdmin($this);
        $this->gs = $GLOBALS['gs_obj'];




        if (!$this->gs->isLogged()) {
            die();
        }
        if (empty($_REQUEST['field'])) {
            $_REQUEST['field'] = '';
        }

        $this->field = strstr($_REQUEST['field'], "_-_") ? explode("_-_", $_REQUEST['field']) : $_REQUEST['field'];
        if (!$this->field) {
            $this->field = akev($_SESSION, 'lastUsedField');
        } else {
            $_SESSION['lastUsedField'] = $this->field;
        }


        $this->LoadPlugins();
    }

    /**
     * LoadPlugins
     *
     * @return unknown
     */
    function LoadPlugins() {

        $plugs = GetPlugins();

        foreach ($plugs as $v) {

            $GLOBALS['gb_obj']->includeFile('admin.php', PLUGINS_FOLDER . '' . $v . '/');
            $adminClassName = $v . 'Admin';
            if (class_exists($adminClassName)) {

                $this->plugins[$v] = new $adminClassName($this);
            }
        }
    }

    /*
      Dispatcher des actions
     */

    function gen() {


        switch ($_REQUEST['xhr']) {

            case 'tablerel':
                $this->searchTableRel();

                break;
            case 'searchRelation':
                $this->searchRelation();

                break;
            case 'arbo':

                $this->getArboRubs();

                break;

            case 'links':
                $this->getLinks();

                break;

            case 'reallink':
                $this->getRealLink();
                break;

            case 'editTrad':
                $this->editTrad();
                break;

            case 'gfa':
                $this->gfa();
                die();
                break;

            case 'ajaxRelinv':
                $this->ajaxRelinv();
                break;
            case 'ajaxForm':
                $this->ajaxForm();
                break;

            case 'ajaxAction':
                $this->ajaxAction();
                break;

            case 'del404':
                del404();

            case 'reorderRelinv';
                $this->reorderRelinv();

            case 'autocompletesearch':
                $this->autocompletesearch();
                break;

            case 'insertIdForNewForm':
                $this->insertIdForNewForm();
                break;

            case 'tablerelAsTags':
                $this->tablerelAsTags();
                break;

            case 'upload':
                $this->upload();
                break;

            case 'uploadDiaporama':
                $this->uploadDiaporama();
                break;
            case 'uploadRP':
                $this->uploadRP();
                break;
            case 'reloadChamp':
                $this->reloadChamp();
                break;

            case 'loadFileTag':
                $this->loadFileTag();

            case 'deleteFile':
                $this->deleteFile();

            case 'genformReloadField';
                $this->reloadField();
        }
    }

    function reloadField() {
        $gf = new GenForm($_REQUEST['curTable'], 'post', $_REQUEST['curId']);
        if (isBaseLgField($_REQUEST['curField'], $_REQUEST['curTable'])) {
            $gf->genlg($_REQUEST['curField']);
        } else {
            $gf->gen($_REQUEST['curField']);
        }
    }

    function loadFileTag() {

        $gf = new genFile($_REQUEST['curTable'], $_REQUEST['champ'], $_REQUEST['curId']);
        echo $gf->genAdminTag();
        die();
    }

    function deleteFile() {
        global $gs_obj;
        if ($gs_obj->can('edit', $_REQUEST['curTable'], array(), $_REQUEST['curId'], $_REQUEST['curChamp'])) {
            $gf = new genFile($_REQUEST['curTable'], $_REQUEST['curChamp'], $_REQUEST['curId']);
            $gf->deleteFile(true);
            global $getRowFromId_cacheRow;
            $getRowFromId_cacheRow = array();
            $gf = new genFile($_REQUEST['curTable'], $_REQUEST['curChamp'], $_REQUEST['curId']);
            if (!empty($_REQUEST['small'])) {
                echo $gf->genSmallAdminTag();
            } else {
                echo $gf->genAdminTag();
            }
            die();
        }
    }

    function upload() {


        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        global $gs_obj;
        if (!$gs_obj->can('edit', $_REQUEST['curTable'], array(), $_REQUEST['curId'], $_REQUEST['champ'])) {
            die('access denied');
        }

        @set_time_limit(5 * 60);

        /**
         * Différents Chunks du fichier
         */
        $chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
        $chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;

        /**
         * Nom réel à l'upload
         */
        $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

        if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
            $contentType = $_SERVER["HTTP_CONTENT_TYPE"];

        if (isset($_SERVER["CONTENT_TYPE"]))
            $contentType = $_SERVER["CONTENT_TYPE"];

        $tempName = md5($_SESSION['gs_admin_id'] . '_' . $_REQUEST['curTable'] . '_' . $_REQUEST['curId'] . '_' . $_REQUEST['champ']);



        global $gb_obj;
        $targetDir = path_concat($gb_obj->include_path, GetParam('cache_path'));
        $tempFullPath = path_concat($targetDir, $tempName);


        // Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
        if (strpos($contentType, "multipart") !== false) {
            if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
                // Open temp file

                $out = fopen($tempFullPath, $chunk == 0 ? "wb" : "ab");
                if ($out) {
                    // Read binary input stream and append it to temp file
                    $in = fopen($_FILES['file']['tmp_name'], "rb");

                    if ($in) {
                        while ($buff = fread($in, 4096))
                            fwrite($out, $buff);
                    } else
                        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                    fclose($in);
                    fclose($out);
                    @unlink($_FILES['file']['tmp_name']);
                } else
                    die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            } else
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
        } else {
            // Open temp file
            $out = fopen($tempFullPath, $chunk == 0 ? "wb" : "ab");
            if ($out) {
                // Read binary input stream and append it to temp file
                $in = fopen("php://input", "rb");

                if ($in) {
                    while ($buff = fread($in, 4096))
                        fwrite($out, $buff);
                } else
                    die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

                fclose($in);
                fclose($out);
            } else
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }


        if ($chunks - 1 == $chunk || $chunks == 0) {


            $gf = new genFile($_REQUEST['curTable'], $_REQUEST['champ'], $_REQUEST['curId'], $fileName, false);
            $fileName = $gf->getSystemPath();

            $fileName = $gf->fileName;
            $targetDir = $gf->systemPath;


            /* chmod($targetDir.DIRECTORY_SEPARATOR.$fileName, 0777);
              chgrp($targetDir.DIRECTORY_SEPARATOR.$filename, 'www-data'); */
            $gf->uploadFile($tempFullPath, true);
            if (!empty($_REQUEST['type']) && $_REQUEST['type'] == 'small') {
                echo $gf->genSmallAdminTag();
            } else {
                echo $gf->genAdminTag();
            }
            unlink($tempFullPath);
            die();
        }
        // Return JSON-RPC response
        die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
    }

    function uploadDiaporama() {

        global $gs_obj;
        if ($gs_obj->can('edit', 'c_programme', array(), $_REQUEST['curId'])) {
            DoSql('INSERT INTO c_diaporama (diaporama_id, fk_programme_id,diaporama_titre)
                    VALUES ("",' . sql($_REQUEST['curId']) . ',' . sql($_REQUEST['name']) . ') ');
            $_REQUEST['curId'] = $_GET['curId'] = $_POST['curId'] = InsertId();
            $_REQUEST['curTable'] = $_GET['curTable'] = $_POST['curTable'] = 'c_diaporama';
            $_REQUEST['champ'] = $_GET['champ'] = $_POST['champ'] = 'diaporama_img';

            $this->upload();
        }
    }

    function uploadRP() {


        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        @set_time_limit(5 * 60);

        /**
         * Différents Chunks du fichier
         */
        $chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
        $chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;

        /**
         * Nom réel à l'upload
         */
        $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

        if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
            $contentType = $_SERVER["HTTP_CONTENT_TYPE"];

        if (isset($_SERVER["CONTENT_TYPE"]))
            $contentType = $_SERVER["CONTENT_TYPE"];

        $tempName = md5($_SESSION['gs_admin_id'] . '_' . $_REQUEST['curTable'] . '_' . $_REQUEST['curId'] . '_' . $_REQUEST['champ']);



        global $gb_obj;
        $targetDir = path_concat($gb_obj->include_path, GetParam('cache_path'));
        $tempFullPath = path_concat($targetDir, $tempName);


        // Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
        if (strpos($contentType, "multipart") !== false) {
            if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
                // Open temp file

                $out = fopen($tempFullPath, $chunk == 0 ? "wb" : "ab");
                if ($out) {
                    // Read binary input stream and append it to temp file
                    $in = fopen($_FILES['file']['tmp_name'], "rb");

                    if ($in) {
                        while ($buff = fread($in, 4096))
                            fwrite($out, $buff);
                    } else
                        die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
                    fclose($in);
                    fclose($out);
                    @unlink($_FILES['file']['tmp_name']);
                } else
                    die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
            } else
                die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
        } else {
            // Open temp file
            $out = fopen($tempFullPath, $chunk == 0 ? "wb" : "ab");
            if ($out) {
                // Read binary input stream and append it to temp file
                $in = fopen("php://input", "rb");

                if ($in) {
                    while ($buff = fread($in, 4096))
                        fwrite($out, $buff);
                } else
                    die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

                fclose($in);
                fclose($out);
            } else
                die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
        }


        if ($chunks - 1 == $chunk || $chunks == 0) {


            $x = simplexml_load_file($tempFullPath);

            echo '<table class="genform_table">';
            foreach ($x->image as $image) {


                if (empty($image['name'])) {
                    continue;
                }
                echo '<tr><td>' . $image['name'] . '</td>';
                /**
                 * On cherche le timecode correspondant
                 */
                $tc = $x->xpath('//crossfade[@target=' . $image['handle'] . ']');
                if (empty($tc[0]['start'])) {
                    echo '<td>Timecode (crossfade) correspondant manquant</td></tr>';
                    continue;
                }
                $tc = $tc[0]['start'];

                /**
                 * On supprime les milisecondes
                 */
                $tc = explode('.', $tc);
                $tc = $tc[0];

                /**
                 * On récupère le tout en tableau
                 */
                $tcs = explode(':', $tc);
                $secs = $tcs[1] * 3600 + $tcs[2] * 60 + $tcs[3];

                //print_r($tc);
                $n = explode('/', $image['name']);
                $n = $n[count($n) - 1];
                $sql = 'SELECT * FROM c_diaporama WHERE fk_programme_id = ' . sql($_REQUEST['curId']) . ' AND diaporama_img LIKE "%' . $n . '"';
                $r = getSingle($sql);
                if (!$r) {
                    echo '<td>Aucun fichier image dans le diaporama ne correspond</td></tr>';
                    continue;
                }
                DoSql('UPDATE c_diaporama SET diaporama_repere_temporel = ' . sql($secs) . ' WHERE diaporama_id = ' . sql($r['diaporama_id']));
                echo '<td>' . $tc . '</td><td>' . $secs . 'sec</td><td><b>OK</b></td></tr>';
            }
            echo '</table>';

            unlink($tempFullPath);
            die();
        }
        // Return JSON-RPC response
        die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
    }

    function reloadChamp() {

        $gf = new GenForm($_REQUEST['curTable'], 'post', $_REQUEST['curId']);
        echo $gf->gen($_REQUEST['curChamp']);
        die();
    }

    function tablerelAsTags() {
        global $tablerel, $_Gconfig;


        $fields = $_Gconfig['tablerelAsTags'][$_REQUEST['tablerel']]['label'];
        $_GET['order'] = $_REQUEST['order'] = $fields[0];
        $_GET['to'] = $_REQUEST['to'] = 'asc';
        $s = new genSearchV2($_REQUEST['table']);
        $clause = '';
        if ($_Gconfig['specialListingWhere'][$_REQUEST['tablerel']]) {
            $clause .= $_Gconfig['specialListingWhere'][$_REQUEST['tablerel']]($_REQUEST['curId']);
        }
        $res = $s->doFullSearch($_REQUEST['term'], $clause);



        $re = array();
        $pk = getPrimaryKey($_REQUEST['table']);


        $fieldsD = akev($_Gconfig['tablerelAsTags'][$_REQUEST['tablerel']], 'desc');
        if (!is_array($fieldsD)) {
            $fieldsD = array();
        }
        $tr = $_REQUEST['tablerel'];

        foreach ($res as $row) {
            $td = $t = array();
            foreach ($fields as $v) {
                $t[] = $row[$v];
            }

            $label = $value = implode($t, " - ");

            $re[] = array('id' => $row[$pk], 'label' => $label, 'value' => $value);
        }

        echo json_encode($re);
        die();
    }

    function insertIdForNewForm() {

        $gr = new genRecord($_REQUEST['table'], 'new');
        $id = $gr->doRecord();

        echo $id;
        die();
    }

    function autocompletesearch() {

        $x = array('query' => $_REQUEST['query'], 'suggestions' => array(), 'data' => array());

        global $tabForms;
        if (!$tabForms[$_REQUEST['table']]) {
            die();
        }

        $sql = 'SELECT * FROM ' . $_REQUEST['table'] . '
					WHERE ' . $_REQUEST['champ'] . ' 
					LIKE ' . sql('%' . $_REQUEST['query'] . '%') . '';
        $res = GetAll($sql);



        $add = true;
        if (strpos(getTitleFromtable($_REQUEST['table']), $_REQUEST['champ'])) {
            $add = false;
        }

        $pk = getPrimaryKey($_REQUEST['table']);


        /**
         * Formatage pour JSON
         */
        foreach ($res as $row) {
            if (true) {
                $x['suggestions'][] = limitwords(strip_tags($row[$_REQUEST['champ']]));
            } else
            if ($add) {
                $x['suggestions'][] = limitwords(strip_tags($row[$_REQUEST['champ']] . ' - ' . GetTitleFromRow($_REQUEST['table'], $row, ' - ')), 50);
            } else {
                $x['suggestions'][] = limitwords(strip_tags(GetTitleFromRow($_REQUEST['table'], $row, ' - ')), 50);
            }
            $x['data'][] = $row[$pk];
        }

        /**
         * Retour
         */
        echo json_encode($x);
        die();
    }

    function ajaxAction() {

        $action = $_REQUEST['action'];
        $id = $_REQUEST['id'];
        $table = $_REQUEST['table'];
        $params = unserialize($_REQUEST['params']);



        if ($GLOBALS['gs_obj']->can($action, $_REQUEST['table'], array(), $_REQUEST['id'])) {

            if ($action == 'goup') {
                $row = getRowFromId($_REQUEST['table'], $_REQUEST['id']);
                $fkC = $row[$params['vfk2']] ? $params['vfk2'] : $params['vfk1'];
                $o = new GenOrder($_REQUEST['table'], $_REQUEST['id'], $row[$fkC], $fkC);
                $o->GetUp();
            } else if ($action == 'godown') {

                $row = getRowFromId($_REQUEST['table'], $_REQUEST['id']);
                $fkC = $row[$params['vfk2']] ? $params['vfk2'] : $params['vfk1'];
                echo 'Descend ' . $_REQUEST['table'] . ' - ' . $_REQUEST['id'] . ' - ' . $fkC;

                $o = new GenOrder($_REQUEST['table'], $_REQUEST['id'], $row[$fkC], $fkC);

                $o->GetDown();
            }
            if ($action == 'add') {

                /* print_r($_REQUEST);
                  print_r(unserialize($_REQUEST['params']));
                 */


                $xfk = $id ? $params['vfk2'] : $params['vfk1'];
                $id = $id ? $id : $params['id'];


                $sql = 'SELECT MAX(' . $params['order'] . ') AS MAXI FROM ' . $table . ' WHERE ' . $xfk . ' = ' . sql($id);
                $row = GetSingle($sql);

                $record[$xfk] = $id;
                $record[$params['order']] = $row['MAXI'] + 1;

                global $co;
                DoSqL($co->getInsertSql($table, $record));

                $ide = InsertId();
                $GLOBALS['gb_obj']->includeFile('genform.fullarbo.php', 'admin/genform_modules/');

                $row = getRowFromId($table, $ide);

                global $_Gconfig;

                $fa = new fullArbo($params['table'], $params['id'], $_Gconfig['fullArbo'][$params['table']][$params['field']], $params['field']);

                $fa->html = '';
                $fa->getLine($row, false);

                echo $fa->html;
            } else if ($action == 'del') {

                $gr = new genRecord($table, $id);
                $gr->DeleteRow($id);
            } else if ($action == 'reorderRelinv') {
                foreach ($params['order'] as $k => $v) {
                    $sql = ('UPDATE ' . $table . ' SET ' . $params['relinv'] . ' = ' . sql($k + 1) . ' WHERE ' . getPrimaryKey($table) . ' = ' . sql($v));
                    echo $sql;
                    Dosql($sql);
                }
                die();
            }
        } else {
            echo 'CANTDO';
        }
    }

    function ajaxForm() {

        if (!empty($_REQUEST['upload'])) {
            echo 'UPLOAD';
            print_r($_REQUEST);
            print_r($_FILES);
        } else
        if (ake($_REQUEST, 'save') && $_REQUEST['champ'] && $_REQUEST['id'] && $_REQUEST['table']) {

            if ($GLOBALS['gs_obj']->can('edit', $_REQUEST['table'], array(), $_REQUEST['id'], $_REQUEST['champ'])) {

                DoSql('UPDATE ' . $_REQUEST['table'] . '
	    						SET ' . $_REQUEST['champ'] . ' = ' . sql($_REQUEST['save']) . ' 
	    					WHERE ' . getPrimaryKey($_REQUEST['table']) . ' = ' . sql($_REQUEST['id']));

                echo Affected_Rows();
            }
        }
    }

    function ajaxRelinv() {


        if (!empty($_REQUEST['save']) && $_REQUEST['field'] && $_REQUEST['id'] && $_REQUEST['table']) {

            if ($GLOBALS['gs_obj']->can('edit', $_REQUEST['table'], array(), $_REQUEST['id'], $_REQUEST['field'])) {

                echo DoSql('UPDATE ' . $_REQUEST['table'] . ' SET ' . $_REQUEST['field'] . ' = ' . sql($_REQUEST['save']) . '
	    					WHERE ' . getPrimaryKey($_REQUEST['table']) . ' = ' . $_REQUEST['id']);
            }
        } else if (!empty($_REQUEST['fake'])) {

            if ($GLOBALS['gs_obj']->can('edit', $_REQUEST['table'], array(), $_REQUEST['id'], $_REQUEST['field'])) {

                global $_Gconfig, $orderFields;

                //$GLOBALS['gb_obj']->includeFile('genform.ajaxRelinv.php','admin/genform_modules');
                include($GLOBALS['gb_obj']->getIncludePath('genform.ajaxrelinv.php', 'admin/genform_modules'));

                $vals = $_Gconfig['ajaxRelinv'][$_REQUEST['table']][$_REQUEST['fake']];
                /* print_r($_Gconfig['ajaxRelinv']);
                  print_r($vals); */
                //die();
                $a = new ajaxRelinv($_REQUEST['table'], $_REQUEST['id'], $vals[0], $vals[1], $_REQUEST['fake']);

                $id = insertEmptyRecord($vals[0], false, array($vals[1] => $_REQUEST['id']));
                /* $sqlInsert = 'INSERT INTO ' . $vals[0] . ' (' . getPrimaryKey($vals[0]) . ' , ' . $vals[1] . ') VALUES ("",' . sql($_REQUEST['id']) . ')';
                  //echo $sqlInsert;
                  $res = DoSql($sqlInsert);
                  $id = InsertId(); */


                if (!$_REQUEST['id'] || $_REQUEST['id'] == 'new') {
                    $_SESSION['sqlWaitingForInsert'][] = 'UPDATE ' . $vals[0] . ' SET ' . $vals[1] . ' = [INSERTID] WHERE ' . getPrimaryKey($vals[0]) . ' = ' . sql($id);
                }
                if ($orderFields[$vals[0]] && $orderFields[$vals[0]][1] == $vals[1]) {
                    $clefEx = $orderFields[$vals[0]][1];
                    $champOrdre = $orderFields[$vals[0]][0];
                    $r = getSingle('SELECT MAX(' . $champOrdre . ') AS MAXX FROM ' . $vals[0] . ' WHERE ' . $clefEx . ' = ' . sql($_REQUEST['id']));
                    $maxx = $r['MAXX'] + 1;
                    //echo $maxx;
                    //echo ' : '.
                    DoSql('UPDATE ' . $vals[0] . ' SET ' . $champOrdre . ' = ' . $maxx . ' WHERE ' . getPrimaryKey($vals[0]) . ' = ' . sql($id));
                }

                $row = getRowFromId($vals[0], $id);

                echo $a->getLine($row, $vals[2]);
            }
        } else if (!empty($_REQUEST['delete'])) {
            if ($GLOBALS['gs_obj']->can('delete', $_REQUEST['table'], array(), $_REQUEST['delete'])) {
                $gr = new genRecord($_REQUEST['table'], $_REQUEST['delete']);
                echo $gr->DeleteRow($_REQUEST['delete']);
                //echo DoSql('DELETE FROM '.$_REQUEST['table'].' WHERE '.getPrimaryKey($_REQUEST['table']). ' = '.sql($_REQUEST['delete']));
            } else {
                echo 'CANTDO';
            }
        }

        die();
    }

    function gfa() {

        $champ = $_REQUEST['field'];
        echo '<input type="text" class="gfa_input" value="" />';
    }

    function editTrad() {

        $_REQUEST['nom'] = str_replace('ET_', '', $_REQUEST['nom']);
        $s = str_replace(str_replace(ADMIN_URL, "", ADMIN_PICTOS_FOLDER), '[ADMIN_PICTOS_FOLDER]', $_REQUEST['valeur']);
        DoSql('REPLACE INTO s_admin_trad (admin_trad_id,admin_trad_' . LG_DEF . ') VALUES ("' . $_REQUEST['nom'] . '",' . sql($s) . ')');

        print_r($_REQUEST);
    }

    function getRealLink() {
        $id = $_GET['id'];

        $site = new GenSite();
        $site->initLight();

        print path_concat(WEB_URL, $site->g_url->buildUrlFromId($id));
    }

    function searchTableRel() {

        global $tablerel, $_Gconfig;

        $tables = array_values($tablerel[$_REQUEST['champ']]);
        $rev = array_flip($tablerel[$_REQUEST['champ']]);
        $fk_table = $tables[0] == $_REQUEST['curTable'] ? $tables[1] : $tables[0];
        $fk_pk = $rev[$fk_table];

        if ($this->gd) {
            $sql = 'SELECT ' . $fk_pk . '
					FROM ' . mes($_REQUEST['champ']) . ' 
					WHERE ' . mes($rev[$_REQUEST['curTable']]) . ' = "' . mes($_REQUEST['curId']) . '"
					
					';
        }

        if ($_Gconfig['specialListingWhere'][$_REQUEST['champ']]) {
            $sql .= $_Gconfig['specialListingWhere'][$_REQUEST['champ']]($_REQUEST['curId']);
        }

        $res = GetAll($sql);

        $tab = array(0);
        foreach ($res as $row) {
            $tab[] = $row[$fk_pk];
        }

        $pk2 = getPrimaryKey($fk_table);

        $clause = "";
        if (count($tab)) {
            $clause = ' AND T.' . $pk2 . ' NOT IN ( ' . implode(',', $tab) . ' )';
        }


        $s = new genSearchV2($fk_table);
        $res = $s->doFullSearch($_REQUEST['q'], $clause);

        foreach ($res as $row) {
            print('<option value="' . $row[$pk2] . '">' . getTitleFromRow($fk_table, $row) . '</option>');
        }
        die();
    }

    function searchRelation() {

        $t = $_REQUEST['table'];
        $fk = str_replace('genform_', '', $_REQUEST['fk']);
        global $relations;

        $table = $relations[$t][$fk];

        $pk2 = getPrimaryKey($table);
        $s = new genSearchV2($table);
        $res = $s->doFullSearch($_REQUEST['q'], $clause, false);
        foreach ($res as $row) {
            print('<li><a class="sal" onclick="selectRelationValue(this)" rel="' . $row[$pk2] . '">' . getTitleFromRow($table, $row, ' > ', true) . '</a></li>');
        }
        die();
    }

    function getArboRubs() {

        genAdmin::handleOpenRubs();

        $this->id = akev($_REQUEST, 'curId');
        if ($this->id) {
            $this->row = getSingle('SELECT * FROM s_rubrique WHERE rubrique_id = ' . sql($this->id));
            $this->real_rub_id = $this->row['fk_rubrique_version_id'];
            $this->real_fk_rub = $this->row['fk_rubrique_id'];
        }
        $this->arboRubs = $this->sa->getRubs();
        //$_REQUEST['curId'] = $_REQUEST['showRub'] ?  $_REQUEST['showRub'] :  $_REQUEST['hideRub'];

        $this->sa->getArboActions();
        p('<div id="arbo">');
        $this->sa->recurserub('NULL', 0, "1");
        p('</div>');
    }

    function recurserub($a, $b, $c) {

        $this->sa->recurserub($a, $b, $c);
    }

    function getLinks() {
        $site = new GenSite();
        $site->initLight();
        $menus = $site->getMenus();

        $this->html = '<h1>' . t('choisir_rubrique_ci_dessous') . '</h1><ul>';
        foreach ($menus as $menu) {

            $arbo = $site->g_url->recursRub($menu['rubrique_id']);
            $this->html .= '<li>' . $menu['rubrique_titre_' . LG];
            $this->recursLinks($arbo);

            $this->html .= '</li>';
        }
        $this->html .= '</ul>';

        print $this->html;
    }

    private
            function recursLinks($array, $level = '1', $rootRub = '1') {
        if (!is_array($array)) {
            return;
        }
        foreach ($array as $page) {
            $page['url'] = '';
            $url = '@rubrique_id=' . $page['id'];
            if ($level == 1) {
                $this->html .= ( '<li class="top_div_' . $rootRub . '">');
                $this->html .= ( '<a onclick="update_links(\'' . $_GET['champ'] . '\',' . $page['id'] . ')" > ' . $page['titre'] . '</a>');
                if (count($page['sub']) && $level != 3) {
                    $this->html .= ( '<ul class="ul_' . $rootRub . '">');
                    $this->recursLinks($page['sub'], $level + 1, $rootRub);
                    $this->html .= ( '</ul>');
                }
                $this->html .= ( '</li>');
            } else {
                $this->html .= ( '<li class="level' . $level . '_' . $rootRub . '">');

                $this->html .= ( '<a onclick="update_links(\'' . $_GET['champ'] . '\',' . $page['id'] . ')"  >' . $page['titre'] . '</a>');
                if (!empty($page['sub']) && $level != 3) {
                    $this->html .= ( '<ul>');
                    $this->recursLinks($page['sub'], $level + 1, $rootRub);
                    $this->html .= ( '</ul>');
                }
                $this->html .= ( '</li>');
            }
            if ($level == 1)
                $rootRub++;
        }
    }

}

class object {
    
}
