
    
    
function arSaveValue(obj,table,champ,id,curtable) {
	url = "index.php?xhr=ajaxRelinv&table="+table+"&field="+champ+"&id="+id+"&save="+obj.value+"&curtable="+curtable;
	//alert(url);
	XHR(url);
}
			
function arAddValue(obj,table,fake,id) {
	url = "index.php?xhr=ajaxRelinv&table="+table+"&fake="+fake+"&id="+id;
	//alert(url);
	XHR(url,"","","addRowToTable(\'ar_"+table+"-"+fake+"\', http.responseText)");
}

function addRowToTable(table,contenu) {
	var tabl = gid(table);
	//alert(tabl.innerHTML);
	
	if(document.all) {
	//alert("test");
		outer = gid(table).outerHTML;
		//alert(outer);
	   gid(table).outerHTML =  outer.replace("</TABLE>", "<tbody>"+contenu+"</tbody></table>");		
	} else {
		var newTb = document.createElement("tbody");
		tabl.appendChild(newTb); 
		newTb.innerHTML = contenu;
		
		//tabl.lastChild.innerHTML = contenu;
	}
	// alert(contenu);
	
}	

function is_ignorable( nod )
{
  return ( nod.nodeType == 8) || // A comment node
         ( (nod.nodeType == 3) && is_all_ws(nod) ); // a text node, all ws
}

function node_before( sib )
{
  while ((sib = sib.previousSibling)) {
    if (!is_ignorable(sib)) return sib;
  }
  return null;
}

function is_all_ws( nod )
{
  // Use ECMA-262 Edition 3 String and RegExp features
  return !(/[^\t\n\r ]/.test(nod.data));
}

function node_after( sib )
{
  while ((sib = sib.nextSibling)) {
    if (!is_ignorable(sib)) return sib;
  }
  return null;
}

function arDelete(obj,faketable,id) {
	url = "index.php?xhr=ajaxRelinv&table="+faketable+"&delete="+id+"&";				
	obj.parentNode.parentNode.parentNode.parentNode.removeChild(obj.parentNode.parentNode.parentNode);
	XHR(url);
}

function arGoUp(obj) {
	var tbod = obj.parentNode.parentNode.parentNode;
	var tabl = tbod.parentNode;
	
	var prev = node_before(tbod);		
	if(prev) {		
		tabl.insertBefore(tbod,prev);				
	}
}

function arGoDown(obj) {
	var tbod = obj.parentNode.parentNode.parentNode;
	var tabl = tbod.parentNode;
	var nex = node_after(tbod);
	
	if(nex) {
		tabl.insertBefore(nex,tbod);
	}
	
}    

 
    
// SUPPRESSION D'UN NOEUD
function FArem(child,nom,id){

 
 
  
  // si c'est le dernier enfant, on supprimer le UL au dessus
  if(child.parentNode.parentNode.childNodes.length == 1){ 
      child.parentNode.parentNode.parentNode.removeChild(child.parentNode.parentNode);
  }
  // sinon juste le LI
  else {
      child.parentNode.parentNode.removeChild(child.parentNode);
  }
  return false;
}
    
// AJOUT D'UN NOEUD
function FAadd(obj,nom,id){
	var ul = obj.parentNode.getElementsByTagName('ul')[0];
	var newLi = document.createElement("li");
	ul.appendChild(newLi); 
	
	newLi.id = nom+"_"+Math.round(Math.random()*1000000);	
	
	XHR(ajaxActionUrl('add',window.arboFull[nom]['vtable'],id,(window.arboFull[nom])),'',newLi.id,'checkUpDown();');
	
	//newLi.innerHTML = "csddsdfsd";
	return false;
}
    
    
    
function FAgoUp(obj,nom,id) {
	var tbod = obj.parentNode;
	var tabl = tbod.parentNode;
	
	var prev = node_before(tbod);		
	
	if(prev) {		
		ajaxAction('goup',window.arboFull[nom]['vtable'],id,window.arboFull[nom]);
		tabl.insertBefore(tbod,prev);	
		checkUpDown(obj);			
	}
	
	return false;
	
}



function FAgoDown(obj,nom,id) {
	var tbod = obj.parentNode;
	var tabl = tbod.parentNode;
	var nex = node_after(tbod);
	
	if(nex) {
		ajaxAction('godown',window.arboFull[nom]['vtable'],id,window.arboFull[nom]);
		tabl.insertBefore(nex,tbod);
		checkUpDown(obj);
	}
	return false;
}	

function checkUpDown(obj) {
	var objs = getElementsByClassName('FAgoUp','a',gid("racine"));
	//alert(objs);
	for(p in objs) {
		if( node_before(objs[p].parentNode)) {
			objs[p].style.opacity = "1";
		} else {
			
			objs[p].style.opacity = "0.5";
		}
	}
	
	var objs = getElementsByClassName('FAgoDown','a',gid("racine"));
	for(p in objs) {
		if(node_after(objs[p].parentNode)) {
			objs[p].style.opacity = "1";
		} else {
			objs[p].style.opacity = "0.5";
		}
	}
	//alert(objs);
}




