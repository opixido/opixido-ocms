window.hasFlash = 0;

function deb(t) {
	gid("debug").innerHTML = t+"<br/>"+gid("debug").innerHTML;
}

function gid(t) {
	return document.getElementById(t);
}


function doblank (atag) {
	window.open(atag.href);
	return false;
}

function doBlank (atag) {
	return doblank(atag);
}


function findPosX(obj)
{
    var curleft = 0;
    if (obj.offsetParent)
    {
        while (obj.offsetParent)
        {
            curleft += obj.offsetLeft
            obj = obj.offsetParent;
        }
    }
    else if (obj.x)
        curleft += obj.x;
    return curleft;
}


function findPosY(obj)
{
    var curtop = 0;
    if (obj.offsetParent)
    {
        while (obj.offsetParent)
        {
            curtop += obj.offsetTop
            obj = obj.offsetParent;
        }
    }
    else if (obj.y)
        curtop += obj.y;
    return curtop;
}



function getElementHeight(Elem) {

		if(typeof Elem != 'string') {
			var elem = Elem;
			
		} else
		if(document.getElementById) {
			var elem = document.getElementById(Elem);
		} else if (document.all){
			var elem = document.all[Elem];
		} else {
			return;
		}
		xPos = elem.offsetHeight;
		
		return xPos;
	
}

function getElementWidth(Elem) {

		if(typeof Elem != 'string') {
			var elem = Elem;
		} else 
		if(document.getElementById) {
			var elem = document.getElementById(Elem);
		} else if (document.all){
			var elem = document.all[Elem];
		} else {
			return;
		}

		xPos = elem.offsetWidth;
		
		return xPos;
	
}

function checkSize() {
 		if (self.innerWidth)
	    {
		frameWidth = self.innerWidth;
		frameHeight = self.innerHeight;
	    }

	    else if (document.documentElement && document.documentElement.clientWidth)

	    {
		frameWidth = document.documentElement.clientWidth;
		frameHeight = document.documentElement.clientHeight;
	    }

	    else if (document.body)

	    {
		frameWidth = document.body.clientWidth;
		frameHeight = document.body.clientHeight;
	    }
	    //deb(frameWidth+" : "+frameHeight);
}

function goBack() {
	window.history.go(-1);
	return false;
}

function smallPopup(obj) {
	var a = window.open(obj.href,'popup','width=280,height=340,scrollbars=1');
	return false;
}


function popup(hrefe,w,h) {
	var a = window.open(hrefe,'popup','width='+w+',height='+h+'');
	return false;
}
function dopopup(obje,w,h) {
	return popup(obje.href,w,h);
}


linkedBlank = 0;
function linkBlank(atag) {
	linkedBlank++;
	window.open(atag.href,'link_blank_'+linkedBlank);
	return false;
}


function swapImg(url,obj) {
	obj.oldsrc=obj.src;
	obj.src=url;
}

function swapRestore(obj) {
	obj.src = obj.oldsrc;
}


function showhide(ide) {
	
	if(typeof(ide) == 'string') {
		var obj = gid(ide);
	} else {
		var obj = ide;
	}
	
	if(obj.style.display=="none" || obj.style.display=="") {
		if(obj.style.oldDisplay) {
			obj.style.display=obj.style.oldDisplay;
		} else {
			obj.style.display='block';
		}
	} else {
		obj.style.oldDisplay=obj.style.display;
		obj.style.display='none';
	}

}

function showhidejq(ide) {
	if(typeof(ide) == 'string') {
		var obj = gid(ide);
	} else {
		var obj = ide;
	}	
	obj = $(obj);
	if(obj.is(':visible')) {
		obj.hide('normal');
	} else {
		obj.show('normal');
	}
}


function show(ide) {
	var obj = gid(ide);	
	
	obj.style.display='block';
}

function hide(ide) {
	var obj = gid(ide);
	obj.style.display='none';
}



