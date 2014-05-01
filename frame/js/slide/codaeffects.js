// FancyZoom.js - v1.1 - http://www.fancyzoom.com
//
// Copyright (c) 2008 Cabel Sasser / Panic Inc
// All rights reserved.
// 
//     Requires: FancyZoomHTML.js
// Instructions: Include JS files in page, call setupZoom() in onLoad. That's it!
//               Any <a href> links to images will be updated to zoom inline.
//               Add rel="nozoom" to your <a href> to disable zooming for an image.
// 
// Redistribution and use of this effect in source form, with or without modification,
// are permitted provided that the following conditions are met:
// 
// * USE OF SOURCE ON COMMERCIAL (FOR-PROFIT) WEBSITE REQUIRES ONE-TIME LICENSE FEE PER DOMAIN.
//   Reasonably priced! Visit www.fancyzoom.com for licensing instructions. Thanks!
//
// * Non-commercial (personal) website use is permitted without license/payment!
//
// * Redistribution of source code must retain the above copyright notice,
//   this list of conditions and the following disclaimer.
//
// * Redistribution of source code and derived works cannot be sold without specific
//   written prior permission.
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
// "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
// LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
// A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
// CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
// EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
// PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
// PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
// LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
// NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
// SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

//ALTERED by Matthew Milner - for jQuery 2013.

var includeCaption = true; // Turn on the "caption" feature, and write out the caption HTML
var zoomTime       = 5;    // Milliseconds between frames of zoom animation
var zoomSteps      = 15;   // Number of zoom animation frames
var includeFade    = 1;    // Set to 1 to fade the image in / out as it zooms
var minBorder      = 90;   // Amount of padding between large, scaled down images, and the window edges
var shadowSettings = '0px 5px 25px rgba(0, 0, 0, '; // Blur, radius, color of shadow for compatible browsers

var zoomImagesURI   = '/images-global/zoom/'; // Location of the zoom and shadow images

// Init. Do not add anything below this line, unless it's something awesome.

var myWidth = 0, myHeight = 0, myScroll = 0; myScrollWidth = 0; myScrollHeight = 0;
var zoomOpen = false, preloadFrame = 1, preloadActive = false, preloadTime = 0, imgPreload = new Image();
var preloadAnimTimer = 0;

var zoomActive = new Array(); var zoomTimer  = new Array(); 
var zoomOrigW  = new Array(); var zoomOrigH  = new Array();
var zoomOrigX  = new Array(); var zoomOrigY  = new Array();

var zoomCaption    = "ZoomCaption";
var zoomCaptionDiv = "ZoomCapDiv";

if (navigator.userAgent.indexOf("MSIE") != -1) {
	var browserIsIE = true;
}

// Zoom: Setup The Page! Called in your <body>'s onLoad handler.


// Zoom: Inject Javascript functions into hrefs pointing to images, one by one!
// Skip any href that contains a rel="nozoom" tag.
// This is done at page load time via an onLoad() handler.

function prepZooms() {
	if (! document.getElementsByTagName) {
		return;
	}
	var links = document.getElementsByTagName("a");
	for (i = 0; i < links.length; i++) {
		if (links[i].getAttribute("href")) {
			if (links[i].getAttribute("href").search(/(.*)\.(jpg|jpeg|gif|png|bmp|tif|tiff)/gi) != -1) {
				if (links[i].getAttribute("rel") != "nozoom") {
					links[i].onclick = function (event) { return zoomClick(this, event); };
					links[i].onmouseover = function () { zoomPreload(this); };
				}
			}
		}
	}
}



// Utility: Math functions for animation calucations - From http://www.robertpenner.com/easing/
//
// t = time, b = begin, c = change, d = duration
// time = current frame, begin is fixed, change is basically finish - begin, duration is fixed (frames),

function linear(t, b, c, d){
	return c*t/d + b;
}

function sineInOut(t, b, c, d){
	return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
}

function cubicIn(t, b, c, d){
	return c*(t/=d)*t*t + b;
}

function cubicOut(t, b, c, d){
	return c*((t=t/d-1)*t*t + 1) + b;
}

function cubicInOut(t, b, c, d){
	if ((t/=d/2) < 1) return c/2*t*t*t + b;
	return c/2*((t-=2)*t*t + 2) + b;
}

function bounceOut(t, b, c, d){
	if ((t/=d) < (1/2.75)){
		return c*(7.5625*t*t) + b;
	} else if (t < (2/2.75)){
		return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
	} else if (t < (2.5/2.75)){
		return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
	} else {
		return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
	}
}


// Utility: Get the size of the window, and set myWidth and myHeight
// Credit to quirksmode.org

