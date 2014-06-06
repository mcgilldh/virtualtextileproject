<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $cms_url; ?>/frame/js/jqueryui/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo $cms_url; ?>/frame/js/jquery-timepicker/jquery.ui.timepicker.js"></script>
<script type="text/javascript" src="<?php echo $cms_url; ?>/frame/js/isotope/jquery.isotope.min.js"></script>
<script type="text/javascript" src="<?php echo $cms_url; ?>/frame/js/Magnific-Popup/jquery.magnific-popup.min.js"></script>
<?php //autocomplete library?>
<script type="text/javascript" src="<?php echo $cms_url; ?>/frame/js/loopj-jquery-tokeninput/src/jquery.tokeninput.js"></script>
<script type="text/javascript">
$.ajaxSetup({
		   type: "POST",
		   cache: false
		});
		$(document).ajaxStart(function() {
		        $('#ajaxloader').show();
		    }).ajaxStop(function() {
		        $('#ajaxloader').hide();
		    });
		//On document ready
		$(function(){
		$("input, #defaultdatamsg, .tooltip").tooltip();
		$("img.rollover").hover(
		function() { this.src = this.src.replace("_off", "_on");
		},
		function() { this.src = this.src.replace("_on", "_off");
		});
		$('.popmodal').magnificPopup({
			  type: 'ajax'
		});


		$('#sideaccordion').accordion({//make side accordion menus automatically
		    heightStyle: "content",
			collapsible: true,
			active: false
		    });
			$('#maincontentpanels').tabs();//make tabs automatically
			$('.tabs').tabs();//make tabs automatically
	});