function XHR(url, paramsUrl, divToFill,dosomethingelse,obj) {

	if(window.XMLHttpRequest) // Firefox
		var http = new XMLHttpRequest();
	else if(window.ActiveXObject) // Internet Explorer
		var http = new ActiveXObject("Microsoft.XMLHTTP");
	else { // XMLHttpRequest non support矰ar le navigateur
		alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
	}

	

	http.open("GET", url+paramsUrl, true);

	http.onreadystatechange = function()
	  {



	    if (http.readyState == 4)
	    {


		if ( typeof divToFill == 'string') {
			if(divToFill == 'none') {

				return;
			} else if(divToFill == 'javascript_eval') {
				eval(http.responseText);
				return;
			}
	        if(gid(divToFill))
				divToFill = gid(divToFill);
		}
		
		resp = (http.responseText);
		
		//divToFill.innerHTML = resp;
		
		try {
			divToFill.innerHTML = resp;
		} catch (e) {
			alert(e.description);
			alert(resp);
			
		}
		
		
		if(typeof dosomethingelse == 'string') {
			if(dosomethingelse.length > 0) {
				//alert(dosomethingelse);
				eval(dosomethingelse);
			}
		}

	    }
	  };
  			http.send(null);
}



function showPhotos(diapora,ide,lien) {
	 gid('diapo_sel').id = '';
	 lien.id = 'diapo_sel';
	 gid('m_photo_'+currentPhoto[diapora]).style.display='none';
	 gid('m_photo_'+ide).style.display='block';
	 currentPhoto[diapora] = ide;
}



function popupAdmin(obj,table,id,field) {
	//obj.innerHTML = '<textarea type="text">'+obj.innerHTML+"</textarea>";
	/*
	var h = getElementHeight(obj.id);
	var l = getElementWidth(obj.id);
	*/
	/*var ifra = gid('iframeadmin');
	ifra.style.left = findPosX(obj)+"px";
	ifra.style.top = findPosY(obj)+"px";
	ifra.style.width = getElementWidth(obj.id)+"px";
	ifra.style.height = getElementHeight(obj.id)+"px";
	*/
	var src = '/h/admin/index.php?gfa=1&curTable='+table+"&curId="+id+"&field="+field;
	
	window.open(src,'','width=600,height=200');
}

function gfa_roll() {
	return;
}

function gfa(obj,table,id,champ) {
	
	//XHR('/h/admin/?xhr=gfa&amp;field='+champ,'','','dogfa(http.responseText,obj)',obj);
	var x = findPosX(obj);
	var y = findPosY(obj);
	
	if(!gid('gfa_item')) {
		var div = document.createElement("iframe");
		div.id = 'gfa_item';
		document.body.appendChild(div);	
	} else {
		div = gid('gfa_item');
	}
		
	var h = getElementHeight(obj);
	var w = getElementWidth(obj);
	h = h < 100 ? 100 : h+50;
	w = w < 50 ? 50 : w+20;
	
	div.style.left = x+"px";
	div.src = '/h/admin/?gfa=1&field='+champ+"&curTable="+table+"&curId="+id;
	div.style.width = w+"px";
	div.style.height = h+"px";
	div.style.top = y+"px";
	

}

function dogfa(e,obj) {
	
	var x = findPosX(obj);
	var y = findPosY(obj);
	if(!gid('gfa_item')) {
		var div = document.createElement("div");
		div.id = 'gfa_item';
	} else {
		div = gid('gfa_item');
	}
	div.style.left = x+"px";
	div.innerHTML = e;
	div.firstChild.style.width = getElementWidth(obj)+"px";
	div.firstChild.style.height = getElementHeight(obj)+"px";
	div.style.top = y+"px";
	
	if(!gid('gfa_item')) {
		document.body.appendChild(div);	
	}
}

function dom_getElements(node, elt) {
    var list = node.getElementsByTagName(elt);
    return list;
    return (list.length) ? list : node.getElementsByTagNameNS("*", elt); 
}


// Browser Detection
isMac = (navigator.appVersion.indexOf("Mac")!=-1) ? true : false;
NS4 = (document.layers) ? true : false;
IEmac = ((document.all)&&(isMac)) ? true : false;
IE4plus = (document.all) ? true : false;
IE4 = ((document.all)&&(navigator.appVersion.indexOf("MSIE 4.")!=-1)) ? true : false;
IE5 = ((document.all)&&(navigator.appVersion.indexOf("MSIE 5.")!=-1)) ? true : false;
ver4 = (NS4 || IE4plus) ? true : false;
NS6 = (!document.layers) && (navigator.userAgent.indexOf('Netscape')!=-1)?true:false;


// Body onload utility (supports multiple onload functions)

var gSafeOnload = new Array();

