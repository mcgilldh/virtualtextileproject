<?php /*page handling form for adds and edits
* needs text-diff fix
*
*/

//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
define("ABSPATH", dirname(__FILE__) . '/');
include (ABSPATH . "/../../includes/phpheader.php");
$thisabspath=ABSPATH;
header("Expires: Mon, 01 Jul 2003 00:00:00 GMT");
// Past date
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
// HTTP/1.1
header("Pragma: no-cache");
// NO CACHE
// if($_SESSION['user']){
?><div id="modalcontent"  class="w800"><?php


switch($_GET['j']){
	case "a":
		$job='Add';
		if($_GET['id']){
$newpage = 0;
print "<h2>Add New Page</h2>";
}else{
$newpage = 1;
print "<h2>Add Version to this Page</h2>";
$thisrev = chkpage($_GET['id']);
}

		break;
	case "e":
		$job='Edit';
		print "<h2>Edit Current Version</h2>";
		$thisrev = chkpage($_GET['id']);
		break;
}
?>
<script>
	function savenewpagecontents(){
		$('#pageadmin').load('<?php echo $cms_url; ?>/cms/page/update.php?j=<?php print $_GET['j'];?>', $('#editthispageform').serialize());
	}
	$(function() {
		$('#pageadmin').tabs();
	});
	//fix problem with javascript injection tags on load etc - reconcile with $(function()... below
	function fixscripttags() {
		var fixtext = $('#pagecontents').val();
		var fix1 = fixtext.replace(/scr@%ipt/g, "script");
		fix1 = fix1.replace(/text@%area/g, "textarea");
		$('#pagecontents').val(fix1);
		var fixltext = $('#pageleft').val();
		var fixl1 = fixltext.replace(/scr@%ipt/g, "script");
		fixl1 = fixl1.replace(/text@%area/g, "textarea");
		$('#pageleft').val(fixl1);
	}
</script>
<?php /*
 *
 * Add jQueryUI tabs for headers/content/user access/etc.
 *
 * popmodal
 *
 * make login menu
 */
$pageparts = "";
if ($_REQUEST['j'] == "e") {
//must use page unique, or else difficult controlling editing!
	$thispage = getpageparts($_GET['p']);
}
//print_r($thispage);
?>
<script>
	$(function() {
		var thispage=$('#pagecontents').val();
		thispage=thispage.replace(/<text@%area/g,"<textarea");
		thispage=thispage.replace(/<scr@%ipt/g,"<script");
		thispage=thispage.replace(/<\/text@%area/g,"</textarea");
		thispage=thispage.replace(/<\/scr@%ipt/g,"</script");
		$('#pagecontents').val(thispage);
	});