function makedatepick(){
	$(".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd'});
}

function maketimepick(){
	$(".timepicker" ).timepicker();
}


/************************************isotope calls*******************************
 * need revising, and tightening up for VTP
 */

var collectiongrabstart=0;
//Workbench functions
function grabcollection(limit,start,id,term){
/*
limit is number of items returned
start where the query starts
id is types of items - all,textile
term = keyword
*/
limit = limit || "100";
start = start || "0";
id = id || "all";
term = term || "";

$.ajax({
url: 'http://www.virtualtextileproject.org/cms/collection/data.php',
data: {
l: limit,
s: start,
id: id,
t: term
}
}).always(function(data){
if(start=="0"){
$('#collectioncontainer').html(data);
grabcollectionitems();
}else{
addcollectionitems(data);
}
collectiongrabstart=collectiongrabstart+parseInt(limit);
});

}

function addcollectionitems(d,id){
id = id || "";
var $newItems = $(d);
$('#collectioncontainer'+id).isotope('insert', $newItems);
$('#collectioncontainer'+id).isotope({ filter: '' });
$('#collectioncontainer'+id).isotope('reLayout');
//$('#collectioncontainer'+id).css({'overflow' : 'auto','height':'500px'});
$('.popmodal').magnificPopup({
	  type: 'ajax'
});
}

function updatewithterm(i,n){
var $container = $('#collectioncontainer');
if($.isFunction($container.isotope)){
$container.isotope('destroy');
$container.isotope = false;
  }
var kw=$('#keywordterm').val();
$('#collectioncontainer').load('http://www.virtualtextileproject.org/cms/collection/data.php',{
t: kw,
q: i,
l: n
}, function(){
grabcollectionitems();
});
}

function grabrecent(limit,start,id,term){
var $container = $('#collectioncontainerrecent');
	if($.isFunction($container.isotope)){
$container.isotope('destroy');
$container.isotope = false;
  }
/*
limit is number of items returned
start where the query starts
draggable is yes / no for makedraggable
query is types of items - all,things,people,places,journals,organizations,events
term is term - ie keyword searching
project is project - ie limit to project
*/
limit = limit || "100";
start = start || "0";
id = id || "all";
term = term || "";

$.ajax({
url: 'http://www.virtualtextileproject.org/cms/collection/data.php',
data: {
	l: limit,
	s: start,
	id: id,
	t: term
}
}).always(function(data){
$('#collectioncontainerrecent').html(data);
var $container = $('#collectioncontainerrecent'),
      filters = {};
    // add randomish size classes
    $container.find('.element').each(function(){
      var $this = $(this),
          number = parseInt( $this.find('.number').text(), 10 );
      if ( number % 7 % 2 === 1 ) {
        $this.addClass('width2');
      }
      if ( number % 3 === 0 ) {
        $this.addClass('height2');
      }
    });

  $container.isotope({
    itemSelector : '.element',
    masonry : {
      columnWidth : 110
    },
    getSortData : {
      category : function( $elem ) {
        return $elem.attr('data-category');
      },
      sortdate : function( $elem ) {
        return $elem.attr('data-sortdate');
      },
		sortinfo : function ( $elem ) {
        return $elem.attr('data-sortinfo');
      },
      info : function ( $elem ) {
        return $elem.find('.info').text();
      }
    }
  });
	//$('#collectioncontainerrecent').css({'overflow' : 'auto','height':'500px'});
});
}


function grabcollectionitems(id){
  id = id || "";
var $container = $('#collectioncontainer'+id),
      filters = {};
    // add randomish size classes
    $container.find('.element').each(function(){
      var $this = $(this),
          number = parseInt( $this.find('.number').text(), 10 );
      if ( number % 7 % 2 === 1 ) {
        $this.addClass('width2');
      }
      if ( number % 3 === 0 ) {
        $this.addClass('height2');
      }
    });

  $container.isotope({
    itemSelector : '.element',
    masonry : {
      columnWidth : 100
    },
    getSortData : {
      category : function( $elem ) {
        return $elem.attr('data-category');
      },
      sortdate : function( $elem ) {
        return $elem.attr('data-sortdate');
      },
		sortinfo : function ( $elem ) {
        return $elem.attr('data-sortinfo');
      },
      info : function ( $elem ) {
        return $elem.find('.info').text();
      }
    }
  });

  // filter buttons
  $('.filter a').click(function(){
    var $this = $(this);
    // don't proceed if already selected
    if ( $this.hasClass('selected') ) {
      return;
    }

    var $optionSet = $this.parents('.option-set');
    // change selected class
    $optionSet.find('.selected').removeClass('selected');
    $this.addClass('selected');

    // store filter value in object
    // i.e. filters.datacollection = 'saved'
    var group = $optionSet.attr('data-filter-group');
    filters[ group ] = $this.attr('data-filter-value');
    // convert object into array
    var isoFilters = [];
    for ( var prop in filters ) {
      isoFilters.push( filters[ prop ] )
    }
    var selector = isoFilters.join('');
    $container.isotope({ filter: selector });
	//$('#collectioncontainer'+id).css({'overflow' : 'auto','height':'500px'});
    return false;
  });

var $optionSets = $('#options .option-set'),
        $optionLinks = $optionSets.find('a');

    $optionLinks.click(function(){
      var $this = $(this);
      // don't proceed if already selected
      if ( $this.hasClass('selected') ) {
        return false;
      }
      var $optionSet = $this.parents('.option-set');
      $optionSet.find('.selected').removeClass('selected');
      $this.addClass('selected');

      // make option object dynamically, i.e. { filter: '.my-filter-class' }
      var options = {},
          key = $optionSet.attr('data-option-key'),
          value = $this.attr('data-option-value');
      // parse 'false' as false boolean
      value = value === 'false' ? false : value;
      options[ key ] = value;

        $container.isotope( options );
      //$('#collectioncontainer'+id).css({'overflow' : 'auto','height':'500px'});
      return false;
    });


  var $sortBy = $('#sort-by');
  $('#shuffle a').click(function(){
    $container.isotope('shuffle');
    $sortBy.find('.selected').removeClass('selected');
    $sortBy.find('[data-option-value="random"]').addClass('selected');
	  //$('#collectioncontainer'+id).css({'overflow' : 'auto','height':'500px'});
    return false;
  });
	//$('#collectioncontainer'+id).css({'overflow' : 'auto','height':'500px'});
	//$('#collectioncontainer'+id).jScrollPane({showArrows: true,verticalGutter: 30,verticalDragMinHeight: 30,verticalDragMaxHeight: 60});
  $('.popmodal').magnificPopup({
	  type: 'ajax'
});
}
</script>
