var isRichText = false;
var rng;
var oMessageFrame;
var oColorPalette;
var oHdnMessage;

if (rteWidth == '') rteWidth = '510';
if (rteHeight == '') rteHeight = '200';

//Function to format text in the text box
function FormatText(command, option) {
	if ((command == "forecolor") || (command == "hilitecolor")) {
		parent.command = command;
		buttonElement = document.getElementById(command);
		oColorPalette.style.left = getOffsetLeft(buttonElement) + "px";
		oColorPalette.style.top = (getOffsetTop(buttonElement) + buttonElement.offsetHeight) + "px";
		if (oColorPalette.style.visibility == "hidden")
			oColorPalette.style.visibility="visible";
		else {
			oColorPalette.style.visibility="hidden";
		}
		
		//get current selected range
		var sel = oMessageFrame.contentWindow.document.selection; 
		if (sel!=null) {
			rng = sel.createRange();
		}
	}
	else if (command == "createlink") {
		var szURL = prompt("Enter a URL:", "");
		oMessageFrame.contentWindow.document.execCommand("Unlink",false,null)
		oMessageFrame.contentWindow.document.execCommand("CreateLink",false,szURL)
	}
	else {
		oMessageFrame.contentWindow.focus();
	  	oMessageFrame.contentWindow.document.execCommand(command, false, option);
		oMessageFrame.contentWindow.focus();
	}
}

//Function to set color
function setColor(color) {
	var parentCommand = parent.command;
	if (browser.isIE55up) {
		//retrieve selected range
		var sel = oMessageFrame.contentWindow.document.selection;
		if (parentCommand == "hilitecolor") parentCommand = "backcolor";
		if (sel!=null) {
			var newRng = sel.createRange();
			newRng = rng;
			newRng.select();
		}
	}
	else {
		oMessageFrame.contentWindow.focus();
	}
	oMessageFrame.contentWindow.document.execCommand(parentCommand, false, color);
	oMessageFrame.contentWindow.focus();
	oColorPalette.style.visibility="hidden";
}

//Function to add image
function AddImage() {
	imagePath = prompt('Enter Image URL:', 'http://');				
	if ((imagePath != null) && (imagePath != "")) {
		oMessageFrame.contentWindow.focus()
		oMessageFrame.contentWindow.document.execCommand('InsertImage', false, imagePath);
	}
	oMessageFrame.contentWindow.focus();
}

//Function to open pop up window
function openWin(theURL,winName,features) {
  	window.open(theURL,winName,features);
}

//function to perform spell check
function checkspell() {
	try {
		var tmpis = new ActiveXObject("ieSpell.ieSpellExtension");
		tmpis.CheckAllLinkedDocuments(document);
	}
	catch(exception) {
		if(exception.number==-2146827859) {
/*			if (confirm("ieSpell not detected.  Click Ok to go to download page."))
				window.open("http://www.iespell.com/download.php","DownLoad");*/
			if (confirm("ieSpell not detected.  Click OK to download."))
				window.open("http://www.youthencounter.org/resources/ieSpellSetup110665.exe","DownLoad");
		}
		else
			alert("Error Loading ieSpell: Exception " + exception.number);
	}
}

function getOffsetTop(elm) {
	var mOffsetTop = elm.offsetTop;
	var mOffsetParent = elm.offsetParent;
	
	while(mOffsetParent){
		mOffsetTop += mOffsetParent.offsetTop;
		mOffsetParent = mOffsetParent.offsetParent;
	}
	
	return mOffsetTop;
}

function getOffsetLeft(elm) {
	var mOffsetLeft = elm.offsetLeft;
	var mOffsetParent = elm.offsetParent;
	
	while(mOffsetParent) {
		mOffsetLeft += mOffsetParent.offsetLeft;
		mOffsetParent = mOffsetParent.offsetParent;
	}
	
	return mOffsetLeft;
}

function Select(selectname)
{
	var cursel = document.getElementById(selectname).selectedIndex;
	// First one is always a label
	if (cursel != 0) {
		var selected = document.getElementById(selectname).options[cursel].value;
		oMessageFrame.contentWindow.document.execCommand(selectname, false, selected);
		document.getElementById(selectname).selectedIndex = 0;
	}
	oMessageFrame.contentWindow.focus();
}

