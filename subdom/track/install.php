<HTML><HEAD>
<LINK REL=StyleSheet HREF="default.css" TYPE="text/css">
</HEAD><BODY>
<FONT CLASS="header">Install PHP Ticket</FONT><BR><BR>
<?
	//check if mysql table exists, if it's a re-install
	function table_exists($name,$drop_tables)
	{
		$query 		= "SELECT COUNT(*) FROM $name";
       	$result 	= mysql_query($query);
		$num_rows 	= @mysql_num_rows($result);

		if($num_rows)
		{
			if($drop_tables)
			{
				mysql_query("DROP TABLE $name");
				print "<LI> Dropped table '<B>$name</B>'<BR>";
			}
			else
			{
				print "<FONT CLASS=\"warn\">Table '$name' already exists, use Re-install option instead. Click back in your browser.</FONT></BODY></HTML>";
				exit();
			}
		}
	}

	/* insert new values into settings table */
	function do_insert_settings($name,$value)
	{
		$query = "INSERT INTO $_POST[frm_db_prefix]settings (name,value) VALUES('$name','$value')";
		$result = mysql_query($query) or die("do_insert_settings($name,$value) failed, execution halted");
	}

	//create tables
	function create_tables($db_prefix,$drop_tables=0)
	{
		//check if tables exists and if drop_tables is 1
		table_exists($db_prefix."action",$drop_tables);
		table_exists($db_prefix."ticket",$drop_tables);
		table_exists($db_prefix."user",$drop_tables);
		table_exists($db_prefix."notify",$drop_tables);
		table_exists($db_prefix."settings",$drop_tables);

		//action table
		if ($db_prefix) $db_prefix_action = $db_prefix."action"; else $db_prefix_action = "action";
		$query = 	"CREATE TABLE $db_prefix_action (
				  	id int(8) NOT NULL auto_increment,
  					ticket_id int(8) NOT NULL default '0',
  					date datetime default NULL,
  					description text NOT NULL,
  					user int(8) default NULL,
  					action_type int(8) default NULL,
  					PRIMARY KEY  (id),
  					UNIQUE KEY ID (id)
					) ENGINE=MyISAM;";
		mysql_query($query) or die("CREATE TABLE $db_prefix_action failed, execution halted");

		//notify table
		if ($db_prefix) $db_prefix_notify = $db_prefix."notify"; else $db_prefix_notify = "notify";
		$query = 	"CREATE TABLE $db_prefix_notify (
  					id int(8) NOT NULL auto_increment,
  					ticket_id int(8) NOT NULL default '0',
  					user int(8) NOT NULL default '0',
  					execute_path tinytext,
  					on_action tinyint(1) default '0',
  					on_ticket tinyint(1) default '0',
  					email_address tinytext,
  					PRIMARY KEY  (id),
  					UNIQUE KEY ID (id)
					) ENGINE=MyISAM;";
		mysql_query($query) or die("CREATE TABLE $db_prefix_notify failed, execution halted");

		//settings table
		if ($db_prefix) $db_prefix_settings = $db_prefix."settings"; else $db_prefix_settings = "settings";
		$query = 	"CREATE TABLE $db_prefix_settings (
 					id int(8) NOT NULL auto_increment,
  					name tinytext,
  					value tinytext,
  					PRIMARY KEY  (id)
					) ENGINE=MyISAM;";
		mysql_query($query) or die("CREATE TABLE $db_prefix_settings failed, execution halted");

		//ticket table
		if ($db_prefix) $db_prefix_ticket = $db_prefix."ticket"; else $db_prefix_ticket = "ticket";
		$query = 	"CREATE TABLE $db_prefix_ticket (
  					id int(8) NOT NULL auto_increment,
  					date datetime default NULL,
  					problemstart datetime default NULL,
  					problemend datetime default NULL,
  					scope text NOT NULL,
  					affected text,
  					description text NOT NULL,
  					status tinyint(1) NOT NULL default '0',
  					owner tinyint(4) NOT NULL default '0',
  					severity int(2) NOT NULL default '0',
					locked tinyint(1) NOT NULL default '0',
 					PRIMARY KEY  (id),
  					UNIQUE KEY ID (id)
					) ENGINE=MyISAM;";
		mysql_query($query) or die("CREATE TABLE $db_prefix_ticket failed, execution halted");


		//user table
		if ($db_prefix) $db_prefix_user = $db_prefix."user"; else $db_prefix_user = "user";
		$query = 	"CREATE TABLE $db_prefix_user (
  					id int(8) NOT NULL auto_increment,
  					passwd tinytext,
  					info text NOT NULL,
  					user tinytext,
  					level tinyint(1) default NULL,
  					email tinytext,
  					ticket_per_page tinyint(1) default NULL,
  					sort_desc tinyint(1) default '0',
  					sortorder tinytext,
  					reporting tinyint(1) default '1',
  					PRIMARY KEY  (id),
  					UNIQUE KEY ID (id)
					) ENGINE=MyISAM;";
		mysql_query($query) or die("CREATE TABLE $db_prefix_user failed, execution halted");

		print "<LI> Created tables '$db_prefix_action', '$db_prefix_notify', '$db_prefix_settings', '$db_prefix_user', '$db_prefix_ticket'<BR>";
	}

	//create default admin user
	function create_user()
	{
		mysql_query("INSERT INTO $_POST[frm_db_prefix]user (user,passwd,info,level,ticket_per_page,sort_desc,sortorder,reporting) VALUES('admin',PASSWORD('admin'),'Administrator',1,0,1,'date',0)") or die("INSERT INTO user failed, execution halted");
		print "<LI> Created user '<B>admin</B>'";
	}

	//insert settings
	function insert_settings()
	{
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
		print "<LI> Inserted standard settings";
	}

	//output mysql settings to mysql.inc.php
	function write_conf($host,$db,$user,$password,$prefix)
	{
		if (!$fp = fopen('mysql.inc.php', 'a'))
        	print '<LI> <FONT CLASS="warn">Cannot open mysql.inc.php for writing</FONT>';
		else
		{
			ftruncate($fp,0);
			fwrite($fp, "<?\n");
			fwrite($fp, "	/* generated by install.php */\n");
			fwrite($fp, '	$mysql_host 	= '."'$host';\n");
			fwrite($fp, '	$mysql_db 		= '."'$db';\n");
			fwrite($fp, '	$mysql_user 	= '."'$user';\n");
			fwrite($fp, '	$mysql_passwd 	= '."'$password';\n");
			fwrite($fp, '	$mysql_prefix 	= '."'$prefix';\n");
			fwrite($fp, '?>');
		}

		fclose($fp);
		print '<LI> Wrote configuration to \'<B>mysql.inc.php</B>\'';
	}

	//upgrade db from 0.7.x to 0.8
	function upgrade_07_08($prefix)
	{
		print '<LI> Upgrading structure <B>0.7->0.8...</B><BR>';
		mysql_query("ALTER TABLE $prefix"."ticket ADD locked tinyint(1) NOT NULL default '0'") or die("<FONT CLASS=\"warn\">Could not upgrade 0.7->0.8, query #1 failed</FONT>");

		print '<LI> Replacing settings...</B>';
		mysql_query("DELETE FROM $prefix"."settings") or die("<FONT CLASS=\"warn\">Could not <remove old settings</FONT>");
		insert_settings();
	}

	//upgrade db from 0.65 to 0.7
	function upgrade_065_07($prefix)
	{
		print '<LI> Upgrading structure <B>0.65->0.7...</B><BR>';
		mysql_query("ALTER TABLE $prefix"."ticket ADD severity int(2) NOT NULL default '0'") or die("<FONT CLASS=\"warn\">Could not upgrade 0.65->0.7, query #1 failed</FONT>");
		mysql_query("ALTER TABLE $prefix"."user ADD level tinyint(1) default NULL") or die("<FONT CLASS=\"warn\">Could not upgrade 0.65->0.7, query #2 failed</FONT>");
		mysql_query("ALTER TABLE $prefix"."user ADD ticket_per_page tinyint(1) default '0'") or die("<FONT CLASS=\"warn\">Could not upgrade 0.65->0.7, query #3 failed</FONT>");
		mysql_query("ALTER TABLE $prefix"."user ADD sort_desc tinyint(1) default '0'") or die("<FONT CLASS=\"warn\">Could not upgrade 0.65->0.7, query #4 failed</FONT>");
		mysql_query("ALTER TABLE $prefix"."user ADD sortorder tinytext") or die("<FONT CLASS=\"warn\">Could not upgrade 0.65->0.7, query #5 failed</FONT>");
		mysql_query("ALTER TABLE $prefix"."user ADD reporting tinyint(1) default '1'") or die("<FONT CLASS=\"warn\">Could not upgrade 0.65->0.7, query #6 failed</FONT>");
		mysql_query("ALTER TABLE $prefix"."action ADD user int(8) default NULL") or die("<FONT CLASS=\"warn\">Could not upgrade 0.65->0.7, query #7 failed</FONT>");
		mysql_query("ALTER TABLE $prefix"."action ADD action_type int(8) default NULL") or die("<FONT CLASS=\"warn\">Could not upgrade 0.65->0.7, query #8 failed</FONT>");

		print '<LI> Replacing permissions and actions...</B>';
		mysql_query("UPDATE $prefix"."user SET level='1' WHERE admin='1'") or die("<FONT CLASS=\"warn\">Could not replace user permissions (admin)</FONT>");
		mysql_query("UPDATE $prefix"."user SET level='2' WHERE admin='0'") or die("<FONT CLASS=\"warn\">Could not replace user permissions (user)</FONT>");
		mysql_query("UPDATE $prefix"."action SET action_type='10', user='0'") or die("<FONT CLASS=\"warn\">Could not fix action data</FONT>");
		mysql_query("ALTER TABLE $prefix"."user DROP admin") or die("<FONT CLASS=\"warn\">Could not drop user field 'admin'</FONT>");

		print '<LI> Replacing settings...</B>';
		mysql_query("DELETE FROM $prefix"."settings") or die("<FONT CLASS=\"warn\">Could not <remove old settings</FONT>");
		insert_settings();
	}

	if($_GET['go'])
	{
		/* connect to mysql database if option isn't writeconf' */
		if ($_POST['frm_option'] != 'writeconf')
		{
			mysql_connect($_POST['frm_db_host'], $_POST['frm_db_user'], $_POST['frm_db_password']) or die("<FONT CLASS=\"warn\">Couldn't connect to database on '$_POST[frm_db_host]', make sure it is running and user has permissions. Click back in your browser.</FONT>");
			mysql_select_db($_POST['frm_db_dbname']) or die("<FONT CLASS=\"warn\">Couldn't select database '$_POST[frm_db_dbname]', make sure it exists and user has permissions. Click back in your browser.</FONT>");
		}

		//run the functions
		switch($_POST['frm_option'])
		{
			case 'install':
			{
				create_tables($_POST['frm_db_prefix']);
				create_user();
				insert_settings();
				write_conf($_POST['frm_db_host'],$_POST['frm_db_dbname'],$_POST['frm_db_user'],$_POST['frm_db_password'],$_POST['frm_db_prefix']);
				print "<LI> Installation done!";
				break;
			}
			case 'install-drop':
			{
				create_tables($_POST['frm_db_prefix'],1);
				create_user();
				insert_settings();
				write_conf($_POST['frm_db_host'],$_POST['frm_db_dbname'],$_POST['frm_db_user'],$_POST['frm_db_password'],$_POST['frm_db_prefix']);
				print "<LI> Re-Installation done!";
				break;
			}
			case 'upgrade-0.65':
			{
				upgrade_065_07($_POST['frm_db_prefix']);
				write_conf($_POST['frm_db_host'],$_POST['frm_db_dbname'],$_POST['frm_db_user'],$_POST['frm_db_password'],$_POST['frm_db_prefix']);
				print "<LI> Upgrade <B>0.65->0.7</B> complete!";
				break;
			}
			case 'upgrade-0.7':
			{
				upgrade_07_08($_POST['frm_db_prefix']);
				write_conf($_POST['frm_db_host'],$_POST['frm_db_dbname'],$_POST['frm_db_user'],$_POST['frm_db_password'],$_POST['frm_db_prefix']);
				print "<LI> Upgrade <B>0.7->0.8</B> complete!";
				break;
			}
			case 'writeconf':
			{
				write_conf($_POST['frm_db_host'],$_POST['frm_db_dbname'],$_POST['frm_db_user'],$_POST['frm_db_password'],$_POST['frm_db_prefix']);
				print "<LI> All done.";
				break;
			}
			default:
				print "<LI> <FONT CLASS=\"warn\">'$_POST[frm_option]' is not a valid option!</FONT>";
		}

		print '<BR><BR><FONT CLASS="warn">It is strongly recommended that you move/delete/change rights on install.php after this</FONT>';
		print '<BR><BR><A HREF="index.php"><< return to PHP Ticket</A>';
	}
	else if ($_GET['help'])
	{
		?>
		Fill in the install form with your mysql server settings. The table prefix option enables you to prefix the tables with
		an optional name if you're only using one database or need multiple instances. Thus a prefix of <B>my_</B> would name the
		tables <B>my_action</B>, <B>my_user</B> etc.<BR><BR>

		The <B>Re-install</B> option <FONT CLASS="warn">drops all phpticket data</FONT> in the specified database and re-installs them,
		if the tables already exists this option is required. If the tables are prefixed, you have to specify it in the form.<BR><BR>

		The <B>Upgrade</B> option upgrades an existing phpticket database from the specified version to the newest available. If the database
		structure has been modified in any way this script <FONT CLASS="warn">will most probably fail</FONT>. Please make sure to backup your database
		before proceeding with this upgrade. All the settings will be replaced.<BR><BR>

		The <B>Write Configuration Only</B> option writes the specified mysql settings to <B>mysql.inc.php</B> but doesn't alter the database
		in any way.

		<BR><BR><A HREF="install.php"><< back to install script</A>
		<?
	}
	else
	{
		?>
		Please fill in this form to install/upgrade phpticket. Make sure to read through <A HREF="install.php?help=1">the help</A> and backup your data.<BR><BR>
		<FORM METHOD="post" ACTION="install.php?go=1">
		<TABLE BORDER="0">
		<TR><TD>MySQL Host: </TD><TD><INPUT TYPE="text" SIZE="45" MAXLENGTH="255" NAME="frm_db_host"></TD></TR>
		<TR><TD>MySQL Database: </TD><TD><INPUT TYPE="text" SIZE="45" MAXLENGTH="255" NAME="frm_db_dbname"></TD></TR>
		<TR><TD>MySQL Username: </TD><TD><INPUT TYPE="text" SIZE="45" MAXLENGTH="255" NAME="frm_db_user"></TD></TR>
		<TR><TD>MySQL Password: </TD><TD><INPUT TYPE="password" SIZE="45" MAXLENGTH="255" NAME="frm_db_password"></TD></TR>
		<TR><TD>MySQL Table Prefix (optional): </TD><TD><INPUT TYPE="text" SIZE="45" MAXLENGTH="255" NAME="frm_db_prefix"></TD></TR>
		<TR><TD>Install Option: </TD><TD>
		<INPUT TYPE="radio" VALUE="install" NAME="frm_option"> Install 0.7 Database<BR>
		<INPUT TYPE="radio" VALUE="install-drop" NAME="frm_option"> Re-install 0.7 Database<BR>
		<INPUT TYPE="radio" VALUE="upgrade-0.65" NAME="frm_option"> Upgrade 0.65 -> 0.7<BR>
		<INPUT TYPE="radio" VALUE="upgrade-0.65" NAME="frm_option"> Upgrade 0.7 -> 0.8<BR>
		<INPUT TYPE="radio" VALUE="writeconf" NAME="frm_option"> Write Configuration Only<BR>
		</TD></TR>
		<TR><TD></TD><TD><INPUT TYPE="Submit" VALUE="Install"></TD></TR>
		</TABLE>
		</FORM>
		<?
	}
?>
</BODY></HTML>
