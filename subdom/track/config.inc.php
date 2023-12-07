<?

/* config.inc.php contains config specific functions */

/* run the OPTIMIZE sql query on all tables */
function optimize_db()
{
	$result = mysql_query("OPTIMIZE TABLE $GLOBALS[mysql_prefix]ticket, $GLOBALS[mysql_prefix]action, $GLOBALS[mysql_prefix]user, $GLOBALS[mysql_prefix]settings, $GLOBALS[mysql_prefix]notify") or do_error('functions.inc.php::optimize_db()', 'mysql_query(optimize) failed', mysql_error());
}

/* reset database to defaults */
function reset_db($user=0,$ticket=0,$settings=0,$purge=0)
{
	/*if($purge)
	{
		print '<LI> Purging closed tickets...NOT IMPLEMENTED';
		//$result = mysql_query("DELETE FROM action") or do_error("functions.php.inc::reset_db($user,$ticket,$settings)", 'mysql query failed', mysql_error());
		//$result = mysql_query("DELETE FROM ticket WHERE status = '$GLOBALS[STATUS_CLOSED]'") or do_error("functions.php.inc::reset_db($user,$ticket,$settings)",'mysql query failed', mysql_error());
		//SELECT action.id FROM action,ticket WHERE action.ticket_id=ticket.id and ticket.status=2;
	}*/

	if($ticket)
	{
		print '<LI> Deleting tickets...';
		$result = mysql_query("DELETE FROM $GLOBALS[mysql_prefix]ticket") or do_error("functions.php.inc::reset_db($user,$ticket,$settings)",'mysql query failed', mysql_error());
	 	print '<LI> Deleting actions...';
		$result = mysql_query("DELETE FROM $GLOBALS[mysql_prefix]action") or do_error("functions.php.inc::reset_db($user,$ticket,$settings)", 'mysql query failed', mysql_error());
	 	print '<LI> Deleting notifies...';
		$result = mysql_query("DELETE FROM $GLOBALS[mysql_prefix]notify") or do_error("functions.php.inc::reset_db($user,$ticket,$settings)", 'mysql query failed', mysql_error());
	}

	if($user)
	{
		$result = mysql_query("DELETE FROM $GLOBALS[mysql_prefix]notify") or do_error('reset_db()::mysql_query(delete notifies)', 'mysql query failed', mysql_error());
		print '<LI> Deleting users and notifies...';
		$result = mysql_query("DELETE FROM $GLOBALS[mysql_prefix]user") or do_error('reset_db()::mysql_query(delete users)', 'mysql query failed', mysql_error());
		$query = "INSERT INTO $GLOBALS[mysql_prefix]user (user,info,level,passwd) VALUES('admin','Administrator',$GLOBALS[LEVEL_ADMINISTRATOR],PASSWORD('admin'))";
		$result = mysql_query($query) or do_error('reset_db()::mysql_query(add admin user)', 'mysql query failed', mysql_error());
		print '<LI> Admin account created with password \'admin\'';
	}

	if($settings)
	{
		print '<LI> Deleting settings...';
		$result = mysql_query("DELETE FROM $GLOBALS[mysql_prefix]settings") or do_error('reset_db()::mysql_query(delete settings)', 'mysql query failed', mysql_error());

		//fix all default settings
		do_insert_settings('version','0.71');
		do_insert_settings('host','www.yourdomain.com');
		do_insert_settings('framesize','50');
		do_insert_settings('frameborder','1');
		do_insert_settings('allow_notify','0');
		do_insert_settings('login_banner','Welcome to PHP Ticket');
		do_insert_settings('ticket_per_page','0');
		do_insert_settings('abbreviate_description','65');
		do_insert_settings('abbreviate_affected','30');
		do_insert_settings('validate_email','1');
		do_insert_settings('allow_custom_tags','0');
		do_insert_settings('restrict_user_tickets','0');
		do_insert_settings('restrict_user_add','0');
		do_insert_settings('date_format','Y-m-d h:ia');
		do_insert_settings('ticket_table_width','640');
		do_insert_settings('guest_add_ticket','0');
		do_insert_settings('military_time','0');
		do_insert_settings('imap_support','0');
		do_insert_settings('imap_server','localhost');
		do_insert_settings('imap_type','1');
		do_insert_settings('imap_port','143');
		do_insert_settings('imap_folder','INBOX');
		do_insert_settings('imap_delete','0');
		do_insert_settings('imap_account','user');
		do_insert_settings('imap_password','pass');
		do_insert_settings('cookie_lifetime','0');
	}

	print '<LI> Database reset done<BR><BR>';
}

