<? 
	require_once('functions.inc.php');
?>
<HTML>
<HEAD>
<style type="text/css">
<!--
	BODY { BACKGROUND-COLOR: #EEEEEE; FONT-WEIGHT: normal; FONT-SIZE: 12px; COLOR: #000000; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; TEXT-DECORATION: none }
	A { FONT-WEIGHT: bold; FONT-SIZE: 12px; COLOR: #000099; FONT-STYLE: normal; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; TEXT-DECORATION: none }
-->
</style>
</HEAD><BODY>
<TITLE>PHP Ticket <?=get_variable('version');?></TITLE><FONT SIZE="3">
PHP Ticket <?=get_variable('version')." on <B>".get_variable('host')."</B></FONT><BR>"; ?>
<A HREF="main.php" target="main">Show Tickets</A> | 
<A HREF="add.php" target="main">Add Ticket</A> | 
<A HREF="main.php?status=<?=$GLOBALS['STATUS_CLOSED'];?>" target="main">Show Closed</A> | 
<A HREF="search.php" target="main">Search</A> | 
<A HREF="config.php" target="main">Configuration</A> | 
<A HREF="help.php" target="main">Help</A> | 
<A HREF="main.php?logout=true" target="main">Logout</A>
</BODY></HTML>
