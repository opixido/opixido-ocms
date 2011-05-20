<?php

$sql = 'SELECT * FROM s_para_type WHERE para_type_gabarit = "genParaContact"';
$rowContact = GetSingle($sql);

global $restrictedMode;
$restrictedMode = true;
$form->gen('fk_contact_id');
$restrictedMode = false;

?>

<script type="text/javascript">


function checkContactPara() {

    if( $('#genform_fk_para_type_id').val() == <?=akev($rowContact,'para_type_id')?> ) {
        $('#genform_div_fk_contact_id').slideDown();
       
    }
    else {

        $('#genform_div_fk_contact_id').slideUp();

    }
 }
    $('#genform_fk_para_type_id').change(checkContactPara);
    checkContactPara();
</script>