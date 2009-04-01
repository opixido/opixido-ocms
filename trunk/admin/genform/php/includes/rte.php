<?php
function LoadRTE($sFieldName, $sHTMLContent, $iWidth, $iHeight, $bButtons){
	//replace single quotes, so content will not interfere with js code
	$sHTML = str_replace("'", "&#39;", $sHTMLContent);
	// php resolves true to 1 and false to 0, hence we have to be javascript friendly... even noticed that the first letter had to be capitalized!
	if($bButtons){
		$jsTrue_bButtons = "True";
	} else {
		$jsTrue_bButtons = "False";
	}
	print "<!-- Start Rich Text Box //-->\n";
	print "	<iframe id=\"testFrame\" style=\"position: absolute; visibility: hidden; width: 0px; height: 0px;\"></iframe>\n";
	print "		<script language=\"JavaScript\" type=\"text/javascript\">\n";
	print "			<!--\n";
	print "				//set variables needed for richtext.js\n";
	print "				var fieldName = '$sFieldName';\n";
	print "				var htmlContent = '$sHTML';\n";
	print "				var rteWidth = '$iWidth';\n";
	print "				var rteHeight = '$iHeight';\n";
	print "				var showButtons = '$jsTrue_bButtons';\n";
	print "			//-->\n";
	print "		</script>\n";
	print "		<script language=\"JavaScript\" type=\"text/javascript\" src=\"includes/browserdetect.js\"></script>\n";
	print "		<script language=\"JavaScript\" type=\"text/javascript\" src=\"includes/richtext.js\"></script>\n";
	print "		<script language=\"JavaScript\" type=\"text/javascript\">\n";
	print "			<!--\n";
	print "				Start();\n";
	print "			//-->\n";
	print "		</script>\n";
	print "		<noscript><p><b>Sorry, javascript must be enabled to use this form.</b></p></noscript>\n";
	print "<!-- End Rich Text Box //-->\n";
}
?>