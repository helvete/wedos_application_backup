<?
error_reporting(0);
require_once('mysql.inc.php');
$cleanP = array(
	'frm_user', 'frm_passwd',
);
$cleanG = array(
	'logout', 'print', 'id', 'sort_by_field', 'status', 'notify', 'imap',
	'profile', 'optimize', 'reset', 'settings', 'edit', 'adduser', 'q', 'go'
);
foreach ($cleanP as $parX) {
	$_POST[$parX] = empty($_POST[$parX])
		? null
		: $_POST[$parX];
}
foreach ($cleanG as $parX) {
	$_GET[$parX] = empty($_GET[$parX])
		? null
		: $_GET[$parX];
}

// Fix for removed Session functions
function fix_session_register(){
    function session_register(){
        $args = func_get_args();
        foreach ($args as $key){
            $_SESSION[$key]=$key;
        }
    }
    function session_is_registered($key){
        return isset($_SESSION[$key]);
    }
    function session_unregister($key){
        unset($_SESSION[$key]);
    }
}
if (!function_exists('session_register')) fix_session_register();

/* constants - do NOT change */
$GLOBALS['STATUS_CLOSED'] 			= 1;
$GLOBALS['STATUS_OPEN']   			= 2;
$GLOBALS['STATUS_PENDING']   		= 3;

$GLOBALS['NOTIFY_ACTION'] 			= 'Added Action';
$GLOBALS['NOTIFY_TICKET'] 			= 'Ticket Update';

$GLOBALS['ACTION_DESCRIPTION']		= 1;
$GLOBALS['ACTION_OPEN'] 			= 2;
$GLOBALS['ACTION_CLOSE'] 			= 3;
$GLOBALS['ACTION_OWNER'] 			= 4;
$GLOBALS['ACTION_PROBLEMSTART'] 	= 5;
$GLOBALS['ACTION_PROBLEMEND'] 		= 6;
$GLOBALS['ACTION_AFFECTED'] 		= 7;
$GLOBALS['ACTION_SCOPE'] 			= 8;
$GLOBALS['ACTION_SEVERITY']			= 9;
$GLOBALS['ACTION_COMMENT']			= 10;

$GLOBALS['SEVERITY_NORMAL'] 		= 0;
$GLOBALS['SEVERITY_MEDIUM'] 		= 1;
$GLOBALS['SEVERITY_HIGH'] 			= 2;

$GLOBALS['LEVEL_ADMINISTRATOR'] 	= 1;
$GLOBALS['LEVEL_USER'] 				= 2;
$GLOBALS['LEVEL_GUEST'] 			= 3;
$GLOBALS['LEVEL_EXTERNAL'] 			= 4;

/* connect to mysql database */
@mysql_connect($GLOBALS['mysql_host'], $GLOBALS['mysql_user'], $GLOBALS['mysql_passwd']) or do_error('functions.inc.php::mysql_open()', 'mysql_connect() failed', mysql_error(),__FILE__,__LINE__);
@mysql_select_db($GLOBALS['mysql_db']) or do_error('functions.inc.php::mysql_select_db()', 'mysql_select_db() failed', @mysql_error());

/* load imap inc file if setting is turned on */
if (get_variable('imap_support')) require_once('imap.inc.php');

/* check for mysql tables, if non-existent, point to install.php */
if (!mysql_table_exists("$GLOBALS[mysql_prefix]ticket")) 	{ print "MySQL table '$GLOBALS[mysql_prefix]ticket' is missing<br>"; $failed = 1; }
if (!mysql_table_exists("$GLOBALS[mysql_prefix]action")) 	{ print "MySQL table '$GLOBALS[mysql_prefix]action' is missing<br>"; $failed = 1; }
if (!mysql_table_exists("$GLOBALS[mysql_prefix]notify")) 	{ print "MySQL table '$GLOBALS[mysql_prefix]notify' is missing<br>"; $failed = 1; }
if (!mysql_table_exists("$GLOBALS[mysql_prefix]settings")) 	{ print "MySQL table '$GLOBALS[mysql_prefix]settings' is missing<br>"; $failed = 1; }
if (!mysql_table_exists("$GLOBALS[mysql_prefix]user")) 		{ print "MySQL table '$GLOBALS[mysql_prefix]user' is missing<br>"; $failed = 1; }
if (!empty($failed))
{
	print "Some or several tables missing in database, please run <a href=\"install.php\">install.php</a> if you haven't or check your database.";
	exit();
}

