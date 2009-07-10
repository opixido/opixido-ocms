

function XHR_links(champ) {
	XHR('index.php?xhr=links&champ='+champ,'',gid(champ+'_links'),'handler_links("'+champ+'")');
}


function update_links(champ,id) {
	gid(champ).value = "@rubrique_id="+id;
	//gid(champ+'_links').style.display = 'none';
	showHide(champ+'_links');
}

function handler_links(champ) {
	//gid(champ+'_links').style.display = 'block';
showHide(champ+'_links');
}

function XHR_menuArbo(url,obj) {
	XHR(url+'&xhr=arbo','','','handler_menuArbo(http.responseText)',obj);

}

function handler_menuArbo(html) {
	//alert(html);
	gid('arbo').innerHTML = html;	
}

function XHR_editTrad(obj) {
	XHR('index.php?xhr=editTrad&nom='+obj.name+'&valeur='+$(obj).val(),'','','handler_editTrad(http.responseText)',obj);
	obj.style.display = "none";

}

function handler_editTrad(resp) {
		
}

lastChampRel= '';
lastQ = '';
function XHR_tablerel(table,id,champ,obj) {
	if(obj.hasAttribute("value"))
		var q = obj.value;
	else
		var q = obj;
	if(champ == lastChampRel && q == lastQ) {
		return;
	}
	lastChampRel = champ;
	lastQ = q;
	XHR('index.php','?xhr=tablerel&curTable='+table+'&curId='+id+'&champ='+champ+'&q='+q,'','handler_filltablerel(http.responseText)',obj);
}


function XHRDel404(id,obj) {
	XHR('index.php','?xhr=del404&id='+id,'');
	obj.parentNode.parentNode.innerHTML = '';
}


function handler_filltablerel(val) {
	//alert(val);
	if(lastChampRel){
		
		gid(lastChampRel+"_").innerHTML = val;
		if(document.all)
		gid(lastChampRel+"_").outerHTML = gid(lastChampRel+"_").outerHTML;

	}
	lastChampRel = false;
}

lastClickedElement = false;

function showLoader(obj) {
	var lood = gid('xhrloader');
	
	if(obj) {
		var poses = findPos(obj);
		
		lood.style.left = (poses[0] -16)+"px";
		lood.style.top = poses[1]+"px";
		
	} else {
		/*else if(lastClickedElement) {
		
		var poses = findPos(lastClickedElement);
		
		lood.style.left = (poses[0])+"px";
		lood.style.top = poses[1]+"px";
		
	}*/
		
		lood.style.left = (mouseX+20)+"px";
		lood.style.top = (mouseY+20)+"px";
	}

	lood.style.display = "block";
	tooltip.hide();
}

jsevents = "";

function addJsEvent(event,value) {
	//var d = new Date();
	jsevents = '<b>'+event+'</b><br/><pre>'+replace(replace(value,'<',('&lt;')),'>',('&gt;'))+'</pre><hr/>' + jsevents;
	gid('jsevents').innerHTML = jsevents;
}


function replace(string,text,by) {
// Replaces text with by in string
    var strLength = string.length, txtLength = text.length;
    if ((strLength == 0) || (txtLength == 0)) return string;

    var i = string.indexOf(text);
    if ((!i) && (text != string.substring(0,txtLength))) return string;
    if (i == -1) return string;

    var newstr = string.substring(0,i) + by;

    if (i+txtLength < strLength)
        newstr += replace(string.substring(i+txtLength,strLength),text,by);

    return newstr;
}


function XHRs(url) {
	
	if(window.XMLHttpRequest) // Firefox
		var http = new XMLHttpRequest();
	else if(window.ActiveXObject) // Internet Explorer
		var http = new ActiveXObject("Microsoft.XMLHTTP");
	else { // XMLHttpRequest non support矰ar le navigateur
		alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
	}
	
	showLoader();
	(http.open("GET", url, false));
	
	
	http.send(null);
	gid('xhrloader').style.display = "none";
	return (http.responseText);	
	
}

