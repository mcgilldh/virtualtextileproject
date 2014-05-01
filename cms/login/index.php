<?php $thisabspath = "/Users/virtualtextileproject/Sites/workingcopy";
define("ABSPATH", dirname(__FILE__) . '/');
include (ABSPATH . "../../includes/phpheader.php");
header("Content-Type: text/html; charset=utf-8");
header("Expires: Mon, 01 Jul 2003 00:00:00 GMT");
// Past date
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
// HTTP/1.1
header("Pragma: no-cache");?>
<div id="modalcontent" class="w300">
<h2>Login</h2>
<form method="post" action="/cms/login/login.php"
							id="loginform">
							<input type="hidden" id="whereurl" name="whereurl"
								value="">
							<p class="h30">
								<label class="w70">user</label><input type="text"
									class="text w200 right" id="user" name="user" value="">
							</p>
							<p class="h30">
								<label class="w70">password</label><input type="password"
									class="text w200 right" id="password" name="password">
							</p>
							<p class="h30">
								<input type="submit" class="makebutton" value="login">
							</p>
							<?php /*?><p class="h30">
								<label class="w70">remember?</label><input type="checkbox"
									id="remember" name="remember" value="yes"><a
									href="/account/password/forgot.php" class="right">my password?</a>
							</p><?php */?>
						</form>
						<script>
						$('#whereurl').val(document.URL);
						</script>
						</div>