//function session_is_registered($x){return isset($_SESSION['$x']);}

/* check if mysql table exists */
function mysql_table_exists($table)
{
	$query = "SELECT COUNT(*) FROM $table";
	$result = @mysql_query($query);
	$num_rows = @mysql_num_rows($result);
	if($num_rows)
		return TRUE;
	else
		return FALSE;
}

function get_issue_date($id)
{
	$result = @mysql_query("SELECT date FROM $GLOBALS[mysql_prefix]ticket WHERE id='$id'");
	$row = @mysql_fetch_array($result);
	print $row[date];
}

/* check sql query for returning rows, courtesy of Micah Snyder */
function check_for_rows($query)
{
	if($sql = @mysql_query($query))
	{
		if(@mysql_num_rows($sql) !== 0)
			return @mysql_num_rows($sql);
		else
			return false;
	}
	else
		return false;
}

/* add ticket */
function add_ticket($description,$affected,$scope,$problemstart,$problemend,$status,$severity,$owner)
{
	//strip values from unwanted characters
	$description 	= strip_html($description);
	$affected		= str_replace('&gt;',')',str_replace('&lt;','(',$affected));
	$affected   	= strip_html($affected);
	$scope       	= strip_html($scope);

	$query  = "INSERT INTO $GLOBALS[mysql_prefix]ticket (affected,scope,owner,description,problemstart,problemend,status,date,severity) VALUES('$affected','$scope','$owner','$description','$problemstart','$problemend',$status,NOW(),'$severity')";
	$result = @mysql_query($query) or do_error("add_ticket($description, $affected,$scope,$problemstart,$problemend,$status,$date,$severity,$owner)::mysql_query()", 'mysql query failed', @mysql_error());
	//report_action($GLOBALS[ACTION_OPEN],0,0,$_POST[$frm_owner]);
}

/* list tickets */
function list_tickets($sort_by_field='',$sort_value='',$php_file='')
{

	$cookiedata = session_get_cookie_params();
	print "<!-- lifetime: $cookiedata[lifetime] -->";

	//fix status, if not set then show all open tickets
	if (empty($_GET['status'])) $_GET['status'] = $GLOBALS['STATUS_OPEN'];

	//fix sort orders
	if(empty($_GET['sortby']))
		$order_by = $_SESSION['sortorder'];
	else
		$order_by = $_GET['sortby'];

	//fix limits according to setting "ticket_per_page"
	$limit = '';
	if ($_SESSION['ticket_per_page'] && (check_for_rows("SELECT id FROM $GLOBALS[mysql_prefix]ticket") > $_SESSION['ticket_per_page']))
	{
		if ($_GET['offset'])
			$limit = "LIMIT $_GET[offset],$_SESSION[ticket_per_page]";
		else
			$limit = "LIMIT 0,$_SESSION[ticket_per_page]";
	}

	//fix showing only user tickets
	if (get_variable('restrict_user_tickets') && !(is_administrator()))
		$restrict_ticket = "AND owner=$_SESSION[user_id]";
	else $restrict_ticket = '';

	//sort by field?
	if ($sort_by_field && $sort_value)
		$query = "SELECT *,UNIX_TIMESTAMP(problemstart) AS problemstart,UNIX_TIMESTAMP(problemend) AS problemend,UNIX_TIMESTAMP(date) AS date FROM $GLOBALS[mysql_prefix]ticket WHERE $sort_by_field='$sort_value' $restrict_ticket ORDER BY $order_by";
	else
		$query = "SELECT *,UNIX_TIMESTAMP(problemstart) AS problemstart,UNIX_TIMESTAMP(problemend) AS problemend,UNIX_TIMESTAMP(date) AS date FROM $GLOBALS[mysql_prefix]ticket WHERE status='$_GET[status]' $restrict_ticket ORDER BY $order_by $limit";

	$result = @mysql_query($query) or do_error('list_tickets(select)::mysql_query()', 'mysql query failed', @mysql_error());

	$querystring = '';
	print "<TABLE BORDER=\"0\" WIDTH=\"97%\"><TR><TD WIDTH=\"1%\" NOWRAP><A HREF=\"$php_file?sortby=date$querystring&status=$_GET[status]\" CLASS=\"td_link\">Date</A></TD><TD WIDTH=\"64%\"><A HREF=\"$php_file?sortby=description$querystring&status=$_GET[status]\" CLASS=\"td_link\">Description</A></TD><TD WIDTH=\"35%\"><A HREF=\"$php_file?sortby=affected$querystring&status=$_GET[status]\" CLASS=\"td_link\">Affected</A></TD></TR>\n";

	while ($row = @mysql_fetch_array($result))
	{
		if ($row['description'] == '') $row['description'] = '[no description]';

		//do abbreviations on description, affected if neccesary
		if (get_variable('abbreviate_description'))
			if (strlen($row['description']) > get_variable('abbreviate_description')) $row['description'] = substr($row['description'],0,get_variable('abbreviate_description')).'...';

		if (get_variable('abbreviate_affected'))
			if (strlen($row['affected']) > get_variable('abbreviate_affected')) $row['affected'] = substr($row['affected'],0,get_variable('abbreviate_affected')).'...';

		//color tickets by severity
		switch($row[severity])
		{
		 	case $GLOBALS['SEVERITY_MEDIUM']: $severityclass='severity_medium'; break;
			case $GLOBALS['SEVERITY_HIGH']: $severityclass='severity_high'; break;
			default: $severityclass=''; break;
		}

		print "<TR><TD NOWRAP VALIGN=\"top\">".format_date($row[date])."&nbsp;&nbsp;</TD><TD VALIGN=\"top\"><A HREF=\"main.php?id=$row[id]\" CLASS=\"$severityclass\">".custom_tags($row[description])."</A></TD>";
		print "<TD VALIGN=\"top\">$row[affected]</TD></TR>\n";
	}

	if (@mysql_num_rows($result) <= 0)
		print '<TR><TD></TD><TD>[no tickets in this section]</TD><TD></TR></TR></TABLE>';
	else
	{
		print '</TABLE><BR>';
		//write out ticket navigation, very messy code, i know :/
		if ($limit)
		{
			if ($_GET[offset] - $_SESSION[ticket_per_page] >= 0) $offset = $_GET[offset] - $_SESSION[ticket_per_page];
			if ($_GET[offset]) print "<A HREF=\"main.php?offset=$offset&status=$_GET[status]\">previous &nbsp;</A>";

			$offset = $_GET[offset] + $_SESSION[ticket_per_page];
			if (check_for_rows("SELECT id FROM $GLOBALS[mysql_prefix]ticket WHERE status='$_GET[status]' $restrict_ticket") > $offset) print "<A HREF=\"main.php?offset=$offset&status=$_GET[status]\">next</A>";
		}
	}
}