function XHR(url, paramsUrl, divToFill,dosomethingelse,obj) {
	
	if(window.XMLHttpRequest) // Firefox
		var http = new XMLHttpRequest();
	else if(window.ActiveXObject) // Internet Explorer
		var http = new ActiveXObject("Microsoft.XMLHTTP");
	else { // XMLHttpRequest non support矰ar le navigateur
		alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
	}
	
	showLoader(obj);
	
	http.open("GET", url+paramsUrl, true);
	addJsEvent('XHR_SENT',url+paramsUrl);
	http.onreadystatechange = function()
	  {
	  	if (http.readyState == 4)
	    {
		
	    	gid('xhrloader').style.display = "none";
	    	addJsEvent('XHR_RECEIVED',http.responseText);
	
		if(typeof dosomethingelse == 'string') {
			if(dosomethingelse.length > 0) {
				//alert(dosomethingelse);
				eval(dosomethingelse);
			}
		}
		if ( typeof divToFill == 'string') {
			if(divToFill == 'none') {
				//alert(http.responseText);
				return;
			} else if(divToFill == 'javascript_eval') {
				eval(http.responseText);
				return;
			}
	        if(gid(divToFill))
				divToFill = gid(divToFill);
		}
		
		var resp = http.responseText;
		if(divToFill) {
			divToFill.innerHTML = http.responseText;
			checkScripts(divToFill);
		}
		
		
		
		if(resp.indexOf('//-TOEVAL-') > 0 && resp.indexOf('//-ENDEVAL-') > 0) {
			var toev1 = resp.split('//-TOEVAL-');
			var s = '';
			var toev = '';
			for (var p in toev1) {
				if(p == 0 )
				 continue;
				s = toev1[p];
				toev = s.substring(0,s.indexOf('//-ENDEVAL-'));
				//alert(toev);
				eval(toev);
			}
			 
			
		}
		/*if(scrollto) {
			scrollToObject(divToFill);
		}
		*/	
	
	    }
	  };
  			http.send(null);
}


function checkScripts(obj) {
	
	scr = obj.getElementsByTagName('script');
	
	for(p=0;p<scr.length;p++) {
		if(scr[p].src) {
			loadjscssfile(scr[p].src,'js');
		} else {
			//eval(scr[p].innerHTML);
		    var fileref=document.createElement('script');
			fileref.setAttribute("type","text/javascript");
			fileref.text = scr[p].innerHTML;
			document.getElementsByTagName("head")[0].appendChild(fileref);
			//fileref.setAttribute("src", filename)
		}
	}
}
function ajaxSaveValue(obj,table,champ,id) {
	if(obj.value) {
		v = obj.value;
	}
	else {
		v = obj;
	}
	
	var url = "index.php?xhr=ajaxForm&table="+table+"&champ="+champ+"&id="+id+"&save="+v+"&";
	XHR(url);
}


window.ajax_cur_lg = new Array();

function ajaxLgs(divname) {
	
	var div = gid(divname);
	
	var im = '';
	var curlg = window.ajax_cur_lg[divname];
	/*
		
	
	var im = "";
	
	var as = div.getElementsByTagName("a");	
	as = as[0];
	
	
	for ( p  in imgs ) {
		im = imgs[p];	
		im.onclick = function() {
			
		}
	}
	
	*/
	var sel = div.getElementsByTagName("select");
		
	sel = sel[0];
	sel.onchange = function() {
		window.ajax_cur_lg[divname] = this.value;
		sel.style.background = sel.options[sel.selectedIndex].style.background;
		doAjaxLgs(div,sel.options[sel.selectedIndex].value);
		
	}
	sel.style.background = sel.options[sel.selectedIndex].style.background;

		
	doAjaxLgs(div,curlg);		
}


function doAjaxLgs(div,curlg) {
	
	var spans = div.getElementsByTagName("span");
	for ( p  in spans ) {
		spa = spans[p];	
		
		if(!spa.style) {
			continue;
		}		
		
		if(spa.className == "lg_"+curlg) {
			spa.style.display = "inline";
		} else {
			spa.style.display = "none";

		}				
	}
	
}

