<?
include('inc.connect.php');
include('../inc.genform.php');
session_write_close();
/*include('../inc.config.php');
include('../inc.config.php');
*/
?>
<html>
<head>
<link rel="StyleSheet" href="css/style.css" />
<link rel="StyleSheet" href="genform/css/genform.css" />
<body>
<div style="background-color:#eee;text-align:center;">
<?
if($_REQUEST['curId']) {
    $editMode = 1;
    
    $form = new GenForm($_REQUEST['curTable'], "", $_REQUEST['curId'], "");
    //$form->genHeader();
    
    $ch = explode(";",$_REQUEST['champs']);
    foreach($ch as $v) {
        $form->gen($v);
    }
    /*while(list($k,$v) = each($tabForms[$_REQUEST['curTable']]['pages'])) {
            $form->genPages();
    }*/
    
    //$form->genFooter();
}

?>
</div>
</body>
</html>