</script>
<div id="pageadmin" class="p100">
	<form name="editthispageform" id="editthispageform"
	method="post" action="<?php echo $cms_url; ?>/cms/page/update.php">
	<table id="managepageheader">
	<tr>
	<td><label for="pagetitle">Title:</label></td>
					<td><input type="text" class="text" id="pagetitle" name="pagetitle"
					value="<?php
					if (!empty($thispage)) {
						print stripslashes($thispage['contenttitle']);
					}
					?>" /></td>
					<td><select name="pageaccess" id="pageaccess" class="text">
				<option value="" <?php if ($thispage['contentfor'] == 'NULL') echo "selected";?>>Open to all</option>
				<option value="1" <?php if ($thispage['contentfor'] == 1) echo "selected";?>>Users only</option>
				<option value="2" <?php if ($thispage['contentfor'] == 2) echo "selected";?>>Community Members only</option>
				<?php if($_SESSION['profile']>=3){?>
				<option value="3" <?php if ($thispage['contentfor'] == 3) echo "selected";?>>Academic Members only</option>
				<?php } ?>
				<?php if($_SESSION['profile']>=4){?>
				<option value="4" <?php if ($thispage['contentfor'] == 4) echo "selected";?>>Project Members only</option>
				<?php } ?>
				<?php if($_SESSION['profile']>=5){?>
				<option value="5" <?php if ($thispage['contentfor'] == 5) echo "selected";?>>Project Admins only</option>
				<?php } ?>
				<?php if($_SESSION['profile']>=6){?>
				<option value="6" <?php if ($thispage['contentfor'] == 6) echo "selected";?>>Site Admins only</option>
				<?php } ?>
				<?php if($_SESSION['profile']>=7){?>
				<option value="7" <?php if ($thispage['contentfor'] == 7) echo "selected";?>>Master Admin only</option>
				<?php } ?>
			</select></td></tr>
			<tr><td colspan="2"> <?php echo $cms_url;?>/</td>
			<td><input type="text" class="text w300" name="pageurl" value="<?php
					if (!empty($thispage)) {
						print stripslashes($thispage['contenturl']);
					}
					?>" /></td></tr>
					<tr><td colspan="3"><input type="button" value="save" class="makebutton"
	onclick="savenewpagecontents(); return false;" /><a class="popmodal makebutton" href="/cms/page/manage.php?id=<?php print $_GET['id'];?>">go back to manage this page</a></td></tr></table>
	<ul>
		<li>
			<a href="#content-tab">Content</a>
		</li>
		<li>
			<a href="#header-tab">Header</a>
		</li>
		<li>
			<a href="#css-tab">Main CSS</a>
		</li>
		<li>
			<a href="#template-tab">Layout Templates</a>
		</li>
		<li><a href="#lastversion">Compare</a></li>
		<li><a href="#aboutpageeditor">About</a></li>
	</ul>
		<div id="content-tab" class="clear-block">
			<table>
				<tr>
					<td><textarea type="text" class="text p100 h400" id="pagecontents"
					name="pagecontents"><?php
						if ($_GET['j'] == "e")
							echo htmlspecialchars($thispage['content']);?></textarea></td>
				</tr>
				<tr>
					<td><label for="pagetags">Tags:</label>
					<br />
					<textarea type="text" class="text p100 h100" id="pagetags"><?php
						if ($_GET['j'] == "e")
							foreach ($thispage['contenttags'] as $tag) echo $tag.",";?></textarea></td>
				</tr>
			</table>
		</div>

		<div id="header-tab" class="clear-block">
			<table>
				<td><label for="pagecss">Inline CSS</label>
				<br />
				<textarea type="text" class="text p100 h150" id="pagecss" name="pagecss"><?php
					if ($_GET['j'] == "e")
						echo $thispage['contentcss'];?></textarea></td>
				</tr>
				<tr>
					<td><label for="pagecssfile">Linked CSS Files - Comma Delimited Relative Urls</label>
					</td></tr>
					<tr><td>
					<textarea type="text" name="pagecssfiles" id="pagecssfiles" class="p100 h50"><?php if ($_GET['j'] == "e") echo $thispage['contentcssfiles'];?></textarea>
					</td>
				</tr>
				<tr>
					<td><label for="pagejs">Inline Javascript</label>
					<br />
					<textarea type="text" id="pagejs" class="p100 h150" name="pagejs"><?php
						if ($_GET['j'] == "e")
							echo $thispage['contentjs'];?></textarea></td>
				</tr>
				<tr>
					<td><label for="pagejsfile">Linked Javascript Files - Comma Delimited Relative Urls</label></td>
					<tr><td>
					<textarea type="text" name="pagejsfiles" class="p100 h50" id="pagejsfiles"><?php if ($_GET['j'] == "e") echo $thispage['contentjsfiles']; ?></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="2">
				</tr>
			</table>
		</div>
		<div id="css-tab">
		<div id="csscontainer" class="h550 verticalscroll">
		<pre><code><?php include ABSPATH . '/../../frame/css/main.css';?></code></pre>
		</div>
		</div>
		<div id="template-tab">
		<div id="templatecontainer" class="h550 verticalscroll">
		<pre><code><?php $templates=file_get_contents('templates.tmpl');
		print htmlspecialchars($templates);?></code></pre>
		</div>
		</div>
		<div id="lastversion">
		<div id="lastversioncontainer" class="h550 verticalscroll">
		<?php $grablast=mysql_query("select * from cms_contents_history where content_unique=".$_GET['p']." order by content_timestamp desc limit 0,1");
		if(mysql_num_rows($grablast)){
$lastrevision=mysql_fetch_assoc($grablast);

require_once('/Users/virtualtextileproject/Sites/includes/Text/Diff.php');
require_once('/Users/virtualtextileproject/Sites/includes/Text/Diff/Renderer/unified.php');

$lines1[]=$thispage['content'];
$lines2[]=$lastrevision['content'];


$diff     = new Text_Diff('auto', array($lines1, $lines2));
$renderer = new Text_Diff_Renderer_unified();
echo $renderer->render($diff);

		}
		?>

		</div>
		</div>
		<div id="aboutpageeditor">
		<div id="aboutpageeditorcontainer" class="h550 verticalscroll">
		<h2>About</h2>
		<p>This editor stores seven main page 'areas' in order to build pages:</p>
		<ul>
		<li>Contents Info - the main page header information, including the page title and the url. Primary Key is content_id. Stored in a separate table, one to many for page content.</li>
		<li>Content - main page content. Primary key is content_unique; multiple pages by audience for each content_id.</li>
		<li>Tags - tags for the page to allow page-specific keyword searching</li>
		<li>Inline CSS - page specific CSS for inside the page header</li>
		<li>Linked CSS - linked CSS files if needed, loaded in the page header. Relative URLs are best, comma delimited</li>
		<li>Inline JS - page specific javascript for inside the page header. Note, JS is at top of page, use $(function(){ ... }); for jQuery if working on loaded elements!</li>
		<li>Linked JS - linked JS libraries if needed, loaded in the page header. Relative URLs if local, or full if offsite (ie CDN, etc), comma delimited.</li>
		</ul>
		<p>Page revision produces a version in cms_contentshistory, and inserts new entry into cms_contents. There are no 'update' queries in the cms - only inserts. Versioning is on the way, will use rollbacks.</p>
		<h5>Main CSS</h5>
		<p>The current CSS file for the site, for reference.</p>
		<h5>Layout Templates</h5>
		<p>Common layout templates (optional) currently used in the site. Will be updated as things change.</p>
		</div>
		</div>
	<input type="hidden" id="new" name="new" value="<?php print $newpage; ?>" />
	<input type="hidden" id="pageid" name="pageid" value="<?php print $_GET['id']; ?>" />
	<input type="hidden" id="pageunique" name="pageunique" value="<?php print $_GET['p']; ?>" />
	</form>
	<?php

	if ($_GET ['n'] != "n") {
	//if ($_SESSION ['profile'] >= 4) {
	//modal box history back and forth - likely can remove this
		$_SESSION ['modalboxhistory'] = "http://" . $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
	?>
	<?php //}?>

	<?php }
	//}
	?>
</div>
</div>
<script>
		$('.popmodal').magnificPopup({
			  type: 'ajax'
		});
</script>