/* show specific ticked by ID */
function show_ticket($id,$print='false')
{
	/* sanity check */
	if ($id == '' OR $id <= 0 OR !check_for_rows("SELECT * FROM $GLOBALS[mysql_prefix]ticket WHERE id='$id'"))
	{
		print "Invalid Ticket ID: '$id'<br>";
		return;
	}

	if (get_variable('restrict_user_tickets') && !(is_administrator()))
		$restrict_user = "AND owner='$_SESSION[user_id]'";

	$result = @mysql_query("SELECT *,UNIX_TIMESTAMP(problemstart) AS problemstart,UNIX_TIMESTAMP(problemend) AS problemend,UNIX_TIMESTAMP(date) AS date FROM $GLOBALS[mysql_prefix]ticket WHERE ID='$id' $restrict_user") or do_error('show_ticket()::mysql_query()', 'mysql query failed', @mysql_error());

	//no tickets? print "error" or "restricted user rights"
	if (!@mysql_num_rows($result))
	{
		print "<FONT CLASS=\"warn\">No such ticket or user access to ticket is denied</FONT>";
		exit();
	}

	$row = @mysql_fetch_array($result);

	if ($print == 'true')
	{
		print '<TABLE BORDER="0">';
		print "<TR><TD class=\"print_TD\"><B>ID:				</B></TD><TD class=\"print_TD\">$row[id]</TD></TR>";
		print "<TR><TD class=\"print_TD\"><B>Owner: 			</B></TD><TD class=\"print_TD\">".get_owner($row[owner])."</TD></TR>";
		print "<TR><TD class=\"print_TD\"><B>Issued: 			</B></TD><TD class=\"print_TD\">".format_date($row[date])."</TD></TR>";
		print "<TR><TD class=\"print_TD\"><B>Status: 			</B></TD><TD class=\"print_TD\">".get_status($row[status])."</TD></TR>";
		print "<TR><TD class=\"print_TD\"><B>Severity:			</B></TD><TD class=\"print_TD\">".get_severity($row[severity])."</TD></TR>";
		print "<TR><TD class=\"print_TD\"><B>Affected: 			</B></TD><TD class=\"print_TD\">$row[affected]</TD></TR>";
		print "<TR><TD class=\"print_TD\"><B>Problem Starts: &nbsp;&nbsp;&nbsp;&nbsp; 	</B></TD><TD class=\"print_TD\">".format_date($row[problemstart])."</TD></TR>";
		print "<TR><TD class=\"print_TD\"><B>Problem Ends:		</B></TD><TD class=\"print_TD\">".format_date($row[problemend])."</TD></TR>";
		print "<TR><TD class=\"print_TD\" VALIGN=\"top\"><B>Description: 		</B></TD><TD class=\"print_TD\">".custom_tags($row[description])."</TD></TR>";

		/* list actions belonging to ticket */
        $query = "SELECT *,UNIX_TIMESTAMP(date) AS date FROM $GLOBALS[mysql_prefix]action WHERE ticket_id = '$id' ORDER BY date";
        $result = @mysql_query($query) or do_error('show_ticket(print ticket)::mysql_query()', 'mysql query failed', @mysql_error());
        print '</TABLE>';
		if (@mysql_num_rows($result))
		{
			print '<BR><TABLE BORDER="0"><TR><TD class="print_TD"><B>Actions:</B></TD></TR>';
        	while ($row = @mysql_fetch_array($result))
			{
				//if (!$_SESSION[reporting] && $row[action_type] != $GLOBALS[ACTION_COMMENT]) continue;
				print "<TR><TD class=\"print_TD\">".format_date($row[date])." by <B>".get_owner($row[user])."</B> ".($row[action_type]!=$GLOBALS[ACTION_COMMENT] ? "*" : "-")." ".custom_tags(wordwrap($row['description']))."</TR></TD>";
			}
			print '</TABLE></BODY></HTML>';
		}
		return;
	}

	?>
	<TABLE BORDER="0" WIDTH="<?=get_variable('ticket_table_width');?>"><TR><TD CLASS="print_TD">
	<TABLE BORDER="0" WIDTH="100%"><TR><TD VALIGN="top" CLASS="td_label">ID:</TD>
	<TD VALIGN="top"><?=$row[id];?></TD><TD VALIGN="top" CLASS="td_label">Scope:</TD><TD VALIGN="top"><A HREF="main.php?sort_by_field=scope&sort_value=<?=$row[scope];?>"><?=$row[scope];?></A></TD></TR>
	<TR><TD VALIGN="top" CLASS="td_label">Issue Date:</TD><TD WIDTH="230" VALIGN="top"><?=format_date($row[date]);?></TD><TD VALIGN="top" CLASS="td_label">Owner: &nbsp;</TD><TD WIDTH="180" VALIGN="top"><A HREF="main.php?sort_by_field=owner&sort_value=<?=$row[owner];?>"><?=get_owner($row[owner]);?></A></TD></TR>
	<TR><TD VALIGN="top" CLASS="td_label">Problem Start: &nbsp;</TD><TD VALIGN="top"><?=format_date($row[problemstart]);?></TD><TD VALIGN="top" CLASS="td_label">Problem End: &nbsp;</TD><TD VALIGN="top"><?=format_date($row[problemend]);?></TD></TR>
	<TR><TD VALIGN="top" CLASS="td_label">Status:</TD><TD VALIGN="top"><?=get_status($row[status]);?></TD><TD VALIGN="top" CLASS="td_label">Severity:<TD><A HREF="main.php?sort_by_field=severity&sort_value=<?=$row[severity];?>"><?=get_severity($row[severity]);?></A></TD></TR>
	</TABLE><BR>

	<TABLE BORDER="0" WIDTH="100%">
	<TR><TD WIDTH="1%" VALIGN="top" CLASS="td_label">Affected:<TD WIDTH="99%" VALIGN="top"><A HREF="main.php?sort_by_field=affected&sort_value=<?=$row[affected];?>"><?=$row[affected];?></TD></TR>
	<TR><TD WIDTH="1%" VALIGN="top" CLASS="td_label">Description:&nbsp;&nbsp;</TD><TD WIDTH="99%" VALIGN="top"><?=custom_tags($row[description]);?></TD></TR>
	</TABLE><BR>
	<?

	/* list actions belonging to ticket */
	$query = "SELECT *,UNIX_TIMESTAMP(date) AS date FROM $GLOBALS[mysql_prefix]action WHERE ticket_id='$id' ORDER BY date";
	$result = @mysql_query($query) or do_error('show_ticket(list actions)::mysql_query()', 'mysql query failed', @mysql_error());
	print '<TABLE BORDER="0">';

	while ($row = @mysql_fetch_array($result))
	{
		//if (!$_SESSION[reporting] && $row[action_type] != $GLOBALS[ACTION_COMMENT]) continue;
		print '<TR><TD VALIGN="top" WIDTH="1%" NOWRAP CLASS="td_label">';
		if (!$no_action) print 'Actions: &nbsp;&nbsp;';
		print "</TD><TD WIDTH=\"99%\">".format_date($row[date])."</B> by <B>".get_owner($row[user])."</B> ".($row[action_type]!=$GLOBALS[ACTION_COMMENT] ? "* " : "- ").custom_tags($row[description])."</TD></TR>\n";
		$no_action = 1; /* simple cosmetics fix */
	}
	print '</TABLE></TD></TR></TABLE>';
}

