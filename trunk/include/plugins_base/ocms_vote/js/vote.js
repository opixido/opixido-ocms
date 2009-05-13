function vote(obj) {
	var d = new Date();
	obj.parentNode.id = "votebloc_"+d.getTime();
	XHR(obj.href+"&jsvote=1","",obj.parentNode.id,'hideVote("'+obj.parentNode.id+'")');
	obj.parentNode.innerHTML = "Enregistrement !";

	return false;
	
}
function openVote(obj) {
	var ob = obj.parentNode.getElementsByTagName('div')[0];
	showhide(ob);
	return false;
}

function hideVote(obj) {
	setTimeout ( 'doHideVote("'+obj+'")', 2000 );

}

function doHideVote(obj) {
	showhide(obj);
}


