
<div style="clear: both;">&nbsp;</div>

</div>
<div style="clear: both;">&nbsp;</div>
&nbsp;<br/>

</div>

<div style="clear: both;">&nbsp;</div>


<script type="text/javascript" >

    $(document).ready(function() {
        $('.resizable').each(function() {
            FitToContent($(this).attr('id'));
        });
        $('input, a, div.rtePreview').tooltip({placement: 'right'}); //tipsy({html: true ,gravity:  $.fn.tipsy.autoWE});
        $('.help i').tooltip({placement: 'left'}); //tipsy({html: true ,gravity:  $.fn.tipsy.autoWE});
    });
    $("table.sortable .order").remove();
    $("table.sortable").each(function() {

        if ($(this).find("tbody tr").length > 1) {

            if (!$(this).find('td.dragHandle').length) {
                $(this).find("tbody tr").prepend('<td class="dragHandle" title=<?= alt(t('relinv_move')); ?>></td>');
                //$(this).find("thead th:first").after("<th width='20'></th>");
            }
            $(this).find('td.dragHandle').tooltip(); //tipsy({html: true ,gravity:  'e'});


            $(this).tableDnD({
                onDrop: function(table, row) {
                    var trs = $(table).find('tbody tr');
                    var arr = new Array();
                    for (p = 0; p < trs.length; p++) {
                        arr.push(($(trs[p]).attr('rel')));
                    }
                    var t = $(table).attr('rel');
                    t = t.split('__');
                    ajaxAction("reorderRelinv", t[0], "<?= akev($_REQUEST, 'curId') ?>", {relinv: t[1], order: arr});
                },
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
            if ($(this).val() == "") {
                $(this).remove();
            }
        });
    });
    $("div.radio").buttonset();
<?php if (!empty($GLOBALS['rteElements'])) { ?>
        if (tinyMCE_GZ) {
        tinyMCE_GZ.init({
        theme : "advanced",
                skin : "default",
                language : "en",
                plugins : "safari,paste,fullscreen,advimage,xhtmlxtras,contextmenu"
        });
        }
        <?php } ?></script>
    <script type="text/javascript">
    <?php
    global $_Gconfig;
    if ($GLOBALS['rteElements']) {
        ?>
            function setupTinymce(elementsId) {
                tinyMCE.init({
                elements : elementsId
        <?php
        foreach ($_Gconfig['tinyMce']['conf'] as $k => $v) {
            $v = $v == 'false' || $v == 'true' ? $v : alt($v);
            echo ',' . $k . ' : ' . $v . "\n";
        }
        ?>


                });
            }
        <?php
    }
    ?>

</script>

</body>
</html>