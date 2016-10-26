<?php

global $_Gconfig,$admin_trads;
$_Gconfig['tableActions']['p_internaute']= array('ocms_exportTable');
$admin_trads['src_ocms_exportTable'][LG] = 'mimetypes/x-office-spreadsheet';

function ocms_exportTable() {
    ob_clean();
    ob_clean();
    ob_clean();
    $sql = 'SELECT * FROM '.$_REQUEST['curTable'].' ';
    $res =DoSql($sql);
   
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename=".$_REQUEST['curTable'].'-'.nicename(date('r')).'.csv');
    header("Content-Transfer-Encoding: binary");
    
    $df = fopen("php://output", 'w');
    $tab = getTabField($_REQUEST['curTable']);
    $entete = array();
    foreach($tab as $k=>$champ) {
        $entete[] = t($champ->name);
    }
    fputcsv($df,$entete);

    foreach($res as $row) {
        $vals = array();
        $f = new GenForm($_REQUEST['curTable'], 'post', 0, $row);
        $f->editMode = true;
        $f->onlyData = true;
        foreach($tab as $k=>$champ) {
            $vals[] = str_replace('&nbsp;','',strip_tags($f->gen($champ->name)));
        }
        fputcsv($df,$vals);
    }
    fclose($df);
    die();
}