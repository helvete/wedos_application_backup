<?
	require_once('functions.inc.php');
	require_once('config.inc.php');
	do_login('config.php');
?>
<HTML>
<HEAD>
<LINK REL=StyleSheet HREF="default.css" TYPE="text/css">
</HEAD>
<BODY>
<?
	if ($_GET['notify'] == 'true')
	{
		if($_GET['id'])
		{
			?>
			<FONT CLASS="header">Add Notify Event</FONT><BR><BR>
			<? if (!get_variable('allow_notify')) print "<FONT CLASS=\"warn\">Warning: Notification is disabled by administrator</FONT><BR><BR>"; ?>
			<TABLE BORDER="0">
			<FORM METHOD="POST" ACTION="config.php?notify=true&add=true">
			<TR><TD CLASS="td_label">Ticket:</TD><TD ALIGN="right"><A HREF="main.php?id=<?=$_GET['id'];?>">#<?=$_GET['id'];?></A></TD></TR>
			<TR><TD CLASS="td_label">Email Address:</TD><TD><INPUT MAXLENGTH="70" SIZE="40" TYPE="text" NAME="frm_email"></TD></TR>
			<TR><TD CLASS="td_label">Execute:</TD><TD><INPUT MAXLENGTH="150" SIZE="40" TYPE="text" NAME="frm_execute"></TD></TR>
			<TR></TR><TD CLASS="td_label">On Action Change:</TD><TD ALIGN="right"><INPUT TYPE="checkbox" VALUE="1" NAME="frm_on_action"></TD></TR>
			<TR><TD CLASS="td_label">On Ticket Change: &nbsp;&nbsp;</TD><TD ALIGN="right"><INPUT TYPE="checkbox" VALUE="1" NAME="frm_on_ticket"></TD></TR>
			<INPUT TYPE="hidden" NAME="frm_id" VALUE="<?=$_GET['id'];?>">
			<TR><TD></TD><TD ALIGN="right"><INPUT TYPE="submit" VALUE="Submit"></TD></TR>
			</FORM></TABLE>
			<?
			exit();
		}
		else if ($_GET['save'] == 'true')
		{
			$i=0;
			while($_POST[frm_id][$i] != '')
			{
				if (isset($_POST[frm_delete][$i]))
				{
					$query = "DELETE from $GLOBALS[mysql_prefix]notify WHERE id='".$_POST[frm_id][$i]."'";
					$result = mysql_query($query) or do_error('config.php::update_notifies(delete)', 'mysql_query() failed', mysql_error());
				}
				else
				{
					//email validation check
					$email = validate_email($_POST['frm_email'][$i]);
					$email_address = $_POST['frm_email'][$i];
					if (!$email[status])
					{
						print "<FONT CLASS=\"warn\">Error: email validation failed for '$email_address', $email[msg]. Go back and check this email address.</FONT>";
						exit();
					}

					$query = "UPDATE $GLOBALS[mysql_prefix]notify SET execute_path='".$_POST[frm_execute][$i]."', email_address='".$_POST[frm_email][$i]."',on_action='".$_POST[frm_on_action][$i]."',on_ticket='".$_POST[frm_on_ticket][$i]."' WHERE id='".$_POST[frm_id][$i]."'";
					$result = mysql_query($query) or do_error('config.php::update_notifies(update)', 'mysql_query() failed', mysql_error());
				}
				$i++;
			}

			if (!get_variable('allow_notify')) print "<FONT CLASS=\"warn\">Warning: Notification is disabled by administrator</FONT><BR><BR>";
			print '<FONT CLASS="header">Notifies saved</FONT><BR><BR>';
		}
		else if ($_GET['add'] == 'true')
		{
			//email validation check
			$email = validate_email($_POST['frm_email']);
			if (!$email[status])
			{
				print "<FONT CLASS=\"warn\">Error: email validation failed for '$_POST[frm_email]', $email[msg]. Go back and check this email address.</FONT>";
				exit();
			}

			$query = "INSERT INTO $GLOBALS[mysql_prefix]notify SET ticket_id='$_POST[frm_id]',user='$_SESSION[user_id]',email_address='$_POST[frm_email]',execute_path='$_POST[frm_execute]',on_action='$_POST[frm_on_action]',on_ticket='$_POST[frm_on_ticket]'";
			$result = mysql_query($query) or do_error('config.php::notify(add)', 'mysql_query() failed', mysql_error());
			if (!get_variable('allow_notify')) print "<FONT CLASS=\"warn\">Warning: Notification is disabled by administrator</FONT><BR><BR>";
			print "<FONT SIZE=\"3\"><B>Notify added.</B></FONT><BR><BR>";
		}
		else
		{
			if ($_SESSION[user_id])
				$query = "SELECT * FROM $GLOBALS[mysql_prefix]notify WHERE user='$_SESSION[user_id]'";
			else
				$query = "SELECT * FROM $GLOBALS[mysql_prefix]notify";

			$result = mysql_query($query) or do_error('config.php::notify(list)', 'mysql_query() failed', mysql_error());

			if (mysql_num_rows($result))
			{
				print "<FONT CLASS=\"header\">Update Notifies<BR><BR>";
				if (!get_variable('allow_notify')) print "<FONT CLASS=\"warn\">Warning: Notification is disabled by administrator</FONT><BR><BR>";
				print '<TABLE BORDER="0"><FORM METHOD="POST" ACTION="config.php?notify=true&save=true">';
				print "<TR><TD CLASS=\"td_label\">Ticket</TD><TD CLASS=\"td_label\">Email</TD>";
				print '<TD CLASS="td_label">Execute</B></TD><TD CLASS="td_label">On Action</TD><TD CLASS="td_label">On Ticket Change</TD><TD CLASS="td_label">Delete</TD></TR>';

				$i = 0;
				while($row = mysql_fetch_array($result))
				{
					print "\n<TR><TD><A HREF=\"main.php?id=$row[ticket_id]\">#$row[ticket_id]</A></FONT></TD>\n";
					print "<TD><INPUT MAXLENGTH=\"70\" SIZE=\"32\" VALUE=\"$row[email_address]\" TYPE=\"text\" NAME=\"frm_email[$i]\"></TD>\n";
					print "<TD><INPUT MAXLENGTH=\"150\" SIZE=\"40\" TYPE=\"text\" VALUE=\"$row[execute_path]\" NAME=\"frm_execute[$i]\"></TD>\n";
					print "<TD ALIGN=\"right\"><INPUT TYPE=\"checkbox\" VALUE=\"1\" NAME=\"frm_on_action[$i]\""; print $row[on_action] ? " checked></TD>\n" : "></TD>\n";
					print "<TD ALIGN=\"right\"><INPUT TYPE=\"checkbox\" VALUE=\"1\" NAME=\"frm_on_ticket[$i]\""; print $row[on_ticket] ? " checked></TD>\n" : "></TD>\n";
					print "<TD ALIGN=\"right\"><INPUT TYPE=\"checkbox\" VALUE=\"1\" NAME=\"frm_delete[$i]\"></TD>\n";
					print "<INPUT TYPE=\"hidden\" NAME=\"frm_id[$i]\" VALUE=\"$row[id]\"></TR>\n";
					$i++;
				}
				print '</TABLE><BR><INPUT TYPE="submit" VALUE="Update"></FORM>';
				exit();
			}
			else
			{
				print '<B>No notifies to update.</B><BR><BR>';
			}
		}
	}
	else if ($_GET['imap'] == 'true')
	{
		//import imap email
		print "<LI> Connecting to <B>".get_variable('imap_account')."@".get_variable('imap_server').":".get_variable('imap_port')."/".get_variable('imap_folder')."</B><BR>";
		$mailbox = imap_connect(get_variable('imap_server'),get_variable('imap_port'),get_variable('imap_folder'),get_variable('imap_account'),get_variable('imap_password'),get_variable('imap_type'));
		imap_import($mailbox,get_variable('imap_delete'));
		imap_disconnect($mailbox);
	}
	else if ($_GET['profile'] == 'true')
	{
		//update profile
		if ($_GET['go'] == 'true')
		{
			//check passwords
			if($_POST['frm_passwd'] != '')
			{
				if($_POST['frm_passwd'] != $_POST['frm_passwd_confirm'])
				{
					print 'Passwords doesn\'t match. Click \'back\' and try again.';
					exit();
				}
				else
					$set_passwd = 1;
			}
			else if($_POST['frm_passwd_confirm'] != '')
				print '<FONT CLASS="warn">You need to fill in both password fields. Password is not updated.</FONT><BR>';

			if(!$set_passwd)
				$query = "UPDATE $GLOBALS[mysql_prefix]user SET info='$_POST[frm_info]',email='$_POST[frm_email]',sortorder='$_POST[frm_sortorder]',sort_desc='$_POST[frm_sort_desc]',ticket_per_page='$_POST[frm_ticket_per_page]',reporting='$_POST[frm_reporting]' WHERE id='$_SESSION[user_id]'";
			else
				$query = "UPDATE $GLOBALS[mysql_prefix]user SET passwd=PASSWORD('$_POST[frm_passwd]'),info='$_POST[frm_info]',email='$_POST[frm_email]',sortorder='$_POST[frm_sortorder]',sort_desc='$_POST[frm_sort_desc]',ticket_per_page='$_POST[frm_ticket_per_page]',reporting='$_POST[frm_reporting]' WHERE id='$_SESSION[user_id]'";

			$result = mysql_query($query) or do_error('config.php::profile(update)', 'mysql_query() failed', mysql_error());
			reload_session();
			print '<B>Your profile has been updated.</B><BR><BR>';
		}
		else
		{
			if ($_SESSION[user_id] < 0 OR check_for_rows("SELECT id FROM $GLOBALS[mysql_prefix]user WHERE id='$_SESSION[user_id]'") == 0)
			{
				print "Invalid user id '$_SESSION[user_id]'.";
				exit();
			}

			$query	= "SELECT * FROM $GLOBALS[mysql_prefix]user WHERE id='$_SESSION[user_id]'";
			$result	= mysql_query($query) or do_error('config.php::profile(get)', 'mysql_query() failed', mysql_error());
			$row	= mysql_fetch_array($result);

			?>
			<FONT CLASS="header">Edit My Profile</FONT><BR><BR><TABLE BORDER="0">
			<FORM METHOD="POST" ACTION="config.php?profile=true&go=true"><INPUT TYPE="hidden" NAME="frm_id" VALUE="<?=$row[id];?>">
			<TR><TD CLASS="td_label">New Password:</TD><TD><INPUT MAXLENGTH="255" SIZE="16" TYPE="password" NAME="frm_passwd"> &nbsp;&nbsp;<B>Confirm: </B><INPUT MAXLENGTH="255" SIZE="16" TYPE="password" NAME="frm_passwd_confirm"></TD></TR>
			<TR><TD CLASS="td_label">Email:</TD><TD><INPUT SIZE="47" MAXLENGTH="255" TYPE="text" VALUE="<?=$row[email];?>" NAME="frm_email"></TD></TR>
			<TR><TD CLASS="td_label">Info:</TD><TD><INPUT SIZE="47" MAXLENGTH="255" TYPE="text" VALUE="<?=$row[info];?>" NAME="frm_info"></TD></TR>
			<!-- <TR><TD CLASS="td_label">Show reporting actions:</TD><TD ALIGN="right"><INPUT TYPE="checkbox" VALUE="1" NAME="frm_reporting" <? if($row[reporting]) print " checked";?>></TD></TR> -->
			<TR><TD CLASS="td_label">Tickets per page:</TD><TD><INPUT SIZE="47" MAXLENGTH="3" TYPE="text" VALUE="<?=$row[ticket_per_page];?>" NAME="frm_ticket_per_page"></TD></TR>
			<TR><TD CLASS="td_label">Sort By:</TD><TD><SELECT NAME="frm_sortorder">
			<OPTION value="date" <? if($row[sortorder]=='date') print " selected";?>>Date</OPTION>
			<OPTION value="description" <? if($row[sortorder]=='description') print " selected";?>>Description</OPTION>
			<OPTION value="affected" <? if($row[sortorder]=='affected') print " selected";?>>Affected</OPTION>
			</SELECT>&nbsp; Descending <INPUT TYPE="checkbox" value="1" name="frm_sort_desc" <? if ($row[sort_desc]) print "checked";?>></TD></TR>
			<INPUT TYPE="hidden" NAME="frm_id" VALUE="<?=$_SESSION[user_id];?>">
			<TR><TD></TD><TD ALIGN="right"><INPUT TYPE="submit" VALUE="Apply"></TD></TR>
			</FORM></TABLE>
			<?
			exit();
		}
	}
	else if ($_GET['optimize'] == 'true')
	{
		if (is_administrator())
		{
			optimize_db();
			print '<FONT CLASS="header">Database optimized.</FONT><BR><BR>';
		}
		else
			print '<FONT CLASS="warn">Not authorized.</FONT><BR><BR>';
	}
	else if ($_GET['reset'] == 'true')
	{
		if (is_administrator())
		{
			if ($_GET['auth'] != 'true')
			{
				?><FONT CLASS="header">Reset Database</FONT><BR>This operation requires confirmation by entering "yes" into the last input box.<BR>
				<FONT CLASS="warn"><BR>Warning! The data will be permanently deleted</FONT><BR><BR>
				<TABLE BORDER="0"><FORM METHOD="POST" ACTION="config.php?reset=true&auth=true">
				<!-- <TR><TD CLASS="td_label">Purge closed tickets:</TD><TD ALIGN="right"><INPUT TYPE="checkbox" VALUE="1" NAME="frm_purge"></TD></TR> -->
				<TR><TD CLASS="td_label">Reset tickets/actions:</TD><TD ALIGN="right"><INPUT TYPE="checkbox" VALUE="1" NAME="frm_ticket"></TD></TR>
				<TR><TD CLASS="td_label">Reset users:</TD><TD ALIGN="right"><INPUT TYPE="checkbox" VALUE="1" NAME="frm_user"></TD></TR>
				<TR><TD CLASS="td_label">Reset settings:</TD><TD ALIGN="right"><INPUT TYPE="checkbox" VALUE="1" NAME="frm_settings"></TD></TR>
				<TR><TD CLASS="td_label">Really reset database? &nbsp;&nbsp;</TD><TD><INPUT MAXLENGTH="20" SIZE="40" TYPE="text" NAME="frm_confirm"></TD></TR>
				<TR><TD></TD><TD ALIGN="right"><INPUT TYPE="submit" VALUE="Apply"></TD></TR>
				</FORM></TABLE>
				<? exit();
			}
			else
			{
				if ($_POST['frm_confirm'] == 'yes')
					reset_db($_POST['frm_user'],$_POST['frm_ticket'],$_POST['frm_settings'],$_POST['frm_purge']);
				else
					print '<FONT CLASS="warn">Not authorized or confirmation failed.</FONT><BR><BR>';
			}
		}
		else
			print '<FONT CLASS="warn">Not authorized.</FONT><BR><BR>';
	}
	else if ($_GET['settings'] != '')
	{
		if (is_administrator())
		{
			if($_GET['go'] == 'true')
			{
				for ($i = 0; $i < $_POST["frm_values"]; $i++)
				{
					$query = "UPDATE $GLOBALS[mysql_prefix]settings SET value='".$_POST['frm_setting_value'][$i]."' WHERE id='".$_POST['frm_setting_id'][$i]."'";
					$result = mysql_query($query) or do_error('config.php::save_setting #$i', 'mysql_query() failed', mysql_error());
				}

				print '<FONT CLASS="header">Settings saved.</FONT><BR><BR>';
			}
			else
			{
				print '<FONT CLASS="header">Edit Settings</FONT><BR><BR><TABLE BORDER="0"><FORM METHOD="POST" ACTION="config.php?settings=true&go=true">';
				$counter = 0;
				$result = mysql_query("SELECT * FROM $GLOBALS[mysql_prefix]settings ORDER BY name") or do_error('config.php::list_settings', 'mysql_query() failed', mysql_error());
				while($row = mysql_fetch_array($result))
				{
					print "<TR><TD CLASS=\"td_label\"><A HREF=\"#\" TITLE=\"".get_setting_help($row['name'])."\">$row[name]</A>: &nbsp;</TD><TD><INPUT MAXLENGTH=\"255\" SIZE=\"40\" TYPE=\"text\" VALUE=\"{$row['value']}\" NAME=\"frm_setting_value[]\"><INPUT TYPE=\"hidden\" NAME=\"frm_setting_id[]\" VALUE=\"{$row['id']}\"></TD></TR>\n";
					$counter++;
				}

				print "<TR><TD></TD><TD ALIGN=\"right\"><INPUT TYPE=\"hidden\" NAME=\"frm_values\" VALUE=\"$counter\"><INPUT TYPE=\"submit\" VALUE=\"Apply\"></TD></TR></FORM></TABLE>";
				exit();
			}
		}
		else
			print '<FONT CLASS="warn">Not authorized.</FONT><BR><BR>';
	}
	else if ($_GET['id'] != '')
	{
		if (is_administrator())
		{
			$id = $_GET['id'];
			if ($id < 0 OR check_for_rows("SELECT id FROM $GLOBALS[mysql_prefix]user WHERE id='$id'") == 0)
			{
				print "Invalid user id '$id'.";
				exit();
			}

			$query	= "SELECT * FROM $GLOBALS[mysql_prefix]user WHERE id='$id'";
			$result	= mysql_query($query) or do_error('config.php::edit_user(edit)', 'mysql_query() failed', mysql_error());
			$row	= mysql_fetch_array($result);

			?>
			<FONT CLASS="header">Edit User</FONT><BR><BR><TABLE BORDER="0">
			<FORM METHOD="POST" ACTION="config.php?edit=true"><INPUT TYPE="hidden" NAME="frm_id" VALUE="<?=$id;?>">
			<TR><TD CLASS="td_label">User:</TD><TD><INPUT MAXLENGTH="20" SIZE="47" TYPE="text" VALUE="<?=$row['user'];?>" NAME="frm_user"></TD></TR>
			<TR><TD CLASS="td_label">Password:</TD><TD><INPUT MAXLENGTH="255" SIZE="16" TYPE="password" NAME="frm_passwd"> &nbsp;&nbsp;<B>Confirm: </B><INPUT MAXLENGTH="255" SIZE="16" TYPE="password" NAME="frm_passwd_confirm"></TD></TR>
			<TR><TD CLASS="td_label">Info:</TD><TD><INPUT SIZE="47" MAXLENGTH="255" TYPE="text" VALUE="<?=$row[info];?>" NAME="frm_info"></TD></TR>
			<TR><TD CLASS="td_label">Email:</TD><TD><INPUT SIZE="47" MAXLENGTH="255" TYPE="text" VALUE="<?=$row[email];?>" NAME="frm_email"></TD></TR>
			<TR><TD CLASS="td_label">Level:</TD><TD>
			<INPUT TYPE="radio" VALUE="<?=$GLOBALS['LEVEL_USER'];?>" NAME="frm_level" <?=is_user($id)?"checked":"";?>> User<BR>
			<INPUT TYPE="radio" VALUE="<?=$GLOBALS['LEVEL_GUEST'];?>" NAME="frm_level" <?=is_guest($id)?"checked":"";?>> Guest<BR>
			<INPUT TYPE="radio" VALUE="<?=$GLOBALS['LEVEL_ADMINISTRATOR'];?>" NAME="frm_level" <?=is_administrator($id)?"checked":"";?>> Administrator<BR>
			</TD></TR>
			<TR><TD CLASS="td_label">Remove User:</TD><TD><INPUT TYPE="checkbox" VALUE="yes" NAME="frm_remove"></TD></TR>
			<TR><TD></TD><TD ALIGN="right"><INPUT TYPE="submit" VALUE="Apply"></TD></TR>
			</FORM></TABLE>
			<?
			exit();
		}
		else
			print '<FONT CLASS="warn">Not authorized.</FONT><BR><BR>';
	}
	else if ($_GET['edit'] == 'true')
	{
		if ($_POST['frm_remove'] == 'yes')
		{
			//delete user
			$query = "DELETE FROM $GLOBALS[mysql_prefix]user WHERE id='$_POST[frm_id]'";
			$result = mysql_query($query) or do_error('config.php::edit_user(remove)', 'mysql_query() failed', mysql_error());

			//delete notifies belonging to user
			$query = "DELETE FROM $GLOBALS[mysql_prefix]notify WHERE user='$_POST[frm_id]'";
			$result = mysql_query($query) or do_error('config.php::edit_user(remove notifies)', 'mysql_query() failed', mysql_error());

			print '<B>User has been deleted from database.</B><BR><BR>';
		}
		else
		{
			if ($_POST['frm_passwd'] == '')
				$query = "UPDATE $GLOBALS[mysql_prefix]user SET user='$_POST[frm_user]',info='$_POST[frm_info]',level='$_POST[frm_level]',email='$_POST[frm_email]' WHERE id='$_POST[frm_id]'";
			else
			{
				if($_POST['frm_passwd'] != $_POST['frm_passwd_confirm'])
				{
					print 'Passwords doesn\'t match. Try again.<BR>';
					exit();
				}
				$query = "UPDATE $GLOBALS[mysql_prefix]user SET user='$_POST[frm_user]',passwd=PASSWORD('$_POST[frm_passwd]'),info='$_POST[frm_info]',level='$_POST[frm_level]' WHERE id='$_POST[frm_id]'";
			}
			$result = mysql_query($query) or do_error('config.php::edit_user(update)', 'mysql_query() failed', mysql_error());
			print '<B>User has been updated.</B><BR><BR>';
		}
	}
	else if($_GET['adduser'] == 'true')
	{
		if (is_administrator())
		{
			if($_GET['go'] == 'true')
			{
				if (check_for_rows("SELECT user FROM $GLOBALS[mysql_prefix]user WHERE user='$_POST[frm_user]'"))
				{
					print "<FONT CLASS=\"warn\">User '$_POST[frm_user]' already exists in database. Go back and try again.</FONT><BR>";
					exit();
				}

				if($_POST['frm_passwd'] == $_POST['frm_passwd_confirm'])
				{
					$query = "INSERT INTO $GLOBALS[mysql_prefix]user (user,passwd,info,level,email,sortorder) VALUES('$_POST[frm_user]',PASSWORD('$_POST[frm_passwd]'),'$_POST[frm_info]','$_POST[frm_level]','$_POST[frm_email]','date')";
					$result = mysql_query($query) or do_error('config.php::add_user()', 'mysql_query() failed', mysql_error());
					print "<B>User '$_POST[frm_user]' has been added.</B><BR><BR>";
				}
				else
				{
					print 'Passwords doesn\'t match. Please try again.<BR>';
					?>
					<BR><TABLE BORDER="0">
					<FORM METHOD="POST" ACTION="config.php?adduser=true&go=true">
					<TR><TD CLASS="td_label">User:</TD><TD><INPUT MAXLENGTH="20" SIZE="40" TYPE="text" VALUE="<?=$_POST['frm_user'];?>" NAME="frm_user"></TD></TR>
					<TR><TD CLASS="td_label">Password</TD><TD><INPUT MAXLENGTH="30" SIZE="40" TYPE="password" NAME="frm_passwd"></TD></TR>
					<TR><TD CLASS="td_label">Confirm Password: &nbsp;&nbsp;</TD><TD><INPUT MAXLENGTH="40" SIZE="40" TYPE="password" NAME="frm_passwd_confirm"></TD></TR>
					<TR><TD CLASS="td_label">Level:</TD><TD>
					<INPUT TYPE="radio" VALUE="<?=$GLOBALS['LEVEL_USER'];?>" NAME="frm_level" <?=is_user()?"checked":"";?>> User<BR>
					<INPUT TYPE="radio" VALUE="<?=$GLOBALS['LEVEL_GUEST'];?>" NAME="frm_level" <?=is_guest()?"checked":"";?>> Guest<BR>
					<INPUT TYPE="radio" VALUE="<?=$GLOBALS['LEVEL_ADMINISTRATOR'];?>" NAME="frm_level" <?=is_administrator()?"checked":"";?>> Administrator<BR>
					</TD></TR>
					<TR><TD CLASS="td_label">Info:</TD><TD><INPUT SIZE="40" MAXLENGTH="80" TYPE="text" VALUE="<?=$_POST['frm_info'];?>" NAME="frm_info"></TD></TR>
					<TR><TD></TD><TD><INPUT TYPE="submit" VALUE="Add User"></TD></TR>
					</FORM></TABLE>
					<?
					exit();
				}
			}
			else
			{
				?>
				<FONT CLASS="header">Add User</FONT><BR><BR><TABLE BORDER="0">
				<FORM METHOD="POST" ACTION="config.php?adduser=true&go=true">
				<TR><TD CLASS="td_label">User:</TD><TD><INPUT MAXLENGTH="20" SIZE="47" TYPE="text" NAME="frm_user"></TD></TR>
				<TR><TD CLASS="td_label">Password:</TD><TD><INPUT MAXLENGTH="255" SIZE="16" TYPE="password" NAME="frm_passwd">&nbsp;&nbsp; <B>Confirm:</B> <INPUT MAXLENGTH="255" SIZE="16" TYPE="password" NAME="frm_passwd_confirm"></TD></TR>
				<TR><TD CLASS="td_label">Info:</TD><TD><INPUT SIZE="47" MAXLENGTH="80" TYPE="text" NAME="frm_info"></TD></TR>
				<TR><TD CLASS="td_label">Email:</TD><TD><INPUT SIZE="47" MAXLENGTH="80" TYPE="text" NAME="frm_email"></TD></TR>
				<TR><TD CLASS="td_label">Level:</TD><TD>
				<INPUT TYPE="radio" VALUE="<?=$GLOBALS['LEVEL_USER'];?>" NAME="frm_level"> User<BR>
				<INPUT TYPE="radio" VALUE="<?=$GLOBALS['LEVEL_GUEST'];?>" NAME="frm_level"> Guest<BR>
				<INPUT TYPE="radio" VALUE="<?=$GLOBALS['LEVEL_ADMINISTRATOR'];?>" NAME="frm_level"> Administrator<BR>
				</TD></TR>
				<TR><TD></TD><TD><INPUT TYPE="submit" VALUE="Add User"></TD></TR>
				</FORM></TABLE>
				<?
				exit();
			}
		}
		else
			print '<FONT CLASS="warn">Not authorized.</FONT><BR><BR>';
	}
?>
<?
	//show menu based on user level
	if (is_administrator())
	{
	?>
		<LI><A HREF="config.php?adduser=true">Add user</A>
		<LI><A HREF="config.php?reset=true">Reset Database</A>
		<LI><A HREF="config.php?optimize=true">Optimize Database</A>
		<LI><A HREF="config.php?settings=true">Edit Settings</A>
		<? if (get_variable('imap_support')) print '<LI><A HREF="config.php?imap=true">Import IMAP</A>' ?>
	<?
	}
	if (!is_guest())
	{
	?>
		<LI><A HREF="config.php?profile=true">Edit My Profile</A>
		<LI><A HREF="config.php?notify=true">Edit My Notifies</A>
		<BR><BR>
	<?
		list_users();
	}
	show_stats();
?>
</BODY></HTML>
