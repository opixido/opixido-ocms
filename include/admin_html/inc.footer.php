
<div style="clear: both;">&nbsp;</div>

</div>
<div style="clear: both;">&nbsp;</div>
&nbsp;<br/>

</div>

<div style="clear: both;">&nbsp;</div>
<div id="jsevents" style="display:none;overflow:auto;height:200px;border:2px solid;background:white;"></div>

<script type="text/javascript">

$(document).ready(function() {
$('.resizable').TextAreaResizer();
});
<?php if($GLOBALS['rteElements']) { ?>
if(tinyMCE_GZ) {
tinyMCE_GZ.init({
				   
					theme : "advanced",
					skin : "default",
					language : "en",
					plugins : "safari,paste,fullscreen,advimage,xhtmlxtras,contextmenu"

		 
				});
}
<? } ?>

$("table.sortable .order").remove();

$("table.sortable").each( function() {

	if($(this).find("tbody tr").length > 1) {
		
		$(this).find("tbody tr").prepend("<td class='dragHandle'></td>");
		$(this).find("thead th:first").after("<th ></th>");
		
		$(this).tableDnD({
	        onDrop: function(table, row) {
	           var trs = $(table).find('tbody tr');
	           var arr = new Array();
	           for(p = 0;p<trs.length;p++) {
	           		arr.push(($(trs[p]).attr('rel')));
	           }
	           var t = $(table).attr('rel');
	           t = t.split('__');
	           ajaxAction("reorderRelinv", t[0],"<?=$_REQUEST['curId']?>",{relinv:t[1],order:arr});	        },
	        dragHandle: "dragHandle",
	        onDragClass: "myDragClass"		
   		 });
	}
	
	
});

$('#arbo_1').sortable();


</script>
<script type="text/javascript">
<?php if($GLOBALS['rteElements']) { ?>

tinyMCE.init({
				    mode : "exact",
					elements : "<?=substr($GLOBALS['rteElements'],0,-2)?>",
					theme : "advanced",
					skin : "default",
					language : "en",
					plugins : "safari,paste,fullscreen,advimage,xhtmlxtras,contextmenu,media",
					entity_encoding : "raw",
					content_css : "<?=BU?>/css/baseadmin.css",
					theme_advanced_styles : "Texte clair=light;Texte important=important;Texte très important=timportant",
					theme_advanced_buttons1 : "styleselect,bold,italic,underline,separator,removeformat,separator,hr,image,media,link,unlink,separator,pastetext,separator,bullist,bullnum,separator,code,cleanup,separator,sub,sup,separator,abbr,acronym,charmap,fullscreen",
					theme_advanced_buttons2 : "",
					theme_advanced_buttons3 : "",
					theme_advanced_toolbar_location : "top",
					theme_advanced_toolbar_align : "left",
					theme_advanced_statusbar_location : "",
				    plugi2n_insertdate_dateFormat : "%d/%m/%Y",
				    plugi2n_insertdate_dateFormat : "%d/%m/%Y",		   
				    relative_urls : false , 
					auto_reset_designmode:true,
					paste_use_dialog : true,	
					file_browser_callback : "fileBrowserCallBack",
					theme_advanced_resize_horizontal : false,	
					paste_auto_cleanup_on_paste : true,
					paste_use_dialog : true,
					paste_convert_headers_to_strong : true,
					paste_strip_class_attributes : "all",
					paste_remove_spans : true,
					paste_remove_styles : true,		
					convert_fonts_to_spans : true,
					verify_html : false ,
					forced_root_block : 'p',	
					remove_linebreaks : false
					

		 
				});

<? }


?>

</script>

<style type="text/css">
	td.dragHandle {
		width:20px;
		background:url(img/move_light.png) no-repeat center center #eee!important ;
		cursor:move;
	}
	tr.myDragClass  td {
		background-color:#ddd!important;
	}
	tr.myDragClass td.dragHandle {
		background:url(img/move.png) no-repeat center center #eee!important ;
	}
	tr.myDragClass  {
	
	}
</style>
</body>
</html>
<?php

/*
global $admin_trads;
print_r($admin_trads);

foreach($admin_trads as $k=>$v) {
	DoSql('REPLACE INTO s_admin_trad VALUES ("'.$k.'",'.sql($v['fr']).','.sql($v['en']).')');
}
*/

?>