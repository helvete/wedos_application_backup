<?
	require_once('functions.inc.php');
	do_login('search.php');
?>
<HTML>
<HEAD>
<LINK REL=StyleSheet HREF="default.css" TYPE="text/css">
</HEAD>
<BODY>
<?
	if (!empty($_POST['frm_query']))
	{
		print "<FONT CLASS=\"header\">Search results for '$_POST[frm_query]'</FONT><BR><BR>\n";
		$_POST['frm_query'] = ereg_replace(' ', '|', $_POST['frm_query']);

		//what field are we searching?
		if($_POST[frm_search_in])
			$search_fields = "$_POST[frm_search_in] REGEXP '$_POST[frm_query]'";
		else
		{
			//list fields and form the query to search all of them
			$result = mysql_query("SELECT * FROM $GLOBALS[mysql_prefix]ticket");
			for ($i = 0; $i < mysql_num_fields($result); $i++)
    			$search_fields .= mysql_field_name($result, $i)." REGEXP '$_POST[frm_query]' OR ";
			$search_fields = substr($search_fields,0,strlen($search_fields) - 4);
		}

		//is user restricted to his/her own tickets?
		if (get_variable('restrict_user_tickets') && !(is_administrator()))
			$restrict_ticket = "AND owner='$_SESSION[user_id]'";

		//tickets
		$query = "SELECT *,UNIX_TIMESTAMP(problemstart) AS problemstart,UNIX_TIMESTAMP(problemend) AS problemend,UNIX_TIMESTAMP(date) AS date FROM $GLOBALS[mysql_prefix]ticket WHERE status LIKE '$_POST[frm_querytype]' AND $search_fields $restrict_ticket ORDER BY $_POST[frm_ordertype] $_POST[frm_order_desc]";
		$result = mysql_query($query) or do_error('search.php','search query failed, possibly illegal syntax', mysql_error());

		if(mysql_num_rows($result) == 1)
		{
			// display ticket in whole if just one returned
			$row = mysql_fetch_array($result);
			show_ticket($row[id]);
			add_footer($row[id]);
			exit();
		}
		else if (mysql_num_rows($result))
		{
			$ticket_found = 1;
			print "<TABLE BORDER=\"0\"><TR><TD CLASS=\"td_header\">Ticket</TD><TD CLASS=\"td_header\">Date</TD><TD CLASS=\"td_header\">Description</TD></TR>";
			while($row = mysql_fetch_array($result))
				print "<TR><TD><A HREF=\"main.php?id=$row[id]\">#$row[id]</A>&nbsp;&nbsp;</TD><TD>".format_date($row[date])."&nbsp;&nbsp;&nbsp;</TD><TD><A HREF=\"main.php?id=$row[id]\">$row[description]</A></TD></TR>\n";

			print '</TABLE><BR><BR>';
		}
		else
			print 'No matching tickets found.<BR><BR>';

		//actions
		$query = "SELECT *,UNIX_TIMESTAMP(date) AS date FROM $GLOBALS[mysql_prefix]action WHERE description REGEXP '$_POST[frm_query]'";
		$result = mysql_query($query) or do_error('search.php','search query failed, possibly illegal syntax', mysql_error());
		if(mysql_num_rows($result) && !$ticket_found)
		{
			// display ticket in whole if just one returned
			$row = mysql_fetch_array($result);
			show_ticket($row[ticket_id]);
			add_footer($row[id]);
			exit();
		}
		else if (mysql_num_rows($result) == 1)
		{
			print '<TABLE BORDER="0"><TR><TD CLASS="td_header">Ticket</TD><TD CLASS="td_header">Date</TD><TD CLASS="td_header">Action</TD></TR>';
			while($row = mysql_fetch_array($result))
			{
				print "<TR><TD VALIGN=\"top\"><A HREF=\"main.php?id=$row[ticket_id]\">#$row[ticket_id]</A>&nbsp;&nbsp;</TD><TD NOWRAP VALIGN=\"top\">".format_date($row[date])."&nbsp;&nbsp;&nbsp;</FONT></TD><TD><A HREF=\"main.php?id=$row[ticket_id]\">$row[description]</A></TD></TR>\n";
			}
			print '</TABLE>';
		}
		else
			print 'No matching actions found.';
	}
	else
	{
		?><FONT CLASS="header">Search</FONT><?
	}
?>
<BR><BR>
<FORM METHOD="post" ACTION="search.php">
<TABLE CELLPADDING="2" BORDER="0">
<TR><TD VALIGN="top" CLASS="td_label">Query: &nbsp;</TD><TD><INPUT TYPE="text" SIZE="40" MAXLENGTH="255" VALUE="<?=!empty($_POST['frm_query'])?$_POST['frm_query']:'';?>" NAME="frm_query"></TD></TR>
<TR><TD VALIGN="top" CLASS="td_label">Search in: &nbsp;</TD><TD>
<SELECT NAME="frm_search_in">
<OPTION VALUE="" checked>All</OPTION>
<OPTION VALUE="description">Description</OPTION>
<OPTION VALUE="affected">Affected</OPTION>
<OPTION VALUE="scope">Scope</OPTION>
<OPTION VALUE="owner">Owner</OPTION>
<OPTION VALUE="date">Issue Date</OPTION>
<OPTION VALUE="problemstart">Problem Starts</OPTION>
<OPTION VALUE="problemend">Problem Ends</OPTION>
</SELECT></TD></TR>
<TR><TD VALIGN="top" CLASS="td_label">Order By: &nbsp;</TD><TD>
<SELECT NAME="frm_ordertype">
<OPTION VALUE="date">Issue Date</OPTION>
<OPTION VALUE="problemstart">Problem Starts</OPTION>
<OPTION VALUE="problemend">Problem Ends</OPTION>
<OPTION VALUE="affected">Affected</OPTION>
<OPTION VALUE="scope">Scope</OPTION>
<OPTION VALUE="owner">Owner</OPTION>
</SELECT>&nbsp;Descending: <INPUT TYPE="checkbox" NAME="frm_order_desc" VALUE="DESC" CHECKED></TD></TR>
<TR><TD VALIGN="top" CLASS="td_label">Status: &nbsp;</TD><TD>
<INPUT TYPE="radio" NAME="frm_querytype" VALUE="%" CHECKED> All<BR>
<INPUT TYPE="radio" NAME="frm_querytype" VALUE="<?=$STATUS_OPEN;?>"> Open<BR>
<INPUT TYPE="radio" NAME="frm_querytype" VALUE="<?=$STATUS_CLOSED;?>"> Closed<BR>
</TD></TR>
<TR><TD></TD><TD><INPUT TYPE="submit" VALUE="Submit"></TD></TR>
</TABLE></FORM>
</BODY></HTML>
