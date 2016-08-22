<?php
	session_start();

	$remove_from_root = 'admin/';
	require('../config.php');

	require('config_page.php');
	require('libs/hv_mysqli_handler.php');
	require('libs/hv_url_routing.php');
	require('libs/hv_shorten.php');
	require('libs/to_ascii.php');
	require('libs/safe_string.php');
	require('libs/hv_ini_set.php');
	require('libs/date_correction.php');

	require('inc/model.php');

	$vote_closed = is_file('../closed.txt');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns='http://www.w3.org/1999/xhtml' xmlns:fb="http://ogp.me/ns/fb#">

	<head>

		<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
		<title>Izgiland Admin</title>

		<!-- Styles -->
			<link rel="stylesheet" href="<?php echo BASE_URL; ?>admin/css/vendor/template.css" />
			<link rel="stylesheet" href="<?php echo BASE_URL; ?>admin/js/hv_ajax_loader/hv_ajax_loader.css" />
			<link href='<?php echo BASE_URL; ?>admin/call/hv_concat.php?v=1.0&files=<?php echo $styles; ?>&type=css' type='text/css' rel='stylesheet/less' />
		<!-- Styles -->

		<link href="<?php echo BASE_URL; ?>favicon.ico" rel="shortcut icon" />

		<script src="<?php echo BASE_URL; ?>admin/js/vendor/less.js" type="text/javascript"></script>


	</head>
	<body>

	<?php if ( ADMIN ) { ?>

		<div id="wrapper">
			<div id="content">
				<div class="c1">
					<div class="controls">
						<nav class="links">
							<ul>
							</ul>
						</nav>
						<div class="profile-box">
							<a href="javascript: logout()" class="btn-on">On</a>
						</div>
					</div>
					<div class="tabs">
						<div class="tab">

							<article>

								<?php
									include('content/'.$params[1].'.php');
								?>


								<!-- Template -->
								<!--
									<ul class="states">
										<li class="error">Error : This is an error placed text message.</li>
										<li class="warning">Warning: This is a warning placed text message.</li>
										<li class="succes">Succes : This is a succes placed text message.</li>
									</ul>
								-->
								<!-- Template -->
							</article>
						</div>
					</div>
				</div>
			</div>
			<aside id="sidebar">
				<br/><br/><br/>
				<ul class="tabset buttons">

					<?php

						// for ($i=0;$i<count($allowed_subpages);$i++) {
						$i=1;
						foreach ($allowed_subpages as $key => $value) {
							echo '
								<li class="'.((isset($params[1])&&$params[1]==$key)?'active':'').'">
									<a href="'.BASE_URL.'admin/'.$key.'" class="ico'.($i++).'"><span>'.$value.'</span><em></em></a>
									<span class="tooltip"><span>'.$value.'</span></span>
								</li>'
							;
							if ($i>8) {
								$i = 8;
							}
						}/*
						echo '
							<li>
								<a href="javascript: close_vote()" class="ico7"><span>Szavazás lezárása</span><em></em></a>
								<span class="tooltip"><span>Szavazás lezárása</span></span>
							</li>'
						;*/
						echo '
							<li>
								<a href="javascript: logout()" class="ico8"><span>Kijelentkezés</span><em></em></a>
								<span class="tooltip"><span>Kijelentkezés</span></span>
							</li>'
						;

					?>
					<!--
						<li>
							<a href="#" class="ico5"><span>Google Analytics</span><em></em></a>
							<span class="tooltip"><span>Google Analytics</span></span>
						</li> -->
					<!--
					<li class="active" onclick="location.href=''">
						<a href="#tab-1" class="ico3"><span>Hírek</span><em></em></a>
						<span class="tooltip"><span>Hírek</span></span>
					</li>
					<li class="" onclick="adminmenu(1)">
						<a href="#tab-2" class="ico4"><span>Galéria</span><em></em></a>
						<span class="tooltip"><span>Galéria</span></span>
					</li> -->
				</ul>
				<span class="shadow"></span>
			</aside>
		</div>

	<?php } else include('content/login.php'); ?>


		<!-- Scripts -->
			<script>
				// GLOBALS
				var DOMAIN = '<?php echo DOMAIN; ?>';
				var ROOT = '<?php echo ROOT; ?>';
				var BASE_URL = '<?php echo BASE_URL; ?>';
				// GLOBALS
			</script>

			<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
			<script>window.jQuery || document.write('<script src="<?php echo BASE_URL; ?>admin/js/vendor/jquery-1.9.1.min.js"><\/script>')</script>

			<script src="<?php echo BASE_URL; ?>admin/js/vendor/nicEdit/nicEdit.js" type="text/javascript"></script>
			<script src="<?php echo BASE_URL; ?>admin/call/hv_concat.php?v=1.1&files=<?php echo $scripts; ?>&type=js"></script>
		<!-- Scripts -->

	</body>
</html>
