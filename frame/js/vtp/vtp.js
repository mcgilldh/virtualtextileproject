//SITE WIDE JS
$.ajaxSetup({
   type: "POST",
   cache: false
});

/*
$(document).ajaxStart(function() { 
        $('#ajaxloader').show(); 
    }).ajaxStop(function() { 
        $('#ajaxloader').hide(); 
    });*/
	
//On document ready
$(function(){
$("img.rollover").hover(
function() { this.src = this.src.replace("_off", "_on");
},
function() { this.src = this.src.replace("_on", "_off");
});
$('.popmodal').magnificPopup({
	  type: 'ajax'
}); //make all popmodal links operable


$('#sideaccordion').accordion({//make side accordion menus automatically
    heightStyle: "content",
	collapsible: true,
	active: false
    });
$('.tabpanels').tabs();//make tabs automatically
});

//When someone scrolls
$(window).scroll(function (){ 

});

//When the window is resized
$(window).resize(function() {

});

function clearvalue(id){
	$('#'+id).val('');
}

function showsection(info){
$('section').hide();
$('#'+info).show();
}