/* alter ticket status */
function set_ticket_status($status,$id)
{
	$query = "UPDATE $GLOBALS[mysql_prefix]ticket SET status='$status' WHERE ID='$id'";
	$result = @mysql_query($query) or do_error("set_ticket_status(s:$status, id:$id)::mysql_query()", 'mysql query failed', @mysql_error());
}

/* format date to defined type */
function format_date($date)
{
	//return date(get_variable("date_format"),strtotime($date));
	return date(get_variable("date_format"),$date);
}

/* return status from code */
function get_status($status)
{
	switch($status)
	{
		case 1: return 'Closed';
		case 2: return 'Open';
	}
}

/* get owner name from id */
function get_owner($id)
{
	$result	= @mysql_query("SELECT user FROM $GLOBALS[mysql_prefix]user WHERE id='$id'") or do_error("get_owner(i:$id)::mysql_query()", 'mysql query failed', @mysql_error());
	$row	= @mysql_fetch_array($result);
	return $row['user'];
}

/* return severity string from value */
function get_severity($severity)
{
	switch($severity)
	{
		case $GLOBALS['SEVERITY_MEDIUM']: return "medium"; break;
		case $GLOBALS['SEVERITY_HIGH']: return "high"; break;
		default: return "normal"; break;
	}
}