function Start() {
	//write html based on browser type
	if (browser.isIE55up) {
		isRichText = true;
	}
	else if (browser.isGecko) {
		//check to see if midas is enabled
		document.getElementById('testFrame').contentDocument.designMode = "on";
		try {
			//midas is enabled
			document.getElementById('testFrame').contentWindow.document.execCommand("undo", false, null);
			isRichText = true;
		}  catch (e) {
			//midas is not enabled
			isRichText = false;
		}
	}
	else {
		//other browser
		isRichText = false;
	}
	
	if (isRichText) {
		writeRTE();
		oMessageFrame = document.getElementById(fieldName);
		oColorPalette = document.getElementById('colorpalette');
		oHdnMessage = document.getElementById('hdn' + fieldName);
	} else {
		writeDefault();
	}
}

function writeRTE() {
	if (showButtons == 'True') {
		document.writeln('<table>');
		document.writeln('	<tr>');
		document.writeln('		<td>');
		document.writeln('			<select id="formatblock" onchange="Select(this.id);">');
		document.writeln('				<option value="<p>">Normal</option>');
		document.writeln('				<option value="<p>">Paragraph</option>');
		document.writeln('				<option value="<h1>">Heading 1 <h1></option>');
		document.writeln('				<option value="<h2>">Heading 2 <h2></option>');
		document.writeln('				<option value="<h3>">Heading 3 <h3></option>');
		document.writeln('				<option value="<h4>">Heading 4 <h4></option>');
		document.writeln('				<option value="<h5>">Heading 5 <h5></option>');
		document.writeln('				<option value="<h6>">Heading 6 <h6></option>');
		document.writeln('				<option value="<address>">Address <ADDR></option>');
		document.writeln('				<option value="<pre>">Formatted <pre></option>');
		document.writeln('			</select>');
		document.writeln('		</td>');
		document.writeln('		<td>');
		document.writeln('			<select id="fontname" name="selectFont" onchange="Select(this.id)">');
		document.writeln('				<option value="Font" selected>Font</option>');
		document.writeln('				<option value="Arial, Helvetica, sans-serif">Arial</option>');
		document.writeln('				<option value="Courier New, Courier, mono">Courier New</option>');
		document.writeln('				<option value="Times New Roman, Times, serif">Times New Roman</option>');
		document.writeln('				<option value="Verdana, Arial, Helvetica, sans-serif">Verdana</option>');
		document.writeln('			</select>');
		document.writeln('		</td>');
		document.writeln('		<td>');
		document.writeln('			<select unselectable="on" id="fontsize" onchange="Select(this.id);">');
		document.writeln('				<option value="Size">Size</option>');
		document.writeln('				<option value="1">1</option>');
		document.writeln('				<option value="2">2</option>');
		document.writeln('				<option value="3">3</option>');
		document.writeln('				<option value="4">4</option>');
		document.writeln('				<option value="5">5</option>');
		document.writeln('				<option value="6">6</option>');
		document.writeln('				<option value="7">7</option>');
		document.writeln('			</select>');
		document.writeln('		</td>');
		document.writeln('	</tr>');
		document.writeln('</table>');
		document.writeln('<table cellpadding="1" cellspacing="0">');
		document.writeln('	<tr>');
		document.writeln('		<td><img class="btnImage" src="images/post_button_bold.gif" width="25" height="24" alt="Bold" title="Bold" onClick="FormatText(\'bold\', \'\')"></td>');
		document.writeln('		<td><img class="btnImage" src="images/post_button_italic.gif" width="25" height="24" alt="Italic" title="Italic" onClick="FormatText(\'italic\', \'\')"></td>');
		document.writeln('		<td><img class="btnImage" src="images/post_button_underline.gif" width="25" height="24" alt="Underline" title="Underline" onClick="FormatText(\'underline\', \'\')"></td>');
		document.writeln('		<td>&nbsp;</td>');
		document.writeln('		<td><img class="btnImage" src="images/post_button_left_just.gif" width="25" height="24" alt="Align Left" title="Align Left" onClick="FormatText(\'justifyleft\', \'\')"></td>');
		document.writeln('		<td><img class="btnImage" src="images/post_button_centre.gif" width="25" height="24" alt="Center" title="Center" onClick="FormatText(\'justifycenter\', \'\')"></td>');
		document.writeln('		<td><img class="btnImage" src="images/post_button_right_just.gif" width="25" height="24" alt="Align Right" title="Align Right" onClick="FormatText(\'justifyright\', \'\')"></td>');
		document.writeln('		<td>&nbsp;</td>');
		document.writeln('		<td><img class="btnImage" src="images/post_button_numbered_list.gif" width="25" height="24" alt="Ordered List" title="Ordered List" onClick="FormatText(\'insertorderedlist\', \'\')"></td>');
		document.writeln('		<td><img class="btnImage" src="images/post_button_list.gif" width="25" height="24" alt="Unordered List" title="Unordered List" onClick="FormatText(\'insertunorderedlist\', \'\')"></td>');
		document.writeln('		<td>&nbsp;</td>');
		document.writeln('		<td><img class="btnImage" src="images/post_button_outdent.gif" width="25" height="24" alt="Outdent" title="Outdent" onClick="FormatText(\'outdent\', \'\')"></td>');
		document.writeln('		<td><img class="btnImage" src="images/post_button_indent.gif" width="25" height="24" alt="Indent" title="Indent" onClick="FormatText(\'indent\', \'\')"></td>');
		document.writeln('		<td><div id="forecolor"><img class="btnImage" src="images/post_button_textcolor.gif" width="25" height="24" alt="Text Color" title="Text Color" onClick="FormatText(\'forecolor\', \'\')"></div></td>');
		document.writeln('		<td><div id="hilitecolor"><img class="btnImage" src="images/post_button_bgcolor.gif" width="25" height="24" alt="Background Color" title="Background Color" onClick="FormatText(\'hilitecolor\', \'\')"></div></td>');
		document.writeln('		<td>&nbsp;</td>');
		document.writeln('		<td><img class="btnImage" src="images/post_button_hyperlink.gif" width="25" height="24" alt="Insert Link" title="Insert Link" onClick="FormatText(\'createlink\')"></td>');
		document.writeln('		<td><img class="btnImage" src="images/post_button_image.gif" width="25" height="24" alt="Add Image" title="Add Image" onClick="AddImage()"></td>');
		if (browser.isIE55up) document.writeln('		<td><img class="btnImage" src="images/post_button_spellcheck.gif" width="25" height="24" alt="Add Image" title="Add Image" onClick="checkspell()"></td>');
		document.writeln('		<td>&nbsp;</td>');
		document.writeln('		<td><img class="btnImage" src="images/post_button_cut.gif" width="25" height="24" alt="Cut" title="Cut" onClick="FormatText(\'cut\')"></td>');
		document.writeln('		<td><img class="btnImage" src="images/post_button_copy.gif" width="25" height="24" alt="Copy" title="Copy" onClick="FormatText(\'copy\')"></td>');
		document.writeln('		<td><img class="btnImage" src="images/post_button_paste.gif" width="25" height="24" alt="Paste" title="Paste" onClick="FormatText(\'paste\')"></td>');
	//	document.writeln('		<td>&nbsp;</td>');
	//	document.writeln('		<td><img class="btnImage" src="images/post_button_undo.gif" width="25" height="24" alt="Undo" title="Undo" onClick="FormatText(\'undo\')"></td>');
	//	document.writeln('		<td><img class="btnImage" src="images/post_button_redo.gif" width="25" height="24" alt="Redo" title="Redo" onClick="FormatText(\'redo\')"></td>');
		document.writeln('	</tr>');
		document.writeln('</table>');
	//	document.writeln('<br>');
	}
	document.writeln('<iframe id="' + fieldName + '" width="' + rteWidth + 'px" height="' + rteHeight + 'px" style="background-color: #FFFFFF;"></iframe>');
	document.writeln('<iframe width="254" height="174" id="colorpalette" src="includes/palette.htm" marginwidth="0" marginheight="0" scrolling="no" style="visibility:hidden; position: absolute; left: 0px; top: 0px;"></iframe>');
	document.writeln('<input type="hidden" id="hdn' + fieldName + '" name="' + fieldName + '" value="">');
	setTimeout("enableDesignMode()", 1000);
}

function writeDefault() {
	document.writeln('<textarea name="' + fieldName + '" id="' + fieldName + '" style="width: ' + rteWidth + 'px; height: ' + rteHeight + 'px;"></textarea>');
}

function enableDesignMode() {
	if (browser.isIE55up) {
		oMessageFrame.contentWindow.document.designMode = "On";
	}
	else {
		oMessageFrame.contentDocument.designMode = "on";
	}
	setTimeout("oMessageFrame.contentWindow.document.body.innerHTML = htmlContent;", 100);
}

function updateRTE() {
	//set message value
	if (isRichText) {
		if (oHdnMessage.value == null) oHdnMessage.value = "";
		oHdnMessage.value = oMessageFrame.contentWindow.document.body.innerHTML;
		//exception for Mozilla
		if (oHdnMessage.value.indexOf('<br>') > -1 && oHdnMessage.value.length == 8) oHdnMessage.value = "";
	}
}
