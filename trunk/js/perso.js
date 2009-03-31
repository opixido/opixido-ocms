

$(document).ready(function(){
 $('#espace_perso a[rel=confirm]').click(function () {
 	
 	var res = confirm(this.title);
 	if(res) {
 		$.post(this.href,{ ajax: "1" }); 		
 		$(this).closest('div[rel=removable]').hide('slow'); 		
 	}
 	return false;
 	
 });

  
	reInitArrows();
    
 
});

/*
$(function() {
	// here, we allow the user to sort the items
	$('#sort_video').sortable({
		axis: "y",
		placeholder: 'ui-state-highlight'

	});
 
	// here, we reload the saved order

});
*/

function reInitArrows() {
	
	if(!gid('concert_videos')) {
		return;
	}

  $('#sort_video a.moveup_off').removeClass('moveup_off').addClass('moveup');
  $('#sort_video a.movedown_off').removeClass('movedown_off').addClass('movedown');
  
  $('#sort_video a.moveup').first().removeClass('moveup').addClass('moveup_off');
  $('#sort_video a.movedown').last().removeClass('movedown').addClass('movedown_off');
  
	 
  $('#sort_video a.moveup').bind('click',
  	function () {
  		moveup(this);
  	}
  );
  
  $('#sort_video a.movedown').bind('click',
  	function () {
  		movedown(this);
  	}
  );  
  
  $('#sort_video a.remove').bind('click',
  	function () {
  		removevideo(this);
  	}
  );  
  
  gid('concert_videos').value = '';
  
  $('#sort_video li').each(function(){gid('concert_videos').value +=","+$(this).attr('rel')});

  
/*
  $('#sort_video a.moveup_off').each(function() {$(this).toggleClass('moveup')});
  $('#sort_video a.movedown_off').each(function() {$(this).toggleClass('movedown')});
 */


 
}

jQuery.fn.first = function() { return this.eq(0) };
jQuery.fn.last = function() { return this.eq(this.size() - 1) };


function moveup(obj) {
	
	myLi = ($(obj).closest('LI'));
	previousLi = myLi.prev();
	if(!previousLi[0]) {
		return;
	}
	
	myClone = myLi.clone();
	myClone.insertBefore(previousLi);
//	myClone.hide();
//	myLi.hide('fast',function() {$(this).remove();reInitArrows()});
//	myClone.show('fast');
	myLi.remove();
	reInitArrows();
	return;
	
	myClone.hide();

	previousLi.animate({'top':(previousLi.height()+25)+"px"},'normal');
	myLi.animate({'top':-(previousLi.height()+25)+"px"},'normal',function(){myClone.show();myLi.hide();myLi.remove();previousLi.css('top',0);reInitArrows();});

	
	
}

function movedown(obj) {
	//alert(obj);
	myLi = ($(obj).closest('LI'));
	nextLi = myLi.next();
	
	if(!nextLi[0]) {
		return;
	}
		
	myClone = myLi.clone();
	myClone.insertAfter(nextLi);
	//myClone.hide();
	//myLi.hide('fast',function() {$(this).remove();reInitArrows();});
	//myClone.show('fast');
	myLi.remove();
	reInitArrows();
	return;
	
	nextLi.animate({'top':-(nextLi.height()+25)+"px"},'normal');
	myLi.animate({'top':(nextLi.height()+25)+"px"},'normal',function(){myClone.show();myLi.hide();myLi.remove();nextLi.css('top',0);reInitArrows();});
	
}

function removevideo(obj) {
	
	$(obj).closest('LI').hide('fast',function() {$(this).remove();reInitArrows();});
	
}