/* strip HTML tags/special characters to prevent bad HTML, CrossSiteScripting etc */
function strip_html($html_string)
{
	//strip all "real" html and convert special characters first
	$html_string = strip_tags(htmlspecialchars($html_string));
	return $html_string;
}

/* convert custom tags (format [tag]) to real HTML tags */
function custom_tags($html_string)
{
	if (get_variable('allow_custom_tags'))
	{
		$html_string = str_replace('[b]', 				'<b>', $html_string);
		$html_string = str_replace('[/b]', 				'</b>', $html_string);
		$html_string = str_replace('[i]', 				'<i>', $html_string);
		$html_string = str_replace('[/i]', 				'</i>', $html_string);
		$html_string = str_replace('[li]',				'<li>', $html_string);
		$html_string = str_replace('[br]', 				'<br>', $html_string);
		$html_string = str_replace('[color=orange]', 	'<font class="text_orange">', $html_string);
		$html_string = str_replace('[color=blue]', 		'<font class="text_blue">', $html_string);
		$html_string = str_replace('[color=red]', 		'<font class="text_red">', $html_string);
		$html_string = str_replace('[color=green]', 	'<font class="text_green">', $html_string);
		$html_string = str_replace('[size=small]', 		'<font class="text_small">', $html_string);
		$html_string = str_replace('[size=medium]', 	'<font class="text_medium">', $html_string);
		$html_string = str_replace('[size=big]', 		'<font class="text_big">', $html_string);
		$html_string = str_replace('[/color]', 			'</font>', $html_string);
		$html_string = str_replace('[/size]', 			'</font>', $html_string);
		$html_string = str_replace('[pre]', 			'<pre>', $html_string);
		$html_string = str_replace('[/pre]', 			'</pre>', $html_string);
	}
	else
	{
		$html_string = str_replace('[b]', 				'', $html_string);
		$html_string = str_replace('[/b]', 				'', $html_string);
		$html_string = str_replace('[i]', 				'', $html_string);
		$html_string = str_replace('[/i]', 				'', $html_string);
		$html_string = str_replace('[li]',				'', $html_string);
		$html_string = str_replace('[br]', 				'', $html_string);
		$html_string = str_replace('[color=orange]', 	'', $html_string);
		$html_string = str_replace('[color=blue]', 		'', $html_string);
		$html_string = str_replace('[color=red]', 		'', $html_string);
		$html_string = str_replace('[color=green]', 	'', $html_string);
		$html_string = str_replace('[size=small]', 		'', $html_string);
		$html_string = str_replace('[size=medium]', 	'', $html_string);
		$html_string = str_replace('[size=big]', 		'', $html_string);
		$html_string = str_replace('[/size]', 			'', $html_string);
	}
	return $html_string;
}

