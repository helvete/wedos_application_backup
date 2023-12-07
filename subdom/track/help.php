<? 
	require_once('functions.inc.php');
?>
<HTML>
<HEAD>
<LINK REL=StyleSheet HREF="default.css" TYPE="text/css">
</HEAD><BODY>
<FONT CLASS="header">PHP Ticket Help</FONT><BR><BR>
<LI> <A HREF="help.php?q=tickets">Tickets and Actions</A>
<LI> <A HREF="help.php?q=config">Configuration</A>
<LI> <A HREF="help.php?q=notify">Notifies</A>
<LI> <A HREF="help.php?q=develop">Developing</A>
<LI> <A HREF="help.php?q=changelog">ChangeLog</A>
<LI> <A HREF="help.php?q=install">Installing/Upgrading</A>
<LI> <A HREF="help.php?q=readme">ReadMe</A>
<LI> <A HREF="help.php?q=todo">ToDo</A>
<LI> <A HREF="help.php?q=licensing">Licensing</A>
<LI> <A HREF="help.php?q=credits">Credits</A>
<BR><BR>
<?
	if ($_GET['q'] == 'tickets')
	{?>
		<FONT CLASS="header"><BR>Tickets and Actions</FONT><BR>
		The tickets are what describes one or several issues, problems if you wish. Every ticket may have one or several actions
		related to it to describe work in progress or adding sidenotes. Actions are described below. A ticket contains several
		values to describe the issue, starting with <B>ID</B> which is of no real use to the user. <B>Issue date</B> is at what date and time
		the ticket was created, <B>problem start</B> and <B>problem end</B> date and time for when the issue starts and ends. The <B>scope</B> may be
		used to seperate tickets for departments, groups, types of problems or simply anything you want. The <B>owner</B> field corresponds
		to the user the ticket belongs to, not neccesarily the creator.<BR><BR>
		The <B>affected</B> field explains what is affected by this issue, systems, computers, groups of people or anything you want.
		The <B>status</B> field is either open or closed depending on the ticket status. Closed tickets can be made open again by changing
		the status again. Removed tickets however are deleted completely from the database along with its related actions.
		The <B>description</B> field describes the issue in depth.<BR><BR>
		When the issue described in a ticket is updated, <B>actions</B> may be added to reflect that change. Actions are simply a string value with
		a date for when the action was added.
		
		<BR><BR>
		If the administrator has allowed custom tags in phpticket settings, the action and description fields of a ticket may contain
		tags that will be replaced with the HTML equivalency. These are the custom tags available:<BR><BR>
		
		<TABLE BORDER="0">
		<TR><TD><B>Result</B></TD><TD><B>Tag</B></TD></TR>
		<TR><TD><B>bold text</B></TD><TD>[b]bold text[/b]<BR></TD></TR>
		<TR><TD><B>pre-formatted text</B></TD><TD>[pre]pre-formatted text[/pre]<BR></TD></TR>
		<TR><TD><I>italic text</I></TD><TD>[i]italic text[/i]<BR></TD></TR>
		<TR><TD><LI>list item</TD><TD>[li]list item<BR></TD></TR>
		<TR><TD><FONT CLASS="text_red">red text</FONT></TD><TD>[color=red]red text[/color]<BR></TD></TR>
		<TR><TD><FONT CLASS="text_green">green text</FONT></TD><TD>[color=green]green text[/color]<BR></TD></TR>
		<TR><TD><FONT CLASS="text_blue">blue text</FONT></TD><TD>[color=blue]blue text[/color]<BR></TD></TR>
		<TR><TD><FONT CLASS="text_orange">orange text</FONT></TD><TD>[color=orange]orange text[/color]<BR></TD></TR>
		<TR><TD><FONT CLASS="text_small">small text</FONT></TD><TD>[size=small]small text[/size]<BR></TD></TR>
		<TR><TD><FONT CLASS="text_medium">medium text</FONT></TD><TD>[size=medium]medium text[/size]<BR></TD></TR>
		<TR><TD><FONT CLASS="text_big">big text</FONT></TD><TD>[size=big]big text[/size]<BR></TD></TR>
		</TABLE>
	<?}
	else if ($_GET['q'] == 'config')
	{?>
		<FONT CLASS="header"><BR>Configuration</FONT><BR>
		The configuration section of PHP Ticket provides user management, various settings and database maintenance. Users are created, edited 
		and deleted here. The <b>administrator</b> user flag toggles user management rights, i.e. the right to edit users as well as administer the
		database. The optimize function optimizes the database for faster queries. The database reset deletes ticket, action and user rows in the database
		and creates a default "admin" user with the password <b>admin</b>. It also resets settings to its original state. The settings control various variables in PHP Ticket and should 
		be carefully changed since there's no verifying of correct values.
	<?}
	else if ($_GET['q'] == 'notify')
	{?>
		<FONT CLASS="header"><BR>Notifies</FONT><BR>
		This feature enables notification of ticket events, currently limited to email. Each notify event consists of
		one email address to which the notification will be sent, a command string to trigger a program or script (not implemented yet)
		and at which ticket changes to notify.<BR><BR>
		
		To add a notify event, when viewing the ticket, click the <B>Notify</B> link and fill in the form. To view and/or edit the notifies
		belonging to the logged in user, click the <B>Edit My Notifies</B> under <B>Configuration</B>.
		
	<?}
	else if ($_GET['q'] == 'develop')
	{?>
		<FONT CLASS="header"><BR>Developing</FONT><BR>
		Developing PHP Ticket to suit your particular needs shouldn't be very hard. The actual PHP code is fairly simple and
		easy to edit while the HTML code that make up the interface is less simple to change. The font properties, table backgrounds
		etc. is using CSS (default.css) for easy editing.<BR>Most of the functions are located
		in the functions.inc.php file. To add a setting, just add the line in the "settings" table in the database and it'll
		show up on the settings screen.
		
		<BR><BR>
		All data is stored in a MySQL database, a table called <b>user</b> is used for simple
		authentication of users, <b>action</b> table are actions belonging to a
		certain ticket and table <b>ticket</b> contains ticket data. The <b>scope</b> column represents
		ticket type and may be set to anything. <b>Issue date</b> is ticket creation date, <b>affected</b>
		is affected systems/entities and <b>status</b> is the ticket status, opened or closed.
		The <b>settings</b> table contains various settings variables, cosmetics and functional and
		the <b>notify</b> table contains the ticket notifications entered by the users. See help section <b>notifies</b>
		for more info.
	<?}
	else if ($_GET['q'] == 'install')
	{?>
		<FONT CLASS="header"><BR>Installing/Upgrading</FONT><BR>
		PHP Ticket is installed and upgraded through <B>install.php</B>, all you need is valid information about the MySQL database.
		More info on the install process can be found in <B>install.php</B>.
		<FONT CLASS="warn">WARNING: Do NOT keep <B>install.php</B> accessible to everyone after installation/upgrading.</FONT>
	<?}
	else if ($_GET['q'] == 'changelog') {
		print '<PRE>'; readfile('ChangeLog'); print '</PRE>';
	}
	else if ($_GET['q'] == 'readme') {
		print '<PRE>'; readfile('README'); print '</PRE>';
	}
	else if ($_GET['q'] == 'todo') {
		print '<PRE>'; readfile('TODO'); print '</PRE>';
	}
	else if ($_GET['q'] == 'licensing') {
		print '<PRE>'; readfile('COPYING'); print '</PRE>';
	}
	else if ($_GET['q'] == 'credits')
	{?>
		<FONT CLASS="header"><BR>Credits</FONT><BR>
		Programming by Daniel Netz, netz "at" home "dot" se</A><BR>
		SourceForge Project: <A HREF="http://www.sourceforge.net/projects/ticket/" target="new">sourceforge.net/projects/ticket/</A><BR>
		CSV Repository: <A HREF="http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/ticket/" target="new">cvs.sourceforge.net/cgi-bin/viewcvs.cgi/ticket/</A><BR>
		PHP Ticket is licensed under <A HREF="COPYING" target="new">GPL</A>.<BR>
		Thanks to <A HREF="http://www.apache.org" TARGET="new">Apache</A>, <A HREF="http://www.php.net" TARGET="new">PHP</A>, <A HREF="http://www.mysql.com" TARGET="new">MySQL</A>, <A HREF="http://www.phpedit.com" TARGET="new">PHPEdit</A> and OpenSource in all.<BR>
		Special thanks to everyone contributing with ideas, code snippets and reporting problems.
	<?}
?>
</BODY></HTML>
