// overly simplistic test for IE
var isIE;
isIE = (document.all ? true : false);
// both IE5 and NS6 are DOM-compliant
var isDOM;
isDOM = (document.getElementById ? true : false);

// get the true offset of anything on NS4, IE4/5 & NS6, even if it's in a table!

function getAbsX(elt) { return (elt.x) ? elt.x : getAbsPos(elt,"Left"); }
function getAbsY(elt) { return (elt.y) ? elt.y : getAbsPos(elt,"Top"); }
function getAbsPos(elt,which) {
 iPos = 0;
 while (elt != null) {
  iPos += elt["offset" + which];
  elt = elt.offsetParent;
 }
 return iPos;
}
frameWidth = 800;
frameHeight = 600;

function CheckSize() {
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


}

function getDivStyle(divname) {
 var style;
 if (isDOM) { 
 			if(dv = gid(divname))
 			style = dv.style; 
 
 }
 else { style = isIE ? document.all[divname].style
                     : document.layers[divname]; } // NS4
 return style;
}

function hideElement(divname) {
	if(vd = getDivStyle(divname))
 		vd.display = 'none';
}


function hideOnglet(i) {
    hideElement("genform_btn_page_"+i);
}

// annoying detail: IE and NS6 store elt.top and elt.left as strings.
function moveBy(elt,deltaX,deltaY) {
 elt.left = parseInt(elt.left) + deltaX;
 elt.top = parseInt(elt.top) + deltaY;
}

function toggleVisible(divname) {
 divstyle = getDivStyle(divname);
 if (divstyle.visibility == 'visible' || divstyle.visibility == 'show') {
   divstyle.visibility = 'hidden';
 } else {
   fixPosition(divname);
   divstyle.visibility = 'visible';
 }
}

function setPosition(elt,positionername,isPlacedUnder) {
 var positioner;
 if (isIE) {
  positioner = document.all[positionername];
 } else {
  if (isDOM) {
    positioner = document.getElementById(positionername);
  } else {
    // not IE, not DOM (probably NS4)
    // if the positioner is inside a netscape4 layer this will *not* find it.
    // I should write a finder function which will recurse through all layers
    // until it finds the named image...
    positioner = document.images[positionername];
  }
 }
 elt.left = getAbsX(positioner);
 elt.top = getAbsY(positioner);
 elt.zIndex = 1546;
}
/*
genform_totalPages = 0;
function genform_activatePage(page) {
	//alert(genform_totalPages);
	if(genform_totalPages) {
		
	
	    for(p=0;p<genform_totalPages;p++) {
	    	if(gid("genform_div_page_"+p)) {
		       	gid("genform_div_page_"+p).className = "btnOngletOff";
	       	}
	       	if(gid("genform_page_"+(p+1))) {
	       		gid("genform_page_"+(p+1)).style.display = "none";
	       	}
	
	    }
	    gid("genform_div_page_"+page).className = "btnOngletOn";
	    gid("genform_page_"+(page+1)).style.display = "block";
	    if(divcur = gid("curPage"))
		divcur.value = page;
		else
	    gid("genform_curPage").value = page;
	}
    
    //alert(gid("curPage").value);
    //eval("editor"+EditorsByPage[page+1]+".generate.initIframe")()


}

*/

divHeight = 60;
divWidth = 150;


function validInsideSubmit(obj) {

	inputs = obj.getElementsByTagName("input");

	for(inpu in inputs) {
		inpu = inputs[inpu];
		if(inpu.type == "image" || inpu.type == "submit") {
			if(inpu.onclick) {
				inpu.onclick();
			} else {
				t=obj;
				while(1) {
					t = t.parentNode;
					if(t.nodeName.toLowerCase() == "form"){
						t.submit();
						break;
					}
				}
			}
			break;
		}
	}
}


function gid (eleme) {
    return document.getElementById(eleme);
}


oldid ="";
oldobj = "";
function swapactions(id,obj) {
	/*if(oldid != "") {
		gid(oldid).style.visibility = "hidden";
		oldobj.parentNode.style.background = 'none';
	}
	//obj.parentNode.style.background = '#cccccc';

	gid(id).style.visibility = "visible";

	oldobj = obj;
	oldid = id;*/
}



function showHideActions() {
	var act	= gid('gen_actions');
	if(act.style.display == "block") {
		act.style.display = 'none';
	} else {
		act.style.display = 'block';
	}
}



