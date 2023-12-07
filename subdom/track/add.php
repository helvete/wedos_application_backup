<?
	require_once('functions.inc.php');
	do_login('add.php');
?>
<HTML>
<HEAD>
<LINK REL=StyleSheet HREF="default.css" TYPE="text/css">
</HEAD><BODY>
<?
	if (!empty($_GET['add']) && $_GET['add'] == 'true')
	{
		//put together date from the dropdown box and textbox values
		if (!get_variable('military_time'))
		{
			if ($_POST[frm_meridiem_problemstart] == 'pm') 	$_POST[frm_hour_problemstart] 	= ($_POST[frm_hour_problemstart] + 12) % 24;
			if ($_POST[frm_meridiem_problemend] == 'pm') 	$_POST[frm_hour_problemend] 	= ($_POST[frm_hour_problemend] + 12) % 24;
		}

		$problemstart = "$_POST[frm_year_problemstart]-$_POST[frm_month_problemstart]-$_POST[frm_day_problemstart] $_POST[frm_hour_problemstart]:$_POST[frm_minute_problemstart]:00$_POST[frm_meridiem_problemstart]";
		$problemend   = "$_POST[frm_year_problemend]-$_POST[frm_month_problemend]-$_POST[frm_day_problemend] $_POST[frm_hour_problemend]:$_POST[frm_minute_problemend]:00$_POST[frm_meridiem_problemend]";

		add_ticket($_POST['frm_description'],$_POST['frm_affected'],$_POST['frm_scope'],$problemstart,$problemend,$GLOBALS['STATUS_OPEN'],$_POST['frm_severity'],$_POST['frm_owner']);
		print "<FONT CLASS=\"header\">Added Ticket: '".substr($_POST[frm_description],0,50)."' by user '$_SESSION[user_name]'</FONT><BR><BR>";
		list_tickets('','','main.php');
	}
	else
	{
?>
<FONT CLASS="header">Add Ticket</FONT><BR><BR>
<?
	//if user is guest and guest_add_ticket is false, warn and exit
	if (is_guest() && !get_variable('guest_add_ticket'))
	//if (!is_administrator())
	{
		print '<FONT CLASS="warn">Guest users are not allowed to add tickets on this system</FONT>';
		exit();
	}
?>
<FORM METHOD="post" ACTION="add.php?add=true"><TABLE BORDER="0">
<TR><TD CLASS="td_label">Affected:</TD><TD><INPUT SIZE="48" TYPE="text" NAME="frm_affected"></TD></TR>
<TR><TD CLASS="td_label">Scope:</TD><TD><INPUT SIZE="48" TYPE="text" NAME="frm_scope"></TD></TR>
<TR><TD CLASS="td_label">Problem Starts: &nbsp;&nbsp;</TD><TD><?=generate_date_dropdown('problemstart');?></TD></TR>
<TR><TD CLASS="td_label">Problem End: &nbsp;&nbsp;</TD><TD><?=generate_date_dropdown('problemend');?></TD></TR>
<?
	if (get_variable("restrict_user_add") && !($_SESSION['level'] == $GLOBALS['LEVEL_ADMINISTRATOR']))
		print "<INPUT TYPE=\"hidden\" NAME=\"frm_owner\" VALUE=\"$_SESSION[user_id]\">";
	else
	{
		//generate dropdown menu of users
		$result = mysql_query("SELECT id,user FROM $GLOBALS[mysql_prefix]user") or do_error('add.php::generate_owner_dropdown','mysql_query() failed', mysql_error());
		print '<TR><TD CLASS="td_label">Owner:</TD><TD><SELECT NAME="frm_owner">';
    	while ($row = mysql_fetch_array($result))
		{
			print "<OPTION VALUE=\"$row[id]\" ";
			if ($row[id] == $_SESSION[user_id]) print "SELECTED";
			print ">$row[user]</OPTION>";
		}
		print '</SELECT></TD></TR>';
	}
?>
<TR><TD CLASS="td_label">Severity:</TD><TD><SELECT NAME="frm_severity">
<OPTION VALUE="0" SELECTED><?=get_severity($GLOBALS['SEVERITY_NORMAL']);?></OPTION>
<OPTION VALUE="1"><?=get_severity($GLOBALS['SEVERITY_MEDIUM']);?></OPTION>
<OPTION VALUE="2"><?=get_severity($GLOBALS['SEVERITY_HIGH']);?></OPTION>
</SELECT></TD></TR>
<TR><TD CLASS="td_label">Description:</TD><TD><TEXTAREA NAME="frm_description" COLS="35" ROWS="8"></TEXTAREA></TD></TR>
<TR><TD></TD><TD ROWSPAN="2"><P ALIGN="right"><INPUT TYPE="submit" VALUE="Submit"></P></TD></TR>
</TABLE></FORM>
<? } //end if ?>
</BODY></HTML>
