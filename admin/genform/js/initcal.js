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
  newCalendar(eltName,gid('genform_formulaire').elements[formEltName+"_day"],gid('genform_formulaire').elements[formEltName+"_month"],gid('genform_formulaire').elements[formEltName+"_year"]);
  toggleVisible(eltName);
}


//  End -->
