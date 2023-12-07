<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File: 			template-homepage.php
* Privy theme for GetSimple CMS
* Design by TEMPLATED
* http://templated.co
* Released for free under the Creative Commons Attribution License
*
*****************************************************/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php get_header(); ?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php get_site_name(); ?> &mdash; <?php get_page_clean_title(); ?></title>
	<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900" rel="stylesheet" />
	<link href="<?php get_theme_url(); ?>/default.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body id="<?php get_page_slug(); ?>" >

<div id="page" class="container">
	<div id="header">
		<div id="logo">
			<img src="<?php get_theme_url(); ?>/images/pic02.jpg" alt="" />
			<h1><a href="<?php get_site_url(); ?>"><?php get_site_name(); ?></a></h1>
			<span><?php get_component('tagline');	?></span>
		</div>
		<div id="menu">
			<ul>
				<?php get_navigation(return_page_slug()); ?>
			</ul>
		</div>
	</div>

	<div id="main">
		<div id="banner">
			<img src="<?php get_theme_url(); ?>/images/pic01.jpg" alt="" class="image-full" />
		</div>
		<div id="welcome">
			<div class="title">
				<h2><?php get_page_title(); ?></h2>
				<span class="byline">Donec leo, vivamus fermentum nibh in augue praesent a lacus at urna congue</span>
			</div>
			<?php get_page_content(); ?>
		</div>

		<div id="copyright">
			<span>&copy; <?php get_site_name(); ?>. All rights reserved. | Photos by <a href="http://fotogrph.com/">Fotogrph</a></span>
			<span>Design by <a href="http://templated.co" rel="nofollow">TEMPLATED</a>.</span>
		</div>
	</div>
</div>

<?php get_footer(); ?>
</body>
</html>