function getSize(){

	// Window Size

	if (self.innerHeight) { // Everyone but IE
		myWidth = window.innerWidth;
		myHeight = window.innerHeight;
		myScroll = window.pageYOffset;
	} else if (document.documentElement && document.documentElement.clientHeight) { // IE6 Strict
		myWidth = document.documentElement.clientWidth;
		myHeight = document.documentElement.clientHeight;
		myScroll = document.documentElement.scrollTop;
	} else if (document.body) { // Other IE, such as IE7
		myWidth = document.body.clientWidth;
		myHeight = document.body.clientHeight;
		myScroll = document.body.scrollTop;
	}

	// Page size w/offscreen areas

	if (window.innerHeight && window.scrollMaxY) {	
		myScrollWidth = document.body.scrollWidth;
		myScrollHeight = window.innerHeight + window.scrollMaxY;
	} else if (document.body.scrollHeight > document.body.offsetHeight) { // All but Explorer Mac
		myScrollWidth = document.body.scrollWidth;
		myScrollHeight = document.body.scrollHeight;
	} else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
		myScrollWidth = document.body.offsetWidth;
		myScrollHeight = document.body.offsetHeight;
	}
}

function arrowon(a){
if(a=="r"){
$('#rightscrollbutton').attr('src','/frame/images/slider/right-arrow-on.png');
}
if(a=="l"){
$('#leftscrollbutton').attr('src','/frame/images/slider/left-arrow-on.png');
}
}

function arrowoff(a){
	sectionNumber=currentSection.split("-")[0];
	totalSlides=countslides();
	if(a=="r"){
	if(sectionNumber==totalSlides){
$('rightscrollbutton').attr('src','/frame/images/slider/right-arrow-off.png');
}else{
$('rightscrollbutton').attr('src','/frame/images/slider/right-arrow.png');
}	
	}
	
	if(a=="l"){
	if(sectionNumber==0){
$('leftscrollbutton').attr('src','/frame/images/slider/left-arrow-off.png');
}else{
$('leftscrollbutton').attr('src','/frame/images/slider/left-arrow.png');
}
	}
}

// Utility: Get Shift Key Status
// IE events don't seem to get passed through the function, so grab it from the window.

// Utility: Find the Y position of an element on a page. Return Y and X as an array

function findElementPos(elemFind){
	/*
	do {
		elemX += $('#'+elemFind).offsetLeft;
		elemY += elemFind.offsetTop;
	} while ( elemFind = elemFind.offsetParent )*/
	
	var elemX = 0;
	var elemY = 0;
	var thiselem=$('#'+elemFind).offset();
	elemX=thiselem.left;
	elemY=thiselem.top;
	return Array(elemX, elemY);
}

//
// CodaEffects.js - (C) 2007 Panic, Inc.
//
// Used to scroll our tabbed view, display our download popup, and display the giant dialog.
// Requires: Effects.js
//
// Not for redistribution.

// UPDATES
// 8/12/2008 switched default loaded section from "sites-pane" to "new-pane" -- Tim
//

// Scroll: Setup Scrolling Stuff
var startSection="1-pane"
var currentSection = "1-pane"; // The default loaded section on the page
var tabTag = "-tab";
var paneTag = "-pane";

// Scroll the page manually to the position of element "link", passed to us.

function ScrollSection(link, scrollArea, offset){
	// Store the last section, and update the current section

	if (currentSection == link) {
		return;
	}
	lastSection = currentSection;
	currentSection = link;
	
	// Change the section highlight.
	// Extract the root section name, and use that to change the background image to 'top', revealing the alt. state

    sectionTab = currentSection.split("-")[0] + tabTag;
	sectionNumber=currentSection.split("-")[0];
    $('#'+sectionTab).addClass("slider-active");
    if (lastSection) {
	    lastTab = lastSection.split("-")[0] + tabTag;
	    $('#'+lastTab).addClass("slider-inactive");
	}
    
	// Get the element we want to scroll, get the position of the element to scroll to
	
	theScroll = scrollArea;
	position = findElementPos(link);

	// Get the position of the offset div -- the div at the far left.
	// This is the amount we compensate for when scrolling
	
	if (offset != "") {
		offsetPos = findElementPos(offset);
		position[0] = position[0] - offsetPos[0];
	}
	
	var thisscrollleft=$('#'+theScroll).scrollLeft();
	scrollStart(theScroll, thisscrollleft, position[0], "horiz");
	
	totalSlides=countslides();
	arrowon('r');
	arrowon('l');
	arrowoff('r');
	arrowoff('l');
	
	// return false;
}
var toolbarNames = new Array();
var toolbarElem;

function countslides(){
	toolbarElem = $('#slider-toolbar').children('.slider-inactive, .slider-active').each(function(item){
				 var thisid=$(this).attr('id');
		toolbarNames.push(thisid.split("-")[0]);																						   
});
	/*toolbarNames = new Array();
	if (toolbarElem.has()){
		var children = toolbarElem.children();
		
		for (var i = 0; i < children.length; i++){
			if (toolbarElem.childNodes[i].className == "slider-inactive" || toolbarElem.childNodes[i].className == "slider-active") {
				toolbarNames.push(toolbarElem.childNodes[i].id.split("-")[0]);
			}
		}
	}*/
	totalSlides=toolbarNames.length;
	return totalSlides;
}
// Scroll the page using the arrows

