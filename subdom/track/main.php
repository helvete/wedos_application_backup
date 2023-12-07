<?
	require_once('functions.inc.php');

	if ($_GET['logout'] == 'true')
	{
		do_logout();
		exit();
	}
	else
		do_login('main.php');

?>
<HTML>
<HEAD>
<LINK REL=StyleSheet HREF="default.css" TYPE="text/css">
</HEAD><BODY>
<?
	if ($_GET['print'] == 'true')
		show_ticket($_GET['id'],'true');
	else if ($_GET['id'])
	{
		show_ticket($_GET['id']);
		add_footer($_GET['id']);
	}
	else if ($_GET['sort_by_field'] && $_GET['sort_value'])
		list_tickets($_GET['sort_by_field'],$_GET['sort_value']);
	else
		list_tickets();
?>
</BODY></HTML>