/* notify user check, $action is the action that triggered the notify, edit, close etc */
function notify_user($ticket_id,$action)
{
	//should we notify?
	if (get_variable('allow_notify') != '1') return;

	//lookup notifies in "notify" table
	$query = "SELECT * FROM $GLOBALS[mysql_prefix]notify WHERE ticket_id='$ticket_id'";
	$result = @mysql_query($query) or do_error("notify_user(i:$ticket_id,$action)::mysql_query(SELECT FROM $GLOBALS[mysql_prefix]notify)", 'mysql query failed', @mysql_error());
	while($row = @mysql_fetch_array($result))
	{
		//is it the right action?
		if (($action == $GLOBALS['NOTIFY_ACTION'] AND $row[on_action]) OR ($action == $GLOBALS['NOTIFY_TICKET'] AND $row[on_ticket]))
		{
			//notify by email
			if (strlen($row['email_address']))
			{
				$ticket_result = @mysql_query("SELECT * FROM $GLOBALS[mysql_prefix]ticket WHERE id='$ticket_id'") or do_error("notify_user(i:$ticket_id,$action)::mysql_query(SELECT FROM $GLOBALS[mysql_prefix]ticket)", 'mysql query failed', @mysql_error());
				$t_row = @mysql_fetch_array($ticket_result);
				$message  = "PHP Ticket on ".get_variable('host')."\n";
				$message .= "This message has been sent to you because you are subscribed to be notified of updates to this ticket.\n\n";
				$message .= "Notify Action: $action\n";
				$message .= "Ticket ID: $t_row[id]\n";
				$message .= "Ticket Scope: $t_row[scope]\n";
				$message .= "Ticket Owner: ".get_owner($t_row['owner'])."\n";
				$message .= "Ticket Status: ".get_status($t_row['status'])."\n";
				$message .= "Ticket Affected: $t_row[affected]\n";
				$message .= "Ticket Problem Start: $t_row[problemstart]\n";
				$message .= "Ticket Problem End: $t_row[problemend]\n";
				$message .= "Ticket Description: ".wordwrap($t_row['description'])."\n";

				//add actions to message
				if(check_for_rows("SELECT * FROM $GLOBALS[mysql_prefix]action WHERE ticket_id='$ticket_id' ORDER BY DATE"))
				{
					$message .= "\nActions:\n";
					$query = "SELECT * FROM $GLOBALS[mysql_prefix]action WHERE ticket_id='$ticket_id'";
					$ticket_result = @mysql_query($query) or do_error("notify_user(i:$ticket_id,$action)::mysql_query(SELECT FROM $GLOBALS[mysql_prefix]action)", 'mysql query failed', @mysql_error());
					while($t_row = @mysql_fetch_array($ticket_result))
						$message .= "$t_row[date] - ".wordwrap($t_row['description'])."\n";
				}

				$message .= "\nThis is an automated message, please do not reply.";
				mail($row[email_address],'PHP Ticket Notification', $message);
			}

			//notify by running program
			if (strlen($row['execute_path']))
			{
				/* not done yet */
			}
		}
		else
		{
			/* no matching action */
			return;
		}
	}
}

/* get variable from settings table in db */
function get_variable($name)
{
	$result = @mysql_query("SELECT name,value FROM $GLOBALS[mysql_prefix]settings WHERE name='$name'") or do_error("get_variable(n:$name)::mysql_query()", 'mysql query failed', @mysql_error());
	$row 	= @mysql_fetch_array($result);
	return $row['value'];
}

