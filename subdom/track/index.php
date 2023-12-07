<? 
	require_once('functions.inc.php');
?>
<HTML>
<HEAD>
<TITLE>PHP Ticket <?=get_variable('version');?></TITLE>
<LINK REL=StyleSheet HREF="default.css" TYPE="text/css">
</HEAD>
<FRAMESET ROWS="<?=get_variable('framesize');?>,*" BORDER="<?=get_variable('frameborder');?>">
	<FRAME SRC="top.php" NAME="top" SCROLLING="no">
	<FRAME SRC="main.php" NAME="main">
	<NOFRAMES>
	<BODY>
		PHP Ticket requires a frame capable browser.
	</BODY>
	</NOFRAMES>
</FRAMESET>
</HTML>
