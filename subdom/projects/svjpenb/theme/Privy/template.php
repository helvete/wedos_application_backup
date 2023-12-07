<?php if(!defined('IN_GS')){ die('you cannot load this page directly.'); }
/****************************************************
*
* @File: 			template.php
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
<link href='http://fonts.googleapis.com/css?family=Arvo:700' rel='stylesheet' type='text/css'>
	<?php get_header(); ?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php get_site_name(); ?> &mdash; <?php get_page_clean_title(); ?></title>
	<link href="<?php get_theme_url(); ?>/default.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body id="<?php get_page_slug(); ?>" >
<div id="page" class="container">
	<div id="main">
		<div class="motto">Levný energetický štítek</div>
		<div id="content">
			<div class="title">
				<h2><a href=""><?php get_page_title(); ?></a></h2>
			</div>
			<?php get_page_content(); ?>
		</div>
		<div id="copyright">
			Březina Zdeněk s. r. o.,<br /><br /> A.Kříže 3, České Budějovice, PSČ 370 06, IČO: 28130545
		</div>
	</div>
</div>

<?php get_footer(); ?>
</body>
</html>