/* show database/user stats */
function show_stats()
{
	//get variables from db
	$user_in_db 		= mysql_num_rows(mysql_query("SELECT * FROM $GLOBALS[mysql_prefix]user WHERE level=$GLOBALS[LEVEL_USER]"));
	$admin_in_db 		= mysql_num_rows(mysql_query("SELECT * FROM $GLOBALS[mysql_prefix]user WHERE level=$GLOBALS[LEVEL_ADMINISTRATOR]"));
	$guest_in_db 		= mysql_num_rows(mysql_query("SELECT * FROM $GLOBALS[mysql_prefix]user WHERE level=$GLOBALS[LEVEL_GUEST]"));
	$ticket_in_db 		= mysql_num_rows(mysql_query("SELECT * FROM $GLOBALS[mysql_prefix]ticket"));
	$ticket_open_in_db 	= mysql_num_rows(mysql_query("SELECT * FROM $GLOBALS[mysql_prefix]ticket WHERE status='$GLOBALS[STATUS_OPEN]'"));

	print '<TABLE BORDER="0"><TR>';
	print "<TR><TD CLASS=\"td_label\">Database:</TD><TD ALIGN=\"right\"><B>$GLOBALS[mysql_db]</B> on <B>$GLOBALS[mysql_host]</B> running mysql <B>".mysql_get_server_info()."</B></TD></TR>";
	print "<TR><TD CLASS=\"td_label\">Tickets in database:&nbsp;&nbsp;</TD><TD ALIGN=\"right\">$ticket_open_in_db open, ".($ticket_in_db - $ticket_open_in_db)." closed, $ticket_in_db total</TD></TR>";
	print "<TR><TD CLASS=\"td_label\">Users in database:</TD><TD ALIGN=\"right\">$user_in_db user(s), $admin_in_db administrator(s), $guest_in_db guest(s), ".($user_in_db+$admin_in_db+$guest_in_db)." total</TD></TR>";
	print '<TR><TD CLASS="td_label">Current User:</TD><TD ALIGN="right">';
	print $_SESSION['user_name'];

	switch($_SESSION['level'])
	{
		case $GLOBALS['LEVEL_ADMINISTRATOR']: 	print ' (administrator)'; 	break;
		case $GLOBALS['LEVEL_USER']: 			print ' (user)'; 			break;
		case $GLOBALS['LEVEL_GUEST']: 			print ' (guest)'; 			break;
	}

	print "</TD></TR><TR><TD CLASS=\"td_label\">Sorting:</TD><TD ALIGN=\"right\">";
	$_SESSION['ticket_per_page'] == 0 ? print "unlimited" : print $_SESSION['ticket_per_page'];
	print " ticket(s)/page, order by '<B>".str_replace('DESC','descending',$_SESSION['sortorder'])."</B>'</TD></TR>";
	print '</TABLE>';
}

/* list users */
function list_users()
{
	$result = mysql_query("SELECT * FROM $GLOBALS[mysql_prefix]user") or do_error('list_users()::mysql_query()', 'mysql query failed', mysql_error());
	if (!check_for_rows("SELECT id FROM $GLOBALS[mysql_prefix]user")) { print '<B>[no users found]</B><BR>'; return; }

	print "<TABLE BORDER=\"0\"><TR><TD><B>ID&nbsp;&nbsp;&nbsp;</B></TD><TD><B>User&nbsp;&nbsp;&nbsp;</B></TD><TD><B>Description&nbsp;&nbsp;&nbsp;</B></TD><TD><B>Level&nbsp;&nbsp;&nbsp;</B></TD></TR>";

	while($row = mysql_fetch_array($result))
	{
		print "<TR><TD><A HREF=\"config.php?id=$row[id]\">#$row[id]</A></TD><TD>$row[user]</TD><TD>$row[info]</TD><TD>";

		switch($row['level'])
		{
			case $GLOBALS['LEVEL_ADMINISTRATOR']:	print "administrator";	break;
			case $GLOBALS['LEVEL_USER']:			print "user";			break;
			case $GLOBALS['LEVEL_GUEST']:			print "guest";			break;
		}

		print "</TD></TR>\n";
	}

	print '</TABLE><BR>';
}