function SafeAddOnload(f)
{
	if (IEmac && IE4)  // IE 4.5 blows out on testing window.onload
	{
		window.onload = SafeOnload;
		gSafeOnload[gSafeOnload.length] = f;
	}
	else if  (window.onload)
	{
		if (window.onload != SafeOnload)
		{
			gSafeOnload[0] = window.onload;
			window.onload = SafeOnload;
		}		
		gSafeOnload[gSafeOnload.length] = f;
	}
	else
		window.onload = f;
}
function SafeOnload()
{
	for (var i=0;i<gSafeOnload.length;i++)
		gSafeOnload[i]();
}

window.onload = SafeOnload;

// Call the following with your function as the argument

//SafeAddOnload();

function exaDefaultText(nom) {
	var champ = gid(nom);
	
	if(champ) {
		window.exa_qDefault = champ.value;
		champ.onfocus =  function () {
			if(this.value == window.exa_qDefault) {
				this.value ='';
			}
		}
		champ.onblur = function () {
			if(this.value == '') {
				this.value = window.exa_qDefault;
			}
		}
	}
}

//SafeAddOnload(exaDefaultText);


function menuLines() {
	var menu = gid('themes_web');
	var cn = menu.childNodes;
	var h = 0;
	for(p in cn) {
		if(cn[p].getElementsByTagName) {
			var span = cn[p].getElementsByTagName('span')[0];
			h = (getElementHeight(span));
			if(h < 20) {
				span.style.lineHeight = '1.8em';
			} 
		}
		
	}
	
}
//SafeAddOnload(menuLines);


function getSelectedOption(obj) {
	if(obj.options && obj.selectedIndex) {
		return obj.options[obj.selectedIndex].value;
	} else {
		return false;
	}
}


function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}


function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}

function setCookie(c_name,value,expiredays)
{
var exdate=new Date();
exdate.setDate(exdate.getDate()+expiredays);
document.cookie=c_name+ "=" +escape(value)+
((expiredays==null) ? "" : ";expires="+exdate.toGMTString())+";path=/";
}

function setCookieOld( name, value, expires, path, domain, secure ) {

	var today = new Date();

	today.setTime( today.getTime() );

	if ( expires ) {

		expires = expires * 1000 * 60 * 60 * 24;

	}

	var expires_date = new Date( today.getTime() + (expires) );

	document.cookie = name+'='+escape( value ) +

		( ( expires ) ? ';expires='+expires_date.toGMTString() : '' ) + //expires.toGMTString()

		( ( path ) ? ';path=' + path : '' ) +

		( ( domain ) ? ';domain=' + domain : '' ) +

		( ( secure ) ? ';secure' : '' );

}


function clone(obj){

    if(obj == null || typeof(obj) != 'object')

        return obj;



    var temp = new obj.constructor(); // changed (twice)

    for(var key in obj)

        temp[key] = clone(obj[key]);



    return temp;

}

function getElementsByClass(searchClass,node,tag) {
	var classElements = new Array();
	if ( node == null )
		node = document;
	if ( tag == null )
		tag = '*';
	var els = node.getElementsByTagName(tag);
	var elsLen = els.length;
	var pattern = new RegExp("(^|\\\\s)"+searchClass+"(\\\\s|$)");
	for (i = 0, j = 0; i < elsLen; i++) {
		if ( pattern.test(els[i].className) ) {
			classElements[j] = els[i];
			j++;
		}
	}
	return classElements;
}




var MM_contentVersion = 9;
var plugin = (navigator.mimeTypes && navigator.mimeTypes["application/x-shockwave-flash"]) ? navigator.mimeTypes["application/x-shockwave-flash"].enabledPlugin : 0;
if ( plugin ) {

		var words = navigator.plugins["Shockwave Flash"].description.split(" ");
	    for (var i = 0; i < words.length; ++i)
	    {
		if (isNaN(parseInt(words[i])))
		continue;
		var MM_PluginVersion = words[i];
	    }
	var MM_FlashCanPlay = MM_PluginVersion >= MM_contentVersion;
}
else if (navigator.userAgent && navigator.userAgent.indexOf("MSIE")>=0
   && (navigator.appVersion.indexOf("Win") != -1)) {

	document.write('<SCR' + 'IPT LANGUAGE=VBScript\> \n'); //FS hide this from IE4.5 Mac by splitting the tag
	document.write('on error resume next \n');
	document.write('MM_FlashCanPlay = ( IsObject(CreateObject("ShockwaveFlash.ShockwaveFlash." & MM_contentVersion)))\n');
	document.write('</SCR' + 'IPT\> \n');

}

if ( !MM_FlashCanPlay ) {
	//alert('NO FLASH');
}