/* do login/session code */
function do_login($requested_page)
{
	session_start();

	if(!session_is_registered('auth'))
	{
		if(check_for_rows("SELECT user,passwd FROM $GLOBALS[mysql_prefix]user WHERE user='$_POST[frm_user]' AND passwd=PASSWORD('$_POST[frm_passwd]')"))
		{
			$auth = True;
			//session_start();
			session_register('auth');

			$query 	= "SELECT * FROM $GLOBALS[mysql_prefix]user WHERE user='$_POST[frm_user]'";
			$result = @mysql_query($query) or do_error("do_login(get_permissions)::mysql_query()", 'mysql query failed', @mysql_error());
			$row 	= @mysql_fetch_array($result);

			if ($row['sortorder'] == NULL) $row['sortorder'] = "date";

			$_SESSION['user_name']     		= $_POST['frm_user'];
			$_SESSION['user_id']     		= $row['id'];
			$_SESSION['level'] 				= $row['level'];
			$_SESSION['reporting']	 		= $row['reporting'];
			$_SESSION['ticket_per_page'] 	= $row['ticket_per_page'];
			$_SESSION['sortorder']			= "{$row['sortorder']} ".($row['sort_desc'] ? "DESC" : "");
			session_set_cookie_params(get_variable('cookie_lifetime'));
			return;
		}
		else
		{
			?>
			<HTML>
			<HEAD>
			<LINK REL=StyleSheet HREF="default.css" TYPE="text/css">
			</HEAD>
			<CENTER><BR>
			<? if(get_variable('version') != '') print get_variable('login_banner')."<BR><BR>"; ?>
			</FONT><FORM METHOD="post" ACTION="<?=$requested_page;?>">
			<TABLE BORDER="0">
			<? if($_POST['frm_user'] != '') print '<TR><TD COLSPAN="2"><FONT CLASS="warn">Login failed. Try again.</FONT></TD></TR>'; ?>
			<TR><TD CLASS="td_label">User:</TD><TD><INPUT TYPE="text" NAME="frm_user" MAXLENGTH="255" SIZE="30"></TD></TR>
			<TR><TD CLASS="td_label">Password: &nbsp;&nbsp;</TD><TD><INPUT TYPE="password" NAME="frm_passwd" MAXLENGTH="255" SIZE="30"></TD></TR>
			<TR><TD></TD><TD><INPUT TYPE="submit" VALUE="Log In"></TD></TR>
			</FORM></CENTER>
			</HTML>
			<?
			exit();
		}
	}
}

/* logout - destroy session */
function do_logout()
{
	session_start();
	session_unset();
	session_destroy();
	do_login('main.php');
}

/* raise an error event */
function do_error($err_function,$err,$custom_err='',$file='',$line='')
{
	print "<FONT CLASS=\"warn\">An error occured in function '<B>$err_function</B>': '<B>$err</B>'<BR>";
	if ($file OR $line) print "Error occured in '$file' at line '$line'<BR>";
	if ($custom_err != '') print "Additional info: '<B>$custom_err</B>'<BR>";
	print '<BR>Check your MySQL connection and if the problem persist, contact the <A HREF="help.php?q=credits">author</A>.<BR>';
	die('<B>Execution stopped.</B></FONT>');
}

/* add footer with links */
function add_footer($ticket_id)
{
	print '<BR><FONT SIZE="2">';
	if (!is_guest())
	{
		print "<A HREF=\"edit.php?id=$ticket_id\">Edit Ticket</A> | ";
		print "<A HREF=\"edit.php?id=$ticket_id&delete=1\">Delete Ticket</A> | ";
		if (!is_closed($ticket_id)) print "<A HREF=\"action.php?ticket_id=$ticket_id\">Add Action</A> | ";
		print "<A HREF=\"config.php?notify=true&id=$ticket_id\">Notify</A> | ";
	}
	print "<A HREF=\"main.php?print=true&id=$ticket_id\">Print Ticket</A></FONT>";
}

/* is ticket closed? */
function is_closed($id)
{
	return check_for_rows("SELECT id,status FROM $GLOBALS[mysql_prefix]ticket WHERE id='$id' AND status='$GLOBALS[STATUS_CLOSED]'");
}

/* is user at admin level? */
function is_administrator($user_id=0)
{
	if ($user_id)
	{
		$row = @mysql_fetch_array(@mysql_query("SELECT level FROM $GLOBALS[mysql_prefix]user WHERE id='$user_id'")) or do_error("functions.php.inc::is_administrator($user_id)", 'mysql_query() failed', @mysql_error());
		if ($row[level] == $GLOBALS['LEVEL_ADMINISTRATOR']) return 1;
	}
	else
		if ($_SESSION['level'] == $GLOBALS['LEVEL_ADMINISTRATOR']) return 1;
}

/* is user at guest level? */
function is_guest($user_id=0)
{
	if ($user_id)
	{
		$row = @mysql_fetch_array(@mysql_query("SELECT level FROM $GLOBALS[mysql_prefix]user WHERE id='$user_id'")) or do_error("functions.php.inc::is_guest($user_id)", 'mysql_query() failed', @mysql_error());
		if ($row[level] == $GLOBALS['LEVEL_GUEST']) return 1;
	}
	else
		if ($_SESSION['level'] == $GLOBALS['LEVEL_GUEST']) return 1;
}

