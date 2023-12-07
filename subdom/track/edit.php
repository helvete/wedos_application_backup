<? 
	require_once('functions.inc.php'); 
	do_login('edit.php');
	
	/* edit ticket */
	function edit_ticket($id)
	{
		//clean up HTML tags
		$_POST['frm_description'] 	= strip_html($_POST['frm_description']);
		$_POST['frm_affected'] 	 	= strip_html($_POST['frm_affected']);
		$_POST['frm_scope']			= strip_html($_POST['frm_scope']);

		//do automatic action reporting
		/*if (get_variable('reporting'))
		{
			if ($_POST[frm_affected] != $_POST[frm_affected_default]) report_action($GLOBALS[ACTION_AFFECTED],$_POST[frm_affected],0,$id);
			if ($_POST[frm_severity] != $_POST[frm_severity_default]) report_action($GLOBALS[ACTION_SEVERITY],get_severity($_POST[frm_severity_default]),get_severity($_POST[frm_severity]),$id);
			if ($_POST[frm_scope] != $_POST[frm_scope_default]) report_action($GLOBALS[ACTION_SCOPE],$_POST[frm_scope_default],0,$id);
		}*/
		
		//put together date from the dropdown box and textbox values
		if (!get_variable('military_time'))
		{
			if ($_POST[frm_meridiem_problemstart] == 'pm') 	$_POST[frm_hour_problemstart]	= ($_POST[frm_hour_problemstart] + 12) % 24;
			if ($_POST[frm_meridiem_problemend] == 'pm') 	$_POST[frm_hour_problemend] 	= ($_POST[frm_hour_problemend] + 12) % 24;
		}
			
		$frm_problemstart = "$_POST[frm_year_problemstart]-$_POST[frm_month_problemstart]-$_POST[frm_day_problemstart] $_POST[frm_hour_problemstart]:$_POST[frm_minute_problemstart]:00";
		$frm_problemend   = "$_POST[frm_year_problemend]-$_POST[frm_month_problemend]-$_POST[frm_day_problemend] $_POST[frm_hour_problemend]:$_POST[frm_minute_problemend]:00";
		
		//update ticket
		$query = "UPDATE $GLOBALS[mysql_prefix]ticket SET affected='$_POST[frm_affected]',scope='$_POST[frm_scope]',owner='$_POST[frm_owner]',description='$_POST[frm_description]',status='$_POST[frm_status]',problemstart='$frm_problemstart',problemend='$frm_problemend',severity='$_POST[frm_severity]' WHERE ID='$id'";
		$result = mysql_query($query) or do_error('edit.php::update_ticket', 'mysql_query() failed', mysql_error);

		/* show updated ticket */
		print '<FONT CLASS="header">Ticket has been updated</FONT><BR><BR>';
		notify_user($id,$NOTIFY_TICKET);
		show_ticket($id);
		add_footer($id);
	}
	
