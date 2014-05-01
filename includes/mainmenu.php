<div class="megamenu_wrapper_full megamenu_vtp_theme clear-block">
	<div class="megamenu_container megamenu_vtp clear-block">
		<ul class="megamenu clear-block" id="mainmenu">
			<li class="menuitem_nodrop"><a href="<?php echo $cms_url;?>/"
				title="Home">home</a>
			</li>
			<li class="menuitem_nodrop"><a href="<?php echo $cms_url;?>/about/"
				title="About the Virtual Textiles Project">about</a>
				<div class="dropdown_1column">
					<div class="col_full firstcolumn">
						<ul class="clear-block">
						   <li><a href="<?php echo $cms_url;?>/about/methodology/">Methodology</a>
							</li>
							<li><a href="<?php echo $cms_url;?>/about#project">The Project</a>
							</li>
							<li><a href="<?php echo $cms_url;?>/about#goals">Our Goals</a></li>
						</ul>
			</li>
			<li><a href="<?php echo $cms_url;?>/team/"
				title="Our Team">team</a>
				<div class="dropdown_2columns">
					<div class="col_full firstcolumn">
						<ul class="clear-block">
							<li><a href="<?php echo $cms_url;?>/team/mcgill/">McGill
									University</a>
							<ul>
							<li><a href="<?php echo $cms_url;?>/team/cbradley/">Catherine
									Bradley</a>
							</li>
							<li><a href="<?php echo $cms_url;?>/team/ssinclair/">Stefan
									Sinclair</a>
							</li>
							<li><a href="<?php echo $cms_url;?>/team/mmilner/">Matthew
									Milner</a>
							</li>
							<li><a href="<?php echo $cms_url;?>/team/pdavoust/">Peter
									Davoust</a>
							</li>
							</ul>
							</li>
							<li><a href="<?php echo $cms_url;?>/team/dragon_phoenix/">Dragon
									and Phoenix Software</a>
									<ul>
							<li><a href="<?php echo $cms_url;?>/team/klind/">Kat Lind</a>
							</li>
							</ul>
							</li>
						</ul>
					</div>
				</div></li>
			<li><a href="<?php echo $cms_url;?>/collections/"
				title="The Collections">the collections</a>
				<div class="dropdown_2columns">
					<div class="col_full firstcolumn">
						<ul class="clear-block">
							<li><a href="<?php echo $cms_url;?>/search/">Search</a>
							</li>
							<li><a href="<?php echo $cms_url;?>/textiles/">Textiles</a>
							</li>
							<li>&nbsp;</li>
							<li><a href="<?php echo $cms_url;?>/partners/">Partners</a></li>
							<li><a href="<?php echo $cms_url;?>/partners/new/">Interested in
									being a partner?</a></li>
							<li><a href="<?php echo $cms_url;?>/policies/">Policies</a>
							<li><a href="<?php echo $cms_url;?>/policies#copyright">Copyright</a>
							</li>
						</ul>
					</div>
				</div></li>
			<li class="menuitem_nodrop"><a href="<?php echo $cms_url;?>/blog/"
				title="Our Blog">blog</a>
			</li>
			<li><a href="<?php echo $cms_url;?>/community/" class="menuitem_drop"
				title="community">community</a>
				<div class="dropdown_1column">
					<div class="col_full firstcolumn">
						<ul class="clear-block">
							<li><a href="<?php echo $cms_url;?>/community/gallery/">Gallery</a></li>
						</ul>
					</div>
				</div></li>
				<?php /*?><li><a href="<?php echo $cms_url;?>/audiences/"
				title="About the Virtual Textiles Project">audiences</a>
			<div class="dropdown_1column">
					<div class="col_full firstcolumn">
						<ul class="clear-block">
							<li><a href="<?php echo $cms_url;?>/audiences/academic/">Academic</a>

							<li><a href="<?php echo $cms_url;?>/audiences/performance/">Performance</a>
							</li>
							<li><a href="<?php echo $cms_url;?>/audiences/industry/">Industry</a>
								<ul>
									<li><a
										href="<?php echo $cms_url;?>/audiences/industry#reproduction">Reproduction</a>
									</li>
									<li><a href="<?php echo $cms_url;?>/audiences/industry#virtual">Virtual</a>
									</li>
								</ul>

							<li><a href="<?php echo $cms_url;?>/audiences/conservation/">Conservation</a>
							</li>
						</ul>
					</div>
				</div></li>
				<?php */?>
			<li class="menuitem_right <?php if (empty ($_SESSION['user'])) {?>menuitem_nodrop"><a href="/cms/login/" class="popmodal">login</a><?php
} else {
?>"><span><?php echo $_SESSION['user']; ?>
			</span>
				<div class="dropdown_1column dropdown_right">
					<div class="col_full firstcolumn">
						<ul class="clear-block">
							<li><a href="<?php echo $cms_url; ?>/account/">my account</a>
							</li>
							<?php if ($_SESSION['profile'] >= 3) {?>
							<li><a href="<?php echo $cms_url; ?>/admin/">project admin</a>
							</li>
							<li><a class="popmodal"
								href="<?php echo $cms_url; ?>/cms/page/manage.php?id=<?php echo $thispageid; ?>">manage
									page</a>
							</li>
							<?php } ?>
							<li><a href="<?php echo $cms_url; ?>/cms/login/logout.php?p=<?php print urlencode("http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING']);?>">logout</a>
							</li>
						</ul>
					</div>
				</div> <?php }?></li>
		</ul>
	</div>
</div>
