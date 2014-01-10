<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
	<title>Cross-Browser Rich Text Editor</title>
	<style type="text/css">
	.btnImage {cursor: pointer; cursor: hand;}
	</style>
</head>
<body>
<h2>Cross-Browser Rich Text Editor</h2>
<p>Last Updated: <b>10/28/2003</b></p>
<p>The cross-browser rich-text editor implements the new <a href="http://www.mozilla.org/editor/midas-spec.html" target="_blank">Midas API</a> included with Mozilla 1.3.  There is <b>NO LICENSE</b>, so just take the code and use it for any purpose.  This code is 100% free.  Enjoy!</p>
<p><b>Requires:</b> IE5.5+/Mozilla 1.3+ for all rich-text features to function properly.  If browser does not support rich-text, it should display a standard textarea box.  <b>*** Note:</b> <a href="http://www.mozilla.org/products/firebird/" target="_blank">Mozilla Firebird</a> (0.6.1+) now supports contentEditable mode.</p>
<p><b>Source:</b> <a href="php_rte.zip">php_rte.zip</a></p>
<form name="RichTextEditor" action="" method="post" onsubmit="return submitForm();">
<!-- PHP code: -->
<?
//the following line demonstrates removing carriage returns in preloaded content
$sContent = str_replace(chr(13), " ", "here's the ". chr(13) ."preloaded <b>content</b>");
?>
<?php include "includes/rte.php"; LoadRTE("msgbody", $sContent, "", "", true); ?>
<!-- END PHP -->
<script language="JavaScript" type="text/javascript">
<!--
function submitForm() {
	//call updateMessage to 
	updateRTE();
	alert(document.RichTextEditor.msgbody.value);
	
	//change the following line to true to submit form
	return false;
}
//-->
</script>
<noscript><p><b>Javascript must be enabled to use this form.</b></p></noscript>

<p>Click submit to show the value of the text box.</p>
<p><input type="submit" name="submit" value="Submit"></p>
</form>
</body>
</html>