/* reload session variables from db after profile update */
function reload_session()
{
	$query 	= "SELECT * FROM $GLOBALS[mysql_prefix]user WHERE user='$_SESSION[user_name]'";
	$result = mysql_query($query) or do_error("reload_session(select user)::mysql_query()", 'mysql query failed', mysql_error());
	$row 	= mysql_fetch_array($result);
	$_SESSION['level'] 				= $row[level];
	$_SESSION['reporting']	 		= $row[reporting];
	$_SESSION['ticket_per_page'] 	= $row[ticket_per_page];
	$_SESSION['sortorder']			= "$row[sortorder] ".($row[sort_desc] ? "DESC" : "");
}

/* insert new values into settings table */
function do_insert_settings($name,$value)
{
	$query = "INSERT INTO $GLOBALS[mysql_prefix]settings (name,value) VALUES('$name','$value')";
	$result = mysql_query($query) or do_error("insert_into_settings(n:$name,v:$value)::mysql_query()", 'mysql query failed', mysql_error());
}

/* validate email, code courtesy of Jerrett Taylor */
function validate_email($email)
{
	//really validate?
	if (!get_variable('validate_email'))
	{
		$return['status'] = true;  $return['msg'] = $email;
		return $return;
	}

	$return = array();

	if (!eregi("^[0-9a-z_]([-_.]?[0-9a-z])*@[0-9a-z][-.0-9a-z]*\\.[a-z]{2,4}[.]?$",$email, $check))
	{
		$return['status'] = false;
		$return['msg'] = 'invalid e-mail address';
		return $return;
	}

	$host = substr(strstr($check[0], '@'), 1);
	if (!checkdnsrr($host.'.',"MX"))
	{
		$return['status'] = false;
		$return['msg'] = "invalid host ($host)";
		return $return;
	}

	$return['status'] = true; $return['msg'] = $email;
	return $return;
}

/* get help for settings */
function get_setting_help($setting)
{
	switch($setting)
	{
		case 'version': 				return 'version number of phpticket'; break;
		case 'host': 					return 'hostname where phpticket is run'; break;
		case 'framesize': 				return 'size of the top frame in pixels'; break;
		case 'frameborder': 			return 'size of frameborder'; break;
		case 'allow_notify': 			return 'allow/deny notification of ticket updates'; break;
		case 'login_banner': 			return 'message shown at login screen'; break;
		case 'abbreviate_description': 	return 'abbreviates descriptions at this number when listing tickets, 0 to turn off'; break;
		case 'validate_email': 			return 'simple email validation check for notifies'; break;
		case 'abbreviate_affected': 	return 'abbreviates \'affected\' string at this number when listing tickets, 0 to turn off'; break;
		case 'allow_custom_tags': 		return 'enable/disable use of custom tags for rowbreak, italics etc.'; break;
		case 'restrict_user_tickets': 	return 'restrict to showing only tickets to current user'; break;
		case 'restrict_user_add': 		return 'restrict user to only post tickets as himself'; break;
		case 'reporting': 				return 'enable/disable automatic ticket reporting (see help for more info)'; break;
		case 'date_format': 			return 'format dates according to php function date() variables'; break;
		case 'ticket_table_width': 		return 'width of table when showing ticket'; break;
		case 'ticket_per_page': 		return 'number of tickets per page to show'; break;
		case 'guest_add_ticket': 		return 'enable guest users to add tickets'; break;
		case 'imap_support': 			return 'enable/disable IMAP support'; break;
		case 'military_time': 			return 'enter dates as military time (no am/pm)'; break;
		case 'imap_server': 			return 'address to IMAP server'; break;
		case 'imap_type': 				return 'IMAP protocol type, default is 1, see README for more info'; break;
		case 'imap_folder': 			return 'IMAP folder, default is INBOX'; break;
		case 'imap_delete': 			return 'delete emails from server after import'; break;
		case 'imap_account': 			return 'IMAP server account name'; break;
		case 'imap_password': 			return 'IMAP server account password'; break;
		case 'imap_port': 				return 'IMAP server port'; break;
		case 'cookie_lifetime': 		return 'login cookie lifetime in seconds, default is 0'; break;
		default: 						return "No help for '$setting'"; break;
	}
}

?>