function ScrollArrow(direction, toolbar, scrollArea, offset){

	/*toolbarElem = $('#slider-toolbar');
	toolbarNames = new Array();
    
	// Find all the <li> elements in the toolbar, and extract their id's into an array.
    
	if (toolbarElem.hasChildNodes())
	{
		var children = toolbarElem.childNodes;
		for (var i = 0; i < children.length; i++) 
		{
			if (toolbarElem.childNodes[i].className == "slider-inactive" || toolbarElem.childNodes[i].className == "slider-active") {
				toolbarNames.push(toolbarElem.childNodes[i].id.split("-")[0]);
			}
		}
	}*/
		toolbarElem = $('#slider-toolbar').children('.slider-inactive, .slider-active').each(function(item){
				 var thisid=$(this).attr('id');
		toolbarNames.push(thisid.split("-")[0]);																						   
});

	// Now iterate through our array of tab names, find matches, and determine where to go.

	for (var i = 0; i < toolbarNames.length; i++) {
		if (toolbarNames[i] == currentSection.split("-")[0]){
			if (direction == "left") {
				if (i - 1 < 0) {
					gotoTab = toolbarNames[toolbarNames.length - 1];
				} else {
					gotoTab = toolbarNames[i - 1];
				}
			} else {
				if ((i + 1) > (toolbarNames.length - 1)){
					gotoTab = toolbarNames[0];
				} else {
					gotoTab = toolbarNames[i + 1];
				}
			}
		}
	}
	// Go to the section name!
	ScrollSection(gotoTab+paneTag, scrollArea, offset);
	}

//
// Animated Scroll Functions
// Scrolls are synchronous -- only one at a time.
//

var scrollanim = {time:0, begin:0, change:0.0, duration:0.0, element:null, timer:null};

function scrollStart(elem, start, end, direction){
	//console.log("scrollStart from "+start+" to "+end+" in direction "+direction);

	if (scrollanim.timer != null) {
		clearInterval(scrollanim.timer);
		scrollanim.timer = null;
	}
	scrollanim.time = 0;
	scrollanim.begin = start;
	scrollanim.change = end - start;
	scrollanim.duration = 25;
	scrollanim.element = $('#'+elem);
	
	if (direction == "horiz") {
		scrollanim.timer = setInterval("scrollHorizAnim();", 15);
	}
	else {
		scrollanim.timer = setInterval("scrollVertAnim();", 15);
	}
}

function scrollVertAnim(){
	if (scrollanim.time > scrollanim.duration) {
		clearInterval(scrollanim.timer);
		scrollanim.timer = null;
	}
	else {
		move = sineInOut(scrollanim.time, scrollanim.begin, scrollanim.change, scrollanim.duration);
		scrollanim.element.scrollTop(move); 
		scrollanim.time++;
	}
}

function scrollHorizAnim(){
	if (scrollanim.time > scrollanim.duration) {
		clearInterval(scrollanim.timer);
		scrollanim.timer = null;
	}
	else {
		
		move = sineInOut(scrollanim.time, scrollanim.begin, scrollanim.change, scrollanim.duration);
		scrollanim.element.scrollLeft(move);
		scrollanim.time++;
	}
}


//
// MOVE: Animate the move of an element.
//
// Move is also synchronous. One at a time, please.
//

var moveanim = {time:0, beginX:0, changeX:0.0, beginY:0, changeY:0, duration:0.0, element:null, timer:null};

function moveStart(elem, startX, endX, startY, endY, duration){
	if (moveanim.timer != null) {
		clearInterval(moveanim.timer);
		moveanim.timer = null;
	}
	moveanim.time = 0;
	moveanim.beginX = startX;
	moveanim.changeX = endX - startX;
	moveanim.beginY = startY;
	moveanim.changeY = endY - startY;
	moveanim.duration = duration;
	moveanim.element = elem;

	moveanim.timer = setInterval("moveAnimDo();", 15);
}

function moveAnimDo(){
	if (moveanim.time > moveanim.duration) {
		clearInterval(moveanim.timer);
		moveanim.timer = null;
	}
	else {
		moveX = cubicOut(moveanim.time, moveanim.beginX, moveanim.changeX, moveanim.duration);
		moveY = cubicOut(moveanim.time, moveanim.beginY, moveanim.changeY, moveanim.duration);
		moveanim.element.style.left = moveX + "px";
		moveanim.element.style.top = moveY + "px";
		moveanim.time++;
	}
}


//console.log("Initialized");