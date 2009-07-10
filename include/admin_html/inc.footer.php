
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