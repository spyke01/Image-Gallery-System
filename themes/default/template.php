<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
	<head>
		<title><? echo $igs_config['ftsigs_site_title'] . " - "; $page->printTemplateVar("PageTitle");  ?></title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="content-language" content="en-us" />
		<!--Stylesheets Begin-->
			<link rel="stylesheet" type="text/css" href="themes/lightbox.css" />
			<link rel="stylesheet" type="text/css" href="themes/gallery.css" />
			<link rel="stylesheet" type="text/css" href="themes/<?= $igs_config['ftsigs_theme']; ?>/main.css" />
			<link rel="stylesheet" type="text/css" href="themes/<?= $igs_config['ftsigs_theme']; ?>/dim.css" />
			<link rel="stylesheet" type="text/css" href="themes/<?= $igs_config['ftsigs_theme']; ?>/alt_star_rating.css" />
			<!--[if lt IE 7]>
				<style>
				</style>
			<![endif]-->			
		<!--Stylesheets End-->
	
		<!--Javascripts Begin-->
			<script type="text/javascript" src="javascripts/scriptaculous1.8.2.js"></script>
			<script type="text/javascript" src="javascripts/validation.js"></script>
			<script type="text/javascript" src="javascripts/ssf_global.js"></script>
			<script type="text/javascript" src="javascripts/bbcode.js"></script>
			<script type="text/javascript" src="javascripts/confirm.js"></script>
			<script src="javascripts/lightbox.js" type="text/javascript"></script>
			<!--[if lt IE 7]>
				<script src="javascripts/pngfix.js" defer type="text/javascript"></script>
			<![endif]-->	
		<!--Javascripts End-->
		<link rel="shortcut icon" href="favicon.ico">
	</head>
	<body>
		<div id="container">
			<div id="page">
				<div id="header">
				</div>
				<div id="top-nav">
					<? $page->printMenu("top", "ul", "", "", "", "nav", ""); ?>
				</div>		
				<div id="content">
					<div id="left-col">
						<? $page->printTemplateVar('PageContent'); ?>	
					</div>
					<div id="right-col">
						<? $page->printSidebar("sidebar", ""); ?>
					</div>	
				</div>				
				<div id="footer">				
					<div id="footer-leftcol" class="FForumBorder">
						Copyright &copy; 2007 Fast Track Sites
					</div>
					<div id="footer-rightcol">
						Powered By: <a href="http://www.fasttracksites.com">Fast Track Sites Simply Image Gallery System</a>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
