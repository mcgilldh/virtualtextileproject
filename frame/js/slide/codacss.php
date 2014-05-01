<?php
header("Content-Type: text/css");
?>
/* The slide-y content box thingy */
#slider-toolbarcontainer{
<?php switch($_GET['s']){
case "f":?>
width:930px;
<?php break;
	case "200":?>
    width:220px;
    <?php break;
	case "sq3":
	case "s":
	case "bookshelf":?>
    width:710px;
	<?php break;
	case "sq2":?>
    width:420px;
	<?php break;
	case "sq":?>
    width:170px;
	<?php break;
	}?>
padding:0px;
margin:0px auto;
background:url('/frame/images/trans/white-trans-25.png');border-bottom:solid 1px #cdcdcd;
}

#slider{
<?php switch($_GET['s']){
case "f":?>
	width: 930px;
<?php break;
	case "200":?>
    width:220px;
    <?php break;
	case "s":
	case "sq3":
	case "bookshelf":?>
    width:710px;
	<?php break;
	case "sq2":?>
    width:420px;
	<?php break;
	case "sq":?>
    width:170px;
	<?php break;
	}?>
	margin:0 0 20px 0;
	padding:0px;
}

#slider-frame, #slider-frame div.slider-button, #scroller { height: auto; }

#slider-frame {
	overflow: hidden;
	margin: 0 auto;
    <?php switch($_GET['s']){
case "f":?>
	width: 930px;
    <?php break;
	case "200":?>
    width:220px;
	<?php break;
	case "s":
	case "sq3":
	case "bookshelf":?>
    width:710px;
	<?php break;
	case "sq2":?>
    width:420px;
	<?php break;
	case "sq":?>
    width:220px;
	<?php break;
	}?>
	position: relative;
	}
	
#slider-frame div.slider-button {
	padding:0px;
	margin:0px;
    <?php switch($_GET['s']){
case "f":
case "s":?>
	height:500px;
    width:40px;
    <?php break;
	case "200":?>
    height:300px;
    width:20px;
	<?php break;
	case "sq3":
	case "sq2":
	case "sq":
	case "bookshelf":?>
    height:180px;
    width:20px;
	<?php break;
	}?>
	float:left;
		cursor:pointer;
	}
	
#slider-frame div.slider-button:hover {
background:url('/frame/images/trans/blue-trans-new.png');
	}
	
#slider-frame div.slider-button img {
	margin:<?php switch($_GET['s']){
case "f":
case "s":?>220px
    <?php break;
	case "200":?>
    130px
    <?php break;
	case "sq3":
	case "sq2":
	case "sq":
	case "bookshelf":?>
    75px
	<?php break;
}?> 5px 0px 5px;
	max-width:30px;
	}
	
div.slider-button#left { left: 0; }
div.slider-button#right { right: 0; }

#scroller {
<?php switch($_GET['s']){
case "f":?>
	width: 850px;
    height:500px;
    <?php break;
	case "s":?>
	width: 630px;
    height:500px;
    <?php break;
	case "200":?>
    width: 180px;
    height:300px;
	<?php break;
	case "sq3":
	case "bookshelf":?>
	width: 630px;
    height:180px;
	<?php break;
	case "sq2":?>
	width: 340px;
    height:180px;
	<?php break;
	case "sq":?>
    height:180px;
    width:170px;
	<?php break;
	}?>
	margin: 0 auto;
	background: transparent;
	overflow: hidden;
	float:left;
	}

#slider-content {
	min-width: 20000px;
	}
	
.slider-section {
<?php switch($_GET['s']){
case "f":?>
	width: 810px;
    	height:460px;
        margin: 20px;
        <?php break;
		case "s":?>
	width: 590px;
    	height:460px;
        margin: 20px;
        <?php break;
	case "200":?>
    width: 170px;
    	height:300px;
       margin: 5px;
	<?php break;
	case "sq3":?>
    width: 200px;
    height:180px;
       margin: 5px;
	<?php break;
	case "sq2":
	case "sq":	?>
    width: 170px;
    height:180px;
       margin: 5px;
	<?php break;
	case "bookshelf":	?>
    width: 20px;
    height:180px;
    margin: 0px;
	<?php break;
	}?>
	float: left;
	text-align:left;
	}

.slider-section.border {
padding:4px;
border:solid 1px transparent;
margin:2px;
<?php switch($_GET['s']){
case "f":
case "s":?>
height:454px;
    <?php break;
	case "200":?>
    	height:294px;
	<?php break;
	case "sq3":
	case "sq2":
	case "sq":
	case "bookshelf":	?>
    height:164px;
	<?php break;
	}?>
}

.slider-section.border:hover {
color:#000;
border:solid 1px #282828;
background:url('/frame/images/trans/white-trans-25.png');
}
	
.slider-section h2, .slider-section h3 {
    color: #072453;
	font-weight: bold;
	font-family: "HelveticaNeue", Helvetica, Arial, sans-serif;
	font-size: 1.3em;
	line-height: 1.2em;
    letter-spacing:0.2em;
	margin-bottom: 5px;
	}

.slider-section h1 {
    color: #072453;
	font-weight: bold;
	font-family: "HelveticaNeue", Helvetica, Arial, sans-serif;
	font-size: 1.8em;
	line-height: 1.2em;
    letter-spacing:0.2em;
	}
	