?> 
<HTML>
<HEAD>
<LINK REL=StyleSheet HREF="default.css" TYPE="text/css">
</HEAD>
<BODY>
<? 
	$id = $_GET['id'];

	if ($_GET['action'] == 'update')
	{
		/* update ticket */
		if ($id == '' OR $id <= 0 OR !check_for_rows("SELECT * FROM $GLOBALS[mysql_prefix]ticket WHERE id='$id'"))
			print "<FONT CLASS=\"warn\">Invalid Ticket ID: '$id'</FONT>";
		else
			edit_ticket($id);
	}
	//delete ticket
	else if ($_GET['delete']) 
	{
		if ($_POST['frm_confirm'])
		{
			/* remove ticket and ticket actions */
			$result = mysql_query("DELETE FROM $GLOBALS[mysql_prefix]ticket WHERE ID='$id'") or do_error('edit.php::remove_ticket(ticket)', 'mysql_query() failed', mysql_error());
			$result = mysql_query("DELETE FROM $GLOBALS[mysql_prefix]action WHERE ticket_id='$id'") or do_error('edit.php::remove_ticket(action)', 'mysql_query() failed', mysql_error());
			print "<FONT CLASS=\"header\">Ticket '$id' has been removed.</FONT><BR><BR>";
			list_tickets();
		}
		else
			//confirm deletion
			print "<FONT CLASS=\"header\">Confirm ticket deletion</FONT><BR><BR><FORM METHOD=\"post\" ACTION=\"edit.php?id=$id&delete=1&go=1\"><INPUT TYPE=\"checkbox\" NAME=\"frm_confirm\" VALUE=\"1\">Delete ticket #$id &nbsp;<INPUT TYPE=\"Submit\" VALUE=\"Confirm\"></FORM>";
	}
	else
	{
		/* sanity check */
		if ($id == '' OR $id <= 0 OR !check_for_rows("SELECT * FROM $GLOBALS[mysql_prefix]ticket WHERE id='$id'"))
		{
			print "<FONT CLASS=\"warn\">Invalid Ticket ID: '$id'</FONT><BR>";
		} else {

		$result = mysql_query("SELECT *,UNIX_TIMESTAMP(problemstart) AS problemstart,UNIX_TIMESTAMP(problemend) AS problemend,UNIX_TIMESTAMP(date) AS date FROM $GLOBALS[mysql_prefix]ticket WHERE ID='$id'") or do_error('edit.php::show_ticket', 'mysql_query() failed', mysql_error());
		$row = mysql_fetch_array($result);

		print "<FONT CLASS=\"header\">Edit Ticket</FONT><BR><BR>";
		print "<FORM METHOD=\"post\" ACTION=\"edit.php?id=$id&action=update\"><TABLE BORDER=\"0\"><TR><TD CLASS=\"td_label\">Affected:</TD>\n";
		print "<TD><INPUT TYPE=\"text\" SIZE=\"48\" NAME=\"frm_affected\" VALUE=\"$row[affected]\"></TD></TR>";
		print "<TR><TD CLASS=\"td_label\">Scope:</TD><TD><INPUT TYPE=\"text\" NAME=\"frm_scope\" SIZE=\"48\" VALUE=\"$row[scope]\"></TD></TR>\n"; 
	
		//lookup owners
		if (get_variable('restrict_user_add') && !(is_administrator()) || $_SESSION['level'] != $GLOBALS['LEVEL_ADMINISTRATOR'])
			print "<INPUT TYPE=\"hidden\" NAME=\"frm_owner\" VALUE=\"$row[owner]\">";
		else
		{
			print '<TR><TD CLASS="td_label">Owner:</TD><TD>';
			$result2 = mysql_query("SELECT id,user FROM $GLOBALS[mysql_prefix]user") or do_error('edit.php::lookup_owner', 'mysql_query() failed', mysql_error());
			print '<SELECT NAME="frm_owner">';
			while ($row2 = mysql_fetch_array($result2))
			{
				if (get_owner($row['owner']) == $row2['user'])
					print "<OPTION VALUE=\"$row2[id]\" SELECTED>$row2[user]</OPTION>";
				else
					print "<OPTION VALUE=\"$row2[id]\">$row2[user]</OPTION>";
			}
			print '</SELECT></TD></TR>';
		}
		?>
		<TR><TD CLASS="td_label">Severity:</TD><TD><SELECT NAME="frm_severity">
		<OPTION VALUE="<?=$GLOBALS['SEVERITY_NORMAL'];?>" <?=($row[severity]==$GLOBALS['SEVERITY_NORMAL']) ? "SELECTED" : "";?>><?=get_severity($GLOBALS['SEVERITY_NORMAL']);?></OPTION>
		<OPTION VALUE="<?=$GLOBALS['SEVERITY_MEDIUM'];?>" <?=($row[severity]==$GLOBALS['SEVERITY_MEDIUM']) ? "SELECTED" : "";?>><?=get_severity($GLOBALS['SEVERITY_MEDIUM']);?></OPTION>
		<OPTION VALUE="<?=$GLOBALS['SEVERITY_HIGH'];?>" <?=($row[severity]==$GLOBALS['SEVERITY_HIGH']) ? "SELECTED" : "";?>><?=get_severity($GLOBALS['SEVERITY_HIGH']);?></OPTION>
		</SELECT></TD></TR>
		<TR><TD CLASS="td_label">Status:</TD><TD>
		<SELECT NAME="frm_status"><OPTION VALUE="2">Open</OPTION><OPTION VALUE="1">Closed</OPTION></SELECT></TD></TR>
		<TR><TD CLASS="td_label">Problem Starts:</TD><TD><?=generate_date_dropdown("problemstart",$row[problemstart]);?></TD></TR>
		<TR><TD CLASS="td_label">Problem Ends:</TD><TD><?=generate_date_dropdown("problemend",$row[problemend]);?></TD></TR>
		<TR><TD CLASS="td_label">Description:</TD><TD><TEXTAREA NAME="frm_description" COLS="35" ROWS="8"><?=$row['description'];?></TEXTAREA></TD></TR>
		<INPUT TYPE="hidden" NAME="frm_status_default" VALUE="<?=$row[status];?>">
		<INPUT TYPE="hidden" NAME="frm_affected_default" VALUE="<?=$row[affected];?>">
		<INPUT TYPE="hidden" NAME="frm_scope_default" VALUE="<?=$row[scope];?>">
		<INPUT TYPE="hidden" NAME="frm_owner_default" VALUE="<?=$row[owner];?>">
		<INPUT TYPE="hidden" NAME="frm_severity_default" VALUE="<?=$row[severity];?>">
		</TABLE><BR>

		<?
		/* list actions belonging to ticket */
		$result = mysql_query("SELECT *,UNIX_TIMESTAMP(date) AS date FROM $GLOBALS[mysql_prefix]action WHERE ticket_id='$id' ORDER BY date") or do_error('edit.php::action', 'mysql_query() failed', mysql_error());	
		print '<TABLE BORDER="0">';
 
		$i=0;
		while ($row = mysql_fetch_array($result))
		{
			//if reporting action, skip
			if ($row[action_type] != $GLOBALS[ACTION_COMMENT]) continue;
			
			//if action desc containts rowbreaks, use <pre> tag
			//if (substr_count($row[description],"\n")) { $pre_tag = '<pre class="text">'; $pre_tag_closed = '</pre>'; }
			
			print '<TR><TD CLASS="td_label" NOWRAP>';	
			if (!$no_action) print 'Action: &nbsp;&nbsp;';
			print "</TD><TD ALIGN=\"left\"><B>".format_date($row[date])."</B> - $pre_tag ".custom_tags($row[description])." $pre_tag_closed &nbsp;[<A HREF=\"action.php?ticket_id=$id&id=$row[id]&action=edit\">edit</A>|<A HREF=\"action.php?id=$row[id]&ticket_id=$id&action=delete\">delete</A>]</TD></TR>\n";
			$no_action = 1; /* simple cosmetics fix */
			$i++;
		}
	       	
		print '</TABLE><BR><INPUT TYPE="submit" VALUE="Submit"></FORM>';
		}
	}
?>
</BODY></HTML>