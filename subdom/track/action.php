<? 
	require_once('functions.inc.php'); 
	do_login('action.php');
?> 
<HTML>
<HEAD>
<LINK REL=StyleSheet HREF="default.css" TYPE="text/css">
</HEAD>
<BODY>
<? 
	if ($_GET['action'] == 'add')
	{
		/* update ticket */
		if ($_GET['ticket_id'] == '' OR $_GET['ticket_id'] <= 0 OR !check_for_rows("SELECT * FROM $GLOBALS[mysql_prefix]ticket WHERE id='$_GET[ticket_id]'"))
			print "<FONT CLASS=\"warn\">Invalid Ticket ID: '$_GET[ticket_id]'</FONT>";
		elseif ($_POST['frm_action'] == '')
			print '<FONT CLASS="warn">Action field is empty. Please try again.</FONT><BR>';
		else
		{
			$_POST['frm_action'] = strip_html($_POST['frm_action']); //fix formatting, custom tags etc.
     		$query 	= "INSERT INTO $GLOBALS[mysql_prefix]action (description,ticket_id,date,user,action_type) VALUES('$_POST[frm_action]','$_GET[ticket_id]',NOW(),$_SESSION[user_id],$GLOBALS[ACTION_COMMENT])";
			$result	= mysql_query($query) or do_error('action.php::add action','mysql_query() failed',mysql_error());

			print '<FONT CLASS="header">Ticket has been updated</FONT><BR><BR>';
			show_ticket($_GET['ticket_id']);
			add_footer($_GET['ticket_id']);
			notify_user($_GET['ticket_id'],$NOTIFY_ACTION);
			exit();
		}
	}
	else if ($_GET['action'] == 'delete')
	{
		if ($_GET['confirm'])
		{
			$result = mysql_query("DELETE FROM action WHERE id='$_GET[id]'") or do_error('action.php::del action','mysql_query',mysql_error());
			print '<FONT CLASS="header">Action deleted</FONT><BR><BR>';
			show_ticket($_GET[ticket_id]);
		}
		else
			print "<FONT CLASS=\"header\">Really delete action '$_GET[id]'?</FONT><BR><BR><FORM METHOD=\"post\" ACTION=\"action.php?action=delete&id=$_GET[id]&ticket_id=$_GET[ticket_id]&confirm=1\"><INPUT TYPE=\"Submit\" VALUE=\"Yes\"></FORM>";
	}
	else if ($_GET['action'] == 'update')
	{
		//update action and show ticket
		$result = mysql_query("UPDATE $GLOBALS[mysql_prefix]action SET description='$_POST[frm_action]' WHERE id='$_GET[id]'") or do_error('action.php::update action','mysql_query',mysql_error());
		$result = mysql_query("SELECT ticket_id FROM $GLOBALS[mysql_prefix]action WHERE id='$_GET[id]'") or do_error('action.php::update action','mysql_query',mysql_error());
		$row = mysql_fetch_array($result);
		print '<FONT CLASS="header">Action updated</FONT><BR><BR>';
		show_ticket($row['ticket_id']);
	}
	else if ($_GET['action'] == 'edit')
	{
		//get and show action to update
		$result = mysql_query("SELECT * FROM $GLOBALS[mysql_prefix]action WHERE id='$_GET[id]'");
		$row = mysql_fetch_array($result);
		?>
		<FONT CLASS="header">Edit Action</FONT><BR><BR>
		<FORM METHOD="post" ACTION="action.php?id=<?=$_GET['id'];?>&ticket_id=<?=$_GET['ticket_id'];?>&action=update"><TABLE BORDER="0">
		<TR><TD><B>Description:</B></TD><TD><TEXTAREA ROWS="8" COLS="45" NAME="frm_action"><?=$row[description];?></TEXTAREA></TD></TR>
		<TR><TD></TD><TD><INPUT TYPE="Submit" VALUE="Submit"></TD></TR>
		</TABLE><BR>
		<?
	}
	else
	{
		?>
		<FONT CLASS="header">Add Action</FONT><BR><BR>
		<FORM METHOD="post" ACTION="action.php?ticket_id=<?=$_GET['ticket_id'];?>&action=add"><TABLE BORDER="0">
		<TR><TD><B>Description:</B></TD><TD><TEXTAREA ROWS="8" COLS="45" NAME="frm_action"></TEXTAREA></TD></TR>
		<TR><TD></TD><TD><INPUT TYPE="Submit" VALUE="Submit"></TD></TR>
		</TABLE><BR>
		<?
	}
	add_footer($_GET['ticket_id']); 
	?>
</BODY></HTML>