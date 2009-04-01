// fixPosition() attaches the element named eltname
// to an image named eltname+'Pos'
//
function fixPosition(divname) {
 divstyle = getDivStyle(divname);
 positionerImgName = divname + 'Pos';
 // hint: try setting isPlacedUnder to false
 isPlacedUnder = false;
 if (isPlacedUnder) {
  setPosition(divstyle,positionerImgName,true);
 } else {
  setPosition(divstyle,positionerImgName)
 }
}

function toggleDatePicker(eltName,formElt) {
  var x = formElt.indexOf('.');
  var formName = formElt.substring(0,x);
  var formEltName = formElt.substring(x+1);
  newCalendar(eltName,document.forms[formName].elements[formEltName+"_day"],document.forms[formName].elements[formEltName+"_month"],document.forms[formName].elements[formEltName+"_year"]);
  toggleVisible(eltName);
}




<!-- Begin
function moveMultiBox(fbox, tbox,ordered) {
        var arrFbox = new Array();
        var arrTbox = new Array();
        var arrLookup = new Array();
        var i;
        for (i = 0; i < tbox.options.length; i++) {
                arrLookup[tbox.options[i].text] = tbox.options[i].value;
                arrTbox[i] = tbox.options[i].text;
        }
        var fLength = 0;
        var tLength = arrTbox.length;
        for(i = 0; i < fbox.options.length; i++) {
                arrLookup[fbox.options[i].text] = fbox.options[i].value;
                if (fbox.options[i].selected && fbox.options[i].value != "") {
                        arrTbox[tLength] = fbox.options[i].text;
                        tLength++;
                }
                else {
                        arrFbox[fLength] = fbox.options[i].text;
                        fLength++;
                }
        }
        if(!ordered) {
		arrFbox.sort();
		arrTbox.sort();
        }
        fbox.length = 0;
        tbox.length = 0;
        var c;
        for(c = 0; c < arrFbox.length; c++) {
                var no = new Option();
                no.value = arrLookup[arrFbox[c]];
                if(no.value == "") {
                        no.disabled = "disabled";
                }
                no.text = arrFbox[c];
                fbox[c] = no;
        }

        for(c = 0; c < arrTbox.length; c++) {
                var no = new Option();

                no.value = arrLookup[arrTbox[c]];
                if(no.value == "") {
                        no.disabled = "disabled";
                }
                no.text = arrTbox[c];
                tbox[c] = no;
        }
}

function moveInsideMulti(tbox,direct) {

	var directmore;
	directmore = direct;

	if(direct > 0) {
	for (i = tbox.options.length-1; i > 0; i--) {

		if(tbox.options[i].selected && tbox.options[i+direct] && (i+direct) > 0 ) {

				tomouv_val = tbox.options[i].value;
				tomouv_txt = tbox.options[i].text;
				tbox.options[i].value = tbox.options[i+direct].value;
				tbox.options[i].text = tbox.options[i+direct].text;
				tbox.options[i].selected = false;
				tbox.options[i+direct].value = tomouv_val;
				tbox.options[i+direct].text = tomouv_txt;
				tbox.options[i+direct].selected = true;
				if(direct > 0) {
					i+=direct;
				}
		}
	}
	} else {
	for (i = 1; i < tbox.options.length; i++) {
		if(tbox.options[i].selected && tbox.options[i+direct] && (i+direct) > 0 ) {

				tomouv_val = tbox.options[i].value;
				tomouv_txt = tbox.options[i].text;
				tbox.options[i].value = tbox.options[i+direct].value;
				tbox.options[i].text = tbox.options[i+direct].text;
				tbox.options[i].selected = false;
				tbox.options[i+direct].value = tomouv_val;
				tbox.options[i+direct].text = tomouv_txt;
				tbox.options[i+direct].selected = true;
				if(direct > 0) {
					i+=direct;
				}
		}
	}
	}
	return false;

}

//  End -->

function doSubmitForm() {
        for(p in multiField) {
                for(z=0;z<(multiField[p].options.length);z++) {
                        multiField[p].options[z].selected = true;
                }
                multiField[p].readonly = "readonly";
                multiField[p].name = multiField[p].name+"[]";

        }
        //updateRTEs();

}