/* is user at user level? */
function is_user($user_id=0)
{
	if ($user_id)
	{
		$row = @mysql_fetch_array(@mysql_query("SELECT level FROM $GLOBALS[mysql_prefix]user WHERE id='$user_id'")) or do_error("functions.php.inc::is_user($user_id)", 'mysql_query() failed', @mysql_error());
		if ($row[level] == $GLOBALS['LEVEL_USER']) return 1;
	}
	if ($_SESSION['level'] == $GLOBALS['LEVEL_USER']) return 1;
}

/* print out date and time in dropdown menus */
function generate_date_dropdown($date_suffix,$default_date=0)
{
	//default to current date/time if no values are given
	if ($default_date)
	{
		$year  		= date('Y',$default_date);
		$month 		= date('m',$default_date);
		$day   		= date('d',$default_date);
		$minute		= date('i',$default_date);
		$meridiem	= date('a',$default_date);
		if (get_variable('military_time')) $hour = date('H',$default_date);
		else $hour = date('h',$default_date);;
	}
	else
	{
		$year 		= date('Y');
		$month 		= date('m');
		$day 		= date('d');
		$minute		= date('i');
		$meridiem	= date('a');
		if (get_variable('military_time')) $hour = date('H');
		else $hour = date('h');
	}

	print "<SELECT name=\"frm_year_$date_suffix\">";
	for($i = 2000; $i < 2011; $i++)
	{
		print "<OPTION VALUE=\"$i\"";
		$year == $i ? print " SELECTED>$i</OPTION>" : print ">$i</OPTION>";
	}

	print "</SELECT>&nbsp;<SELECT name=\"frm_month_$date_suffix\">";
	for($i = 1; $i < 13; $i++)
	{
		print "<OPTION VALUE=\"$i\"";
		$month == $i ? print " SELECTED>$i</OPTION>" : print ">$i</OPTION>";
	}

	print "</SELECT>&nbsp;<SELECT name=\"frm_day_$date_suffix\">";
	for($i = 1; $i < 32; $i++)
	{
		print "<OPTION VALUE=\"$i\"";
		$day == $i ? print " SELECTED>$i</OPTION>" : print ">$i</OPTION>";
	}
	print '</SELECT>&nbsp;&nbsp;';

	print "<!-- default:$default_date,$year-$month-$day $hour:$minute -->";

	print "<INPUT TYPE=\"text\" SIZE=\"2\" MAXLENGTH=\"2\" NAME=\"frm_hour_$date_suffix\" VALUE=\"$hour\">:";
	print "<INPUT TYPE=\"text\" SIZE=\"2\" MAXLENGTH=\"2\" NAME=\"frm_minute_$date_suffix\" VALUE=\"$minute\">";

	//put am/pm optionlist if not military time
	if (!get_variable('military_time'))
	{
		print "<SELECT NAME=\"frm_meridiem_$date_suffix\"><OPTION value=\"am\"";
		if ($meridiem == 'am') print ' selected';
		print ">am</OPTION><OPTION value=\"pm\"";
		if ($meridiem == 'pm') print ' selected';
		print ">pm</OPTION></SELECT>";
	}
}

/* insert reporting actions */
function report_action($action_type,$ticket_id,$value1='',$value2='')
{
	exit(); //not used in 0.7
	if (!get_variable('reporting')) return;

	switch($action_type)
	{
		case $GLOBALS[ACTION_AFFECTED]: $description = "Changed affected field: $value1"; break;
		case $GLOBALS[ACTION_SCOPE]: 	$description = "Changed scope field: $value1"; break;
		case $GLOBALS[ACTION_SEVERITY]: $description = "Changed severity from $value1 to $value2"; break;
		case $GLOBALS[ACTION_OPEN]: 	$description = "Ticket Opened"; break;
		case $GLOBALS[ACTION_CLOSED]: 	$description = "Ticket Closed"; break;
		default: 						$description = "[unknown report value: $action_type]";
	}
	@mysql_query("INSERT INTO action (date,ticket_id,action_type,description,user) VALUES(NOW(),'$ticket_id','$action_type','$description','$_SESSION[user_id]')") or do_error("functions.php.inc::reporting_action($action_type,$value1,$value2,$ticket_id)", 'mysql_query() failed', @mysql_error());
}

?>
