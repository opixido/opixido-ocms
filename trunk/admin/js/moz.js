

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
	};

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
