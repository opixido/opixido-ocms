<?php

class genRteInline{

	var $toolbar;

	function __construct($champ,$valeur="",$toolbar = 'Default', $tabContent = ''){
		$this->toolbar = $toolbar;
		$this->tabContent = $tabContent;
		$this->champ = $champ;
		$this->valeur = $valeur;

		
	}

	function gen(){
		return $this->createRte($content);
	}

	function createRte(){
		global $formFooters,$champsRTE;
		
		$formFooters = '';
		$champsRTE .= $this->champ.', ';
		$html .= $this->instanceRte();
			
		$formFooters =  '
		    <script language="javascript" type="text/javascript" src="tinymce/tiny_mce.js"></script>
			<script language="javascript" type="text/javascript">
			';
		
		
	
//separator,insertdate,inserttime,print,help

		    $formFooters .=  '
		
		</script>
		
		<script language="javascript" type="text/javascript">
		//advimage
		tinyMCE.init({
		    mode : "exact",
			elements : "@@CHAMPS@@",
			theme : "advanced",
			plugins : "xhtmlxtras,accessilink,iespell,insertdatetime,searchreplace,print,contextmenu,paste,styleselect",
			entity_encoding : "raw",
			content_css : "'.BU.'/css/baseadmin.css",
			theme_advanced_buttons1 : "bold,italic,underline,separator,image,link,unlink,separator,pastetext,separator,search,replace,separator,bullist,bullnum,separator,undo,redo,separator,code,separator,sub,sup,separator,abbr,acronym,separator,charmap",
			theme_advanced_buttons2 : "",
			theme_advanced_buttons3 : "",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "",
		    plugi2n_insertdate_dateFormat : "%d/%m/%Y",
		    plugi2n_insertdate_timeFormat : "%H:%M:%S",
		    relative_urls : false , 
			auto_reset_designmode:true,
			paste_use_dialog : false,	
			file_browser_callback : "fileBrowserCallBack",
			theme_advanced_resize_horizontal : false,	
			paste_auto_cleanup_on_paste : false,
			paste_convert_headers_to_strong : true,
			paste_strip_class_attributes : "all",
			paste_remove_spans : true,
			paste_remove_styles : true,		
			convert_fonts_to_spans : true,
			verify_html : false 
		});
		
		
		function fileBrowserCallBack(field_name, url, type, win) {
		var connector = "../../filemanager/browser.html?Connector=connectors/php/connector.php";
		var enableAutoTypeSelection = true;
		
		var cType;
		tinymcpuk_field = field_name;
		tinymcpuk = win;
		
		switch (type) {
			case "image":
				cType = "images";
				break;
			case "flash":
				cType = "Flash";
				break;
			case "file":
				cType = "File";
				break;
		}
		
		if (enableAutoTypeSelection && cType) {
			connector += "&Type=" + cType;
		}
		
		window.open(connector, "tinymcpuk", "modal,width=1000,height=800");
		}
		
		';
		      
	 
		  	 
	$formFooters .=  '</script>
	
	';

		
		return $html.$formFooters;
		
		

	}

	function instanceRte(){

		$html .= ('<textarea  name="'.$this->champ.'" id="'.$this->champ.'" 
						style="height:300px;width:500px" > '.$this->valeur .' </textarea >');
		
		return $html;
	}

}




?>