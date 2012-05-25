
<div style="clear: both;">&nbsp;</div>

</div>
<div style="clear: both;">&nbsp;</div>
&nbsp;<br/>

</div>

<div style="clear: both;">&nbsp;</div>


<script type="text/javascript" >

    $(document).ready(function() {
        $('.resizable').each(function() {FitToContent($(this).attr('id'));});
        $('input, a, div.rtePreview').tooltip({placement:'right'});//tipsy({html: true ,gravity:  $.fn.tipsy.autoWE});
        $('.help i').tooltip({placement:'left'});//tipsy({html: true ,gravity:  $.fn.tipsy.autoWE});
    });

    $("table.sortable .order").remove();

    $("table.sortable").each( function() {

        if($(this).find("tbody tr").length > 1) {

            if(!$(this).find('td.dragHandle').length) {
                $(this).find("tbody tr").prepend('<td class="dragHandle" title=<?= alt(t('relinv_move')); ?>></td>');
                //$(this).find("thead th:first").after("<th width='20'></th>");
            }
            $(this).find('td.dragHandle').tooltip();//tipsy({html: true ,gravity:  'e'});


            $(this).tableDnD({
                onDrop: function(table, row) {
                    var trs = $(table).find('tbody tr');
                    var arr = new Array();
                    for(p = 0;p<trs.length;p++) {
                        arr.push(($(trs[p]).attr('rel')));
                    }
                    var t = $(table).attr('rel');
                    t = t.split('__');
                    ajaxAction("reorderRelinv", t[0],"<?= akev($_REQUEST, 'curId') ?>",{relinv:t[1],order:arr});	        },
                dragHandle: "dragHandle",
                onDragClass: "myDragClass"
            });
        }


    });

    /**
     * Pour la limitation de certains serveurs à un nombre de champ d'upload restreint
     */
    $("#genform_formulaire").submit(function() {
        $("#genform_formulaire input[type=file]").each(function() {
            if($(this).val() == "") {
                $(this).remove();
            }
        });
    });

    $( "div.radio" ).buttonset();


<?php if (!empty($GLOBALS['rteElements'])) { ?>
        if(tinyMCE_GZ) {
            tinyMCE_GZ.init({
                theme : "advanced",
                skin : "default",
                language : "en",
                plugins : "safari,paste,fullscreen,advimage,xhtmlxtras,contextmenu"
            });
        }
<? } ?>


</script>
<script type="text/javascript">
<?php
global $_Gconfig;
if ($GLOBALS['rteElements']) {
    ?>
            function setupTinymce(elementsId) {
                tinyMCE.init({
                    mode : "exact",
                    elements : elementsId,
                    theme : "advanced",
                    skin : "cirkuit",
                    language : "en",
                    plugins : "<?= implode(',', $_Gconfig['tinyMce']['plugins']) ?>",
                    entity_encoding : "raw",
                    content_css : "<?= BU ?>/css/baseadmin.css",
                    theme_advanced_styles : "<?= implode(';', $_Gconfig['tinyMce']['styles']) ?>",
                    theme_advanced_buttons1 : "<?= implode(',', $_Gconfig['tinyMce']['buttons1']) ?>",
                    theme_advanced_buttons2 : "<?= implode(',', $_Gconfig['tinyMce']['buttons2']) ?>",
                    theme_advanced_buttons3 : "<?= implode(',', $_Gconfig['tinyMce']['buttons3']) ?>",
                    theme_advanced_toolbar_location : "top",
                    theme_advanced_toolbar_align : "left",
                    theme_advanced_statusbar_location : "",
                    plugi2n_insertdate_dateFormat : "%d/%m/%Y",
                    plugi2n_insertdate_dateFormat : "%d/%m/%Y",
                    relative_urls : false ,
                    auto_reset_designmode:true,
                    file_browser_callback : "fileBrowserCallBack",
                    theme_advanced_resize_horizontal : false,
                    paste_auto_cleanup_on_paste : true,
                    paste_text_use_dialog : true,
                    paste_convert_headers_to_strong : true,
                    paste_strip_class_attributes : "all",
                    paste_remove_spans : true,
                    paste_remove_styles : true,
                    convert_fonts_to_spans : true,
                    verify_html : false ,
                    forced_root_block : 'p',
                    remove_linebreaks : false

                });
            }
    <?
}
?>

</script>

</body>
</html>