function FAdel(obj,nom,id) {
	//url = "index.php?xhr=ajaxRelinv&table="+faketable+"&delete="+id+"&";	
	 ajaxAction('del',window.arboFull[nom]['vtable'],id);			
	obj.parentNode.parentNode.removeChild(obj.parentNode);
	return false;
	//XHR(url);
}		


function ajaxAction(action,table,id,params) {
	
	XHRs(ajaxActionUrl(action,table,id,params));		
	
		
}

function ajaxActionUrl(action,table,id,params) {
	url = "index.php?xhr=ajaxAction&table="+table+"&action="+action+"&id="+id+"&params="+serialize(params);	
	return url;
}
			
			
function serialize( inp ) {
    // Generates a storable representation of a value
    // 
    // +    discuss at: http://kevin.vanzonneveld.net/techblog/article/javascript_equivalent_for_phps_serialize/
    // +       version: 804.1712
    // +   original by: Arpad Ray (mailto:arpad@php.net)
    // *     example 1: serialize(['Kevin', 'van', 'Zonneveld']);
    // *     returns 1: 'a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}'

    var getType = function( inp ) {
        var type = typeof inp, match;
        if(type == 'object' && !inp)
        {
            return 'null';
        }
        if (type == "object") {
            if(!inp.constructor)
            {
                return 'object';
            }
            var cons = inp.constructor.toString();
            if (match = cons.match(/(\w+)\(/)) {
                cons = match[1].toLowerCase();
            }
            var types = ["boolean", "number", "string", "array"];
            for (key in types) {
                if (cons == types[key]) {
                    type = types[key];
                    break;
                }
            }
        }
        return type;
    };

    var type = getType(inp);
    var val;
    switch (type) {
        case "undefined":
            val = "N";
            break;
        case "boolean":
            val = "b:" + (inp ? "1" : "0");
            break;
        case "number":
            val = (Math.round(inp) == inp ? "i" : "d") + ":" + inp;
            break;
        case "string":
            val = "s:" + inp.length + ":\"" + inp + "\"";
            break;
        case "array":
            val = "a";
        case "object":
            if (type == "object") {
                var objname = inp.constructor.toString().match(/(\w+)\(\)/);
                if (objname == undefined) {
                    return;
                }
                objname[1] = serialize(objname[1]);
                val = "O" + objname[1].substring(1, objname[1].length - 1);
            }
            var count = 0;
            var vals = "";
            var okey;
            for (key in inp) {
                okey = (key.match(/^[0-9]+$/) ? parseInt(key) : key);
                vals += serialize(okey) +
                        serialize(inp[key]);
                count++;
            }
            val += ":" + count + ":{" + vals + "}";
            break;
    }
    if (type != "object" && type != "array") val += ";";
    return val;
}// }}}



if(!document.documentElement.outerHTML){
	Node.prototype.getAttributes = function(){
		var attStr = "";
		if(this && this.attributes.length > 0){
			for(a = 0; a < this.attributes.length; a ++){
				attStr += " " + this.attributes.item(a).nodeName + "=\"";
				attStr += this.attributes.item(a).nodeValue + "\"";
			}
		}
		return attStr;
	}

	Node.prototype.getInsideNodes = function(){
		if(this){
			var cNodesStr = "", i = 0;
			var iEmpty = /^(img|embed|input|br|hr)$/i;
			var cNodes = this.childNodes;
			for(i = 0; i < cNodes.length; i ++){
				switch(cNodes.item(i).nodeType){
					case 1 :
						cNodesStr += "<" + cNodes.item(i).nodeName.toLowerCase();
						if(cNodes.item(i).attributes.length > 0){
							cNodesStr += cNodes.item(i).getAttributes();
						}
						cNodesStr += (cNodes.item(i).nodeName.match(iEmpty))? "" : ">";
						if(cNodes.item(i).childNodes.length > 0){
							cNodesStr += cNodes.item(i).getInsideNodes();
						}
						if(cNodes.item(i).nodeName.match(iEmpty)){
							cNodesStr += " />";
						} else {
							cNodesStr += "</" + cNodes.item(i).nodeName.toLowerCase() + ">";
						}
						break;
					case 3 :
						cNodesStr += cNodes.item(i).nodeValue;
						break;
					case 8 :
						cNodesStr += "<!--" + cNodes.item(i).nodeValue + "-->";
						break;
				}
			}
			return cNodesStr;
		}
	}

	HTMLElement.prototype.outerHTML getter = function(){
		var strOuter = "";
		var iEmpty = /^(img|embed|input|br|hr)$/i;
		switch(this.nodeType){
			case 1 :
				strOuter += "<" + this.nodeName.toLowerCase();
				strOuter += this.getAttributes();
				if(this.nodeName.match(iEmpty)){
					strOuter += " />";
				} else {
					strOuter += ">" + this.getInsideNodes();
					strOuter += "</" + this.nodeName.toLowerCase() + ">";
				}
				break;
			case 3 :
				strOuter += this.nodeValue;
				break;
			case 8 :
				cNodesStr += "<!--" + this.nodeValue + "-->";
				break;
		}
		return strOuter;
	}

	HTMLElement.prototype.outerHTML setter = function(str){
		var iRange = document.createRange();

		iRange.setStartBefore(this);

		var strFragment = iRange.createContextualFragment(str);
		var sRangeNode = iRange.startContainer;

		iRange.insertNode(strFragment);
		sRangeNode.removeChild(this);
	}
}


function set_it(){
	document.getElementById('s').outerHTML = '<span id="s">' + document.getElementById('html').value + '</span>';
}



/**
ARBO

*/

   // AJOUT D'UN NOEUD
    function addChild(param){

        var num = param.parentNode.id.substring(3);
        var childrenId = 'ul_'+num;
        var hasChild = document.getElementById(childrenId);
        
        // Le noeud courant n'a pas d'enfant
        if(!hasChild){
                      
            var childTagUl = document.createElement('ul');
            
            childTagUl.setAttribute('id',childrenId);
            param.parentNode.appendChild(childTagUl);
            
            childTagLi = document.createElement('li');
            var liId = 'li_'+num+'_0';
            childTagLi.setAttribute('id',liId);
            childTagUl.appendChild(childTagLi);
            
        } 
        // le noeud courant a des enfants
        else {
        
            var numLastChild = document.getElementById(childrenId).lastChild.id.substring(3);
            var next = parseInt(numLastChild.substring(numLastChild.length-1))+1;
            var liId = 'li_'+numLastChild.substring(0,numLastChild.length-2)+'_'+next;
            var childTagLi = document.createElement('li');
            childTagLi.setAttribute('id',liId);
            document.getElementById('ul_'+num).appendChild(childTagLi);
            
        }
        
       
              
        // construction du name de l'input pour le récuprérer facilement ensuite
        var inputName = 'n[0][fils]'; 
        
        // si le noeud courant n'a pas encore des fils
        if(!hasChild){
        
              for(i=1;i<=(num.length-1)/2;i++){
                  n = num.substring(2*i,1 + 2*i); 
                  inputName = inputName + '['+ n +'][fils]';
              }
              
              inputName = inputName + '[0][value]'; 
              
        }
        // si le noeud courant a déjà des fils
        else {
              
              for(i=1;i<(numLastChild.length-1)/2;i++){
                  n = numLastChild.substring(2*i,1 + 2*i);
                  inputName = inputName + '['+ n +'][fils]';
              }
              
              inputName = inputName + '['+ next +'][value]'; 
              
        }
        
        /**********************************************************************/
        
        
        
        for(k=0; k < lgs.length; k++){
            // flag
            var lagLG = document.createElement('img');
            lagLG.setAttribute('src','./img/flags/' + lgs[k] + '.gif');
            childTagLi.appendChild(lagLG);
            
            // champ de saisie
            var childTagInput = document.createElement('input');
            childTagInput.setAttribute('type','text');
            inputNameLG = inputName + '[' + lgs[k] + ']'
            childTagInput.setAttribute('name',inputNameLG);
            childTagLi.appendChild(childTagInput);
        }
        
        var childTagButton = document.createElement('a');
        childTagButton.setAttribute('href','#');
        childTagButton.setAttribute('class','addChild');
        childTagButton.setAttribute('onclick','addChild(this)');
        childTagLi.appendChild(childTagButton);
        
        var imgAdd = document.createElement('img');
        imgAdd.setAttribute('src','./pictos/list-add.png');
        childTagButton.appendChild(imgAdd);
        
        var childTagButton = document.createElement('a');
        childTagButton.setAttribute('href','#');
        childTagButton.setAttribute('class','delChild');
        childTagButton.setAttribute('onclick','delChild(this)');
        childTagLi.appendChild(childTagButton);
        
        var imgDel = document.createElement('img');
        imgDel.setAttribute('src','./pictos/process-stop.png');
        childTagButton.appendChild(imgDel);
        
    }
    
    
    // SUPPRESSION D'UN NOEUD
    function delChild(child){
    
      // si c'est le dernier enfant, on supprimer le UL au dessus
      if(child.parentNode.parentNode.childNodes.length == 1){ 
          child.parentNode.parentNode.parentNode.removeChild(child.parentNode.parentNode);
      }
      // sinon juste le LI
      else {
          child.parentNode.parentNode.removeChild(child.parentNode);
      }
      
    }