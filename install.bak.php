<? 
/***************************************************************************
 *                               install.php
 *                            -------------------
 *   begin                : Tuseday, May 1, 2007
 *   copyright            : (C) 2007 Fast Track Sites
 *   email                : sales@fasttracksites.com
 *
 *
 ***************************************************************************/

/***************************************************************************
Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:
    * Redistributions of source code must retain the above copyright
      notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright
      notice, this list of conditions and the following disclaimer in the
      documentation and/or other materials provided with the distribution.
    * Neither the name of the <organization> nor the
      names of its contributors may be used to endorse or promote products
      derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 ***************************************************************************/
define('IN_FTSIGS', true);
include_once ('includes/menu.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
	<head>
		<title>Fast Track Sites IGS - Install Page</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="content-language" content="en-us" />
		<!--Stylesheets Begin-->
			<link rel="stylesheet" type="text/css" href="themes/default/main.css" />
			<link rel="stylesheet" type="text/css" href="themes/default/dim.css" />
		<!--Stylesheets End-->
		<link rel="shortcut icon" href="favicon.ico" />
		<!--Javascripts Begin-->
		<!--[if lt IE 7]>
			<script src="javascripts/pngfix.js" defer type="text/javascript"></script>
		<![endif]-->	
		<!--Javascripts End-->
	</head>
	<body>
		<div id="container">
			<div id="page">
				<div id="header">
				</div>
				<div id="top-nav">
					<ul id="nav">
						<li><a href="index.php?p=1"><span>Home</span></a></li>
					</ul>			
			</div>		
				<div id="content">
<?
	function checkresult($result, $sql, $table) {
		global $failed;
		global $failedsql;
		global $totalfailure;
		
		if (!$result || $result == "") {
			$failed[$table] = "failed";
			$failedsql[$table] = $sql;
			$totalfailure = 1;
		}  
		else {
			$failed[$table] = "succeeded";
			$failedsql[$table] = $sql;
		}	
	}
	
	if (isset($_POST[submit])) {
		$failed = 0;
		$totalfailure = 0;
		$failed = array();
		$failedsql = array();
		$currentdate = time();
		
		$adminname = $_POST[adminname]; 
		$pass = $_POST[password]; 
		$password = md5($pass);
		$postemail = $_POST[email];
		$postusername = $_POST[username];
	
		// Create our database connection file
		$str = "<?PHP\n\n// Connect to the database\n\n\$server = \"" . $_POST[dbserver] . "\";\n\$dbuser = \"" . $_POST[dbusername] . "\";\n\$dbpass = \"" . $_POST[dbpassword] . "\";\n\$dbname = \"" . $_POST[dbname] . "\";\n\$DBTABLEPREFIX = \"" . $_POST[dbtableprefix] . "\";\n\n\$connect = mysql_connect(\$server,\$dbuser,\$dbpass);\n\n//display error if connection fails\nif (\$connect==FALSE) {\n   print 'Unable to connect to database: '.mysql_error();\n   exit;\n}\n\nmysql_select_db(\$dbname); // select database\n\n?>";
		
		$fp=fopen("_db.php","w");
		$result = fwrite($fp,$str);
		fclose($fp);		
		checkresult($result, "The installation program failed to create a connection file to your database, you will need to do this manually. Please see the readme file for more information.", "dbconnection");
	
	  	include '_db.php';

  		
		$sql = "CREATE TABLE `" . $DBTABLEPREFIX . "config` (
				`config_name` varchar(255) NOT NULL default '',
				`config_value` varchar(255) NOT NULL default ''
				) TYPE=MyISAM;";
		$result = mysql_query($sql);
		checkresult($result, $sql, "config");
  		
		$sql = "CREATE TABLE `" . $DBTABLEPREFIX . "comments` (
				`comments_id` mediumint(8) NOT NULL auto_increment,
				`comments_pic_id` mediumint(8) NOT NULL default '1',
				`comments_user_id` mediumint(8) NOT NULL default '1',
				`comments_timestamp` mediumint(11) NOT NULL default '',
				`comments_comment` text NOT NULL,
			  	PRIMARY KEY  (`comments_id`)
				) TYPE=MyISAM AUTO_INCREMENT=1 ;";
		$result = mysql_query($sql);
		checkresult($result, $sql, "comments");

		$sql = "CREATE TABLE `" . $DBTABLEPREFIX . "categories` (
				`cat_id` mediumint(8) NOT NULL auto_increment,
				`cat_name` varchar(50) NOT NULL default '',
				`cat_desc` text NOT NULL,
				`cat_icon` varchar(255) NOT NULL default '',
				PRIMARY KEY  (`cat_id`)
				) TYPE=MyISAM AUTO_INCREMENT=1 ;";
		$result = mysql_query($sql);
		checkresult($result, $sql, "categories");

		$sql = "CREATE TABLE `" . $DBTABLEPREFIX . "pics` (
				`pics_id` mediumint(8) NOT NULL auto_increment,
				`pics_cat_id` mediumint(8) NOT NULL default '1',
				`pics_user_id` mediumint(8) NOT NULL default '1',
				`pics_name` varchar(50) NOT NULL default '',
				`pics_desc` text NOT NULL,
				`pics_file` varchar(255) NOT NULL default '',
				`pics_license` varchar(50) NOT NULL default '',
				`pics_timestamp` mediumint(11) NOT NULL default '',
				`pics_total_ratings` mediumint(11) NOT NULL default '',
				`pics_rating` mediumint(11) NOT NULL default '',
				PRIMARY KEY  (`pics_id`)
				) TYPE=MyISAM AUTO_INCREMENT=1 ;";
		$result = mysql_query($sql);
		checkresult($result, $sql, "pics");

		$sql = "CREATE TABLE `" . $DBTABLEPREFIX . "users` (
			  `users_id` mediumint(8) NOT NULL auto_increment,
			  `users_full_name` varchar(50) NOT NULL default '',
			  `users_username` varchar(25) NOT NULL default '',
			  `users_password` varchar(32) NOT NULL default '',
			  `users_email_address` varchar(100) NOT NULL default '',
			  `users_avatar` varchar(255) NOT NULL default '',
			  `users_joined` mediumint(11) NOT NULL default '',
			  `users_active` tinyint(1) NOT NULL default '1',
			  `users_activation_key` varchar(255) NOT NULL default '1',
			  `users_user_level` tinyint(1) NOT NULL default '0',
				PRIMARY KEY  (`users_id`)
				) TYPE=MyISAM AUTO_INCREMENT=1 ;";
		$result = mysql_query($sql);
		checkresult($result, $sql, "users");

		$sql = "INSERT INTO `" . $DBTABLEPREFIX . "users` (`users_username`, `users_password`, `users_email_address`, `users_user_level`, `users_joined`) VALUES ('$postusername', '$password', '$postemail', '1', '" . time() . "');";
		$result = mysql_query($sql);
		checkresult($result, $sql, "adminuser");

		$sql = "INSERT INTO `" . $DBTABLEPREFIX . "config` VALUES ('ftsigs_site_title', 'Fast Track Sites IGS');";
		$result = mysql_query($sql);
		checkresult($result, $sql, "configinsert1");

		$sql = "INSERT INTO `" . $DBTABLEPREFIX . "config` VALUES ('ftsigs_active', '1');";
		$result = mysql_query($sql);
		checkresult($result, $sql, "configinsert2");

		$sql = "INSERT INTO `" . $DBTABLEPREFIX . "config` VALUES ('ftsigs_inactive_msg', 'Sorry but our system is currently down, please check back later.');";
		$result = mysql_query($sql);
		checkresult($result, $sql, "configinsert3");

		$sql = "INSERT INTO `" . $DBTABLEPREFIX . "config` VALUES ('ftsigs_theme', 'default');";
		$result = mysql_query($sql);
		checkresult($result, $sql, "configinsert4");

		$sql = "INSERT INTO `" . $DBTABLEPREFIX . "config` VALUES ('ftsigs_cookie_name', 'ftsigs');";
		$result = mysql_query($sql);
		checkresult($result, $sql, "configinsert5");

		$sql = "INSERT INTO `" . $DBTABLEPREFIX . "config` VALUES ('ftsigs_per_page', '8');";
		$result = mysql_query($sql);
		checkresult($result, $sql, "configinsert8");

		$sql = "INSERT INTO `" . $DBTABLEPREFIX . "categories` VALUES ('1', 'General', 'The default category. To edit this one or add more, please go to the administration page.', '');";
		$result = mysql_query($sql);
		checkresult($result, $sql, "categoriesinsert1");
		
		if ($totalfailure == 0) { 
			echo "\n<br /><h3><span color='green'>Installation Completed successfully!</span></h3><br /><strong><u>Please Delete This File Before Continuing</u></strong>.<br /><br />You can now view your new Image Gallery System <a href=\"$menuvar[HOME]\">here</a>."; 
		}
		else { 
			echo "\nInstallation failed, please see the explanations below"; 
			
			foreach ($failed as $table => $status) {
				echo "\nQuery for $table has ";
				if ($status == "failed") {
					echo "<span color='red'>$status</span> $failedsql[$table].<br />";
					$totalfailure = 1;
				}
				else {
					echo "<span color='green'>$status</span>.<br />";				
				}
			}					
		}
	}
	else {
		echo "\n<form action='$PHP_SELF' method='POST'>";
		echo "\n	<table class=\"tableBorder\" cellpadding=\"0\" cellspacing=\"1\" width=\"100%\">";
		echo "\n		<tr class='title1'>";
		echo "\n			<td colspan='2'>";
		echo "\n				Fast Track Sites Image Gallery Install";
		echo "\n			</td>";
		echo "\n		</tr>";
		echo "\n		<tr>";
		echo "\n			<td class='title2' colspan='2'>";
		echo "\n				Database Configuration";
		echo "\n			</td>";
		echo "\n		</tr>";
		echo "\n		<tr>";
		echo "\n			<td class='row1'>";
		echo "\n				<strong>Database Server:</strong>";
		echo "\n			</td>";
		echo "\n			<td class='row1'>";
		echo "\n				<input type='text' name='dbserver' />";
		echo "\n			</td>";
		echo "\n		</tr>";
		echo "\n		<tr>";
		echo "\n			<td class='row2'>";
		echo "\n				<strong>Database Name:</strong>";
		echo "\n			</td>";
		echo "\n			<td class='row2'>";
		echo "\n				<input type='text' name='dbname' />";
		echo "\n			</td>";
		echo "\n		</tr>";
		echo "\n		<tr>";
		echo "\n			<td class='row1'>";
		echo "\n				<strong>Database Username:</strong>";
		echo "\n			</td>";
		echo "\n			<td class='row1'>";
		echo "\n				<input type='text' name='dbusername' />";
		echo "\n			</td>";
		echo "\n		</tr>";
		echo "\n		<tr>";
		echo "\n			<td class='row2'>";
		echo "\n				<strong>Database Password:</strong>";
		echo "\n			</td>";
		echo "\n			<td class='row2'>";
		echo "\n				<input type='text' name='dbpassword' />";
		echo "\n			</td>";
		echo "\n		</tr>";
		echo "\n		<tr>";
		echo "\n			<td class='row1'>";
		echo "\n				<strong>Table Prefix:</strong>";
		echo "\n			</td>";
		echo "\n			<td class='row1'>";
		echo "\n				<input type='text' name='dbtableprefix' value=\"IGS_\" />";
		echo "\n			</td>";
		echo "\n		</tr>";
		echo "\n		<tr>";
		echo "\n			<td class='title2' colspan='2'>";
		echo "\n				General Configuration";
		echo "\n			</td>";
		echo "\n		</tr>";
		echo "\n		<tr class='row1'>";
		echo "\n			<td>";
		echo "\n				<strong>Admin Username:</strong>";
		echo "\n			</td>";
		echo "\n			<td>";
		echo "\n				<input type='text' name='username' size='40' />";
		echo "\n			</td>";
		echo "\n		</tr>";
		echo "\n		<tr class='row2'>";
		echo "\n			<td>";
		echo "\n				<strong>Admin Password:</strong>";
		echo "\n			</td>";
		echo "\n			<td>";
		echo "\n				<input type='text' name='password' size='40' />";
		echo "\n			</td>";
		echo "\n		</tr>";
		echo "\n		<tr class='row1'>";
		echo "\n			<td>";
		echo "\n				<strong>Your Email:</strong>";
		echo "\n			</td>";
		echo "\n			<td>";
		echo "\n				<input type='text' name='email' size='40' />";
		echo "\n			</td>";
		echo "\n		</tr>";			
		echo "\n	</table>";
		echo "\n	<br /><center><input type='submit' name='submit' class=\"button\" value='Install It!' /></center>";
		echo "\n</form>";
		echo "\n";
	}
?>
			</div>
			<div id="footer">				
					<div id="footer-leftcol" class="FForumBorder">
						Copyright &copy; 2007 Fast Track Sites
					</div>
					<div id="footer-rightcol">
						Powered By: <a href="http://www.fasttracksites.com">Fast Track Sites Simply Image Gallery System</a>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>