.slider-section h1.dark, .slider-section h2.dark, .slider-section h3.dark {
	color: #000;
	}
.slider-section h1.light, .slider-section h2.light, .slider-section h3.light {
	color: #efe;
	}  
	

.slider-section img {
	float: left;
    <?php switch($_GET['s']){
case "f":
case "s":?>
	margin-right: 20px;
	margin-bottom: 20px;
        <?php break;
	case "200":
	case "sq3":
	case "sq2":
	case "sq":?>
	margin-right: 2px;
	margin-bottom: 2px;
	<?php break;
	case "bookshelf":?>
	margin-right: 0px;
	margin-bottom: 0px;
	<?php break;
	}?>   
	}

.slider-fullbox{
<?php switch($_GET['s']){
case "f":?>
	width: 810px;
    height:420px;
    <?php break;
	case "s":?>
	width: 630px;
    height:420px;
    <?php break;
	case "200":?>
    width: 170px;
    height:260px;
	<?php break;
	case "sq3":?>
    width: 200px;
    height:140px;
	<?php break;
	case "sq2":
	case "sq":	?>
    width: 170px;
    height:140px;
	<?php break;
	case "bookshelf":	?>
    width:20px;
    height:140px;
	<?php break;
	}?>
    margin: 0px;
    padding:0px;
}

.slider-bottombox{
<?php switch($_GET['s']){
case "f":?>
	width: 810px;
    <?php break;
	case "s":?>
	width: 630px;
    <?php break;
	case "200":?>
    width: 170px;
	<?php break;
	case "sq3":?>
    width: 200px;
	<?php break;
	case "sq2":
	case "sq":	?>
    width: 170px;
	<?php break;
	case "bookshelf":	?>
    width:20px;
	<?php break;
	}?>
    height:30px;
    margin:10px 0 0 0;
    padding:0px;
}

    #leftscrollbutton, #rightscrollbutton{
        <?php switch($_GET['s']){
case "f":
case "s":?>
    max-height:60px;
        <?php break;
	case "200":
	case "sq3":
	case "sq2":
	case "sq":
	case "bookshelf":?>
    max-height:20px;
	<?php break;
	}?>
    }
	
/* Sidemenu */
#sideBar{
text-align:left;
}

#sideBar h2{
	color:#FFFFFF;
	font-size:110%;
	font-family:arial;
	margin:10px 10px 10px 10px;
	font-weight:bold !important;
}

#sideBar h2 span{
	font-size:125%;
	font-weight:normal !important;
}

#sideBar ul{
	margin:0px 0px 0px 0px;
	padding:0px 0px 0px 0px;
}

#sideBar li{
	margin:0px 10px 3px 10px;
	padding:2px;
	list-style-type:none;
	display:block;
	background-color:#DA1074;
	width:177px;
	color:#FFFFFF;
}

#sideBar li a{
	width:100%;
}

#sideBar li a:link,
#sideBar li a:visited{
	color:#FFFFFF;
	font-family:verdana;
	font-size:100%;
	text-decoration:none;
	display:block;
	margin:0px 0px 0px 0px;
	padding:0px;
	width:100%;
}

#sideBar li a:hover{
	color:#FFFFFF;
	text-decoration:underline;
}

#sideBar{
	position: absolute;
	width: auto;
	height: auto;
	top: 140px;
	right:0px;
	background:url('/frame/images/side/background.gif') top left repeat-y;
background-color:transparent;
}

#sideBarTab{
	float:left;
	height:137px;
	width:28px;
	}

#sideBarTab img{
	border:0px solid #FFFFFF;
}

#sideBarContents{
	float:left;
	overflow:hidden !important;
	width:200px;
	height:320px;
}

#sideBarContentsInner{
	width:200px;
}

/* The toolbar for the content box */

#slider-toolbar {
<?php switch($_GET['s']){
case "f":?>
	width: 930px;
     <?php break;
	case "200":?>
    	width: 220px;
	<?php break;
	case "s":
	case "sq3":
	case "bookshelf":?>
    width:620px;
	<?php break;
	case "sq2":?>
    width:420px;
	<?php break;
	case "sq":?>
    width:220px;
	<?php break;
	}?>
    list-style: none;margin: 0px 60px;padding:0px;
    clear: both;
}
	
#slider-toolbar li {
line-height:18px;
	display:inline;
	float:left;
	padding:2px 6px;
	margin:0px;
	font-size:1em;
	}

#slider-toolbar li.slider-header{
background-color:transparent;
color:#000;
border:none;
	}	
	
#slider-toolbar li.slider-divider{
	display:inline;
	padding:0px;
	margin:0px;
	border:none;
	background:#282828;
	width:1px;
}

#slider-toolbar li.slider-divider img{
width:1px;
height:18px;
}

#slider-toolbar li.slider-active{
cursor:pointer;
background-color:#fff;
color:#000;
border:none;
}

#slider-toolbar li.slider-inactive {
background-color:transparent;
color:#787878;
border:none;
cursor:pointer;
	}

#slider-toolbar li.slider-inactive:hover {
background-color:#dedede;
color:#000;
border:none;
	}