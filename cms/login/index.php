<?php /*user login modal*/
$thisabspath = "/Users/virtualtextileproject/Sites";
define("ABSPATH", dirname(__FILE__) . '/');
include (ABSPATH . "../../includes/phpheader.php");

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: text/html; charset=utf-8");
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
							<p class="h30">
								<label class="w70">remember?</label><input type="checkbox"
									id="remember" name="remember" value="yes"><a
									href="/account/forgot/" class="right">my password?</a>
							</p>
						</form>
						<script>
						$('#whereurl').val(document.URL);
						</script>
						</div>