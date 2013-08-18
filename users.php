<? 
/***************************************************************************
 *                               users.php
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
include 'includes/header.php';
if ($_SESSION['user_level'] == ADMIN) {
	//==================================================
	// Handle editing, adding, and deleting of users
	//==================================================	
	if ($_GET[action] == "newuser") {
		if (isset($_POST[submit])) {
			if ($_POST[password] == $_POST[password2]) {
				$password = md5($_POST[password]);
								
				$sql = "INSERT INTO `" . $DBTABLEPREFIX . "users` (`users_username`, `users_password`, `users_email_address`, `users_user_level`, `users_full_name`, `users_avatar`) VALUES ('$_POST[username]', '$password', '$_POST[emailaddress]', '$_POST[userlevel]', '$_POST[fullname]', '$_POST[avatar]')";
				$result = mysql_query($sql);
				
				if ($result) {
					$content = "<center>Your new user has been added, and you are being redirected to the main page.</center>
								<meta http-equiv='refresh' content='1;url=$menuvar[USERS]'>";
				}
				else {
					$content = "<center>There was an error while creating your new user. You are being redirected to the main page.</center>
								<meta http-equiv='refresh' content='5;url=$menuvar[USERS]'>";						
				}
			}
			else {
				$content = "<center>The passwords you supplied do not match. You are being redirected to the main page.</center>
							<meta http-equiv='refresh' content='5;url=$menuvar[USERS]'>";			
			}
		}
		else {
			$content .= "
						<form name=\"newuserform\" action=\"$menuvar[USERS]&amp;action=newuser\" method=\"post\">
							<table class=\"tableBorder\" cellpadding=\"0\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"2\">Add A New User</td>
								</tr>
								<tr class=\"row1\">
									<td width=\"20%\"><strong>Full Name:</strong></td><td width=\"80%\"><input name=\"fullname\" type=\"text\" size=\"60\" /></td>
								</tr>
								<tr class=\"row2\">
									<td width=\"20%\"><strong>Username:</strong></td><td width=\"80%\"><input name=\"username\" type=\"text\" size=\"60\" /></td>
								</tr>
								<tr class=\"row1\">
									<td width=\"20%\"><strong>Password:</strong></td><td width=\"80%\"><input name=\"password\" type=\"password\" size=\"60\" /></td>
								</tr>
								<tr class=\"row2\">
									<td width=\"20%\"><strong>Confirm Password:</strong></td><td width=\"80%\"><input name=\"password2\" type=\"password\" size=\"60\" /></td>
								</tr>
								<tr class=\"row1\">
									<td width=\"20%\"><strong>Email Address:</strong></td><td width=\"80%\"><input name=\"emailaddress\" type=\"text\" size=\"60\" /></td>
								</tr>
								<tr class=\"row2\">
									<td width=\"20%\"><strong>Avatar:</strong></td><td width=\"80%\"><input name=\"avatar\" type=\"text\" size=\"60\" /></td>
								</tr>
								<tr class=\"row1\">
									<td width=\"20%\"><strong>User Level:</strong></td><td width=\"80%\">
										<select name=\"userlevel\" class=\"settingsDropDown\">
											<option value=\"" . BANNED . "\">Banned</option>
											<option value=\"" . USER . "\">User</option>
											<option value=\"" . MOD . "\">Moderator</option>
											<option value=\"" . ADMIN . "\">Administrator</option>
										</select>
									</td>
								</tr>
							</table>									
							<br />
							<center><input type=\"submit\" name=\"submit\" class=\"button\" value=\"Add User\" /></center>
						</form>";
		}
	}	
	elseif ($_GET[action] == "edituser" && isset($_GET[id])) {
		if (isset($_POST[submit])) {
			if ($_POST[password] != "") {
				if ($_POST[password] == $_POST[password2]) {
					$password = md5($_POST[password]);								

					$sql = "UPDATE `" . $DBTABLEPREFIX . "users` SET users_username = '$_POST[username]', users_password = '$password', users_email_address = '$_POST[emailaddress]', users_user_level = '$_POST[userlevel]', users_full_name = '$_POST[fullname]', users_avatar = '$_POST[avatar]' WHERE users_id = '$_GET[id]'";
				}
				else {
					$content = "<center>The passwords you supplied do not match. You are being redirected to the main page.</center>
								<meta http-equiv='refresh' content='5;url=$menuvar[USERS]'>";			
				}
			}
			else {
				$sql = "UPDATE `" . $DBTABLEPREFIX . "users` SET users_username = '$_POST[username]', users_email_address = '$_POST[emailaddress]', users_full_name = '$_POST[fullname]', users_avatar = '$_POST[avatar]', users_user_level = '$_POST[userlevel]' WHERE users_id = '$_GET[id]'";
			}
			$result = mysql_query($sql);
			
			if ($result) {
				$content = "<center>Your user's details have been updated, and you are being redirected to the main page.</center>
							<meta http-equiv='refresh' content='1;url=$menuvar[USERS]'>";
			}
			else {
				$content = "<center>There was an error while updating your user's details. You are being redirected to the main page.</center>
							<meta http-equiv='refresh' content='5;url=$menuvar[USERS]'>";						
			}
		}
		else {
			$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "users` WHERE users_id = '$_GET[id]' LIMIT 1";
			$result = mysql_query($sql);
			
			if (mysql_num_rows($result) == 0) {
				$content = "<center>There was an error while accessing the user's details you are trying to update. You are being redirected to the main page.</center>
							<meta http-equiv='refresh' content='5;url=$menuvar[USERS]'>";	
			}
			else {
				$row = mysql_fetch_array($result);
				
				function testlevel($currentlevel, $testinglevel) {
					$selected = ($currentlevel == $testinglevel) ? " selected=\"selected\"" : "";
					
					return $selected;
				}
				
				$content .= "
							<form name=\"newpageform\" action=\"$menuvar[USERS]&amp;action=edituser&amp;id=$row[users_id]\" method=\"post\">
								<table class=\"tableBorder\" cellpadding=\"0\" cellspacing=\"1\" width=\"100%\">
									<tr>
										<td class=\"title1\" colspan=\"2\">Edit User's Details</td>
									</tr>
									<tr class=\"row1\">
										<td width=\"20%\"><strong>Full Name:</strong></td><td width=\"80%\"><input name=\"fullname\" type=\"text\" size=\"60\" value=\"$row[users_full_name]\" /></td>
									</tr>
									<tr class=\"row2\">
										<td width=\"20%\"><strong>Username:</strong></td><td width=\"80%\"><input name=\"username\" type=\"text\" size=\"60\" value=\"$row[users_username]\" /></td>
									</tr>
									<tr class=\"row1\">
										<td width=\"20%\"><strong>New Password:</strong></td><td width=\"80%\"><input name=\"password\" type=\"password\" size=\"60\" value=\"$row[users_]\" /></td>
									</tr>
									<tr class=\"row2\">
										<td width=\"20%\"><strong>Confirm Password:</strong></td><td width=\"80%\"><input name=\"password2\" type=\"password\" size=\"60\" value=\"$row[users_]\" /></td>
									</tr>
									<tr class=\"row1\">
										<td width=\"20%\"><strong>Email Address:</strong></td><td width=\"80%\"><input name=\"emailaddress\" type=\"text\" size=\"60\" value=\"$row[users_email_address]\" /></td>
									</tr>
									<tr class=\"row2\">
										<td width=\"20%\"><strong>Avatar:</strong></td><td width=\"80%\"><input name=\"avatar\" type=\"text\" size=\"60\" value=\"$row[users_avatar]\" /></td>
									</tr>
									<tr class=\"row1\">
										<td width=\"20%\"><strong>User Level:</strong></td><td width=\"80%\">
											<select name=\"userlevel\" class=\"settingsDropDown\">
												<option value=\"" . BANNED . "\"" . testlevel($row[users_user_level], BANNED) . ">Banned</option>
												<option value=\"" . USER . "\"" . testlevel($row[users_user_level], USER) . ">User</option>
												<option value=\"" . MOD . "\"" . testlevel($row[users_user_level], MOD) . ">Moderator</option>
												<option value=\"" . ADMIN . "\"" . testlevel($row[users_user_level], ADMIN) . ">Administrator</option>
											</select>
										</td>
									</tr>
								</table>									
								<br />
								<center><input type=\"submit\" name=\"submit\" class=\"button\" value=\"Update User's Details\" /></center>
							</form>";							
			}			
		}
	}
	else {
		if ($_GET[action] == "deleteuser") {
			$sql = "DELETE FROM `" . $DBTABLEPREFIX . "users` WHERE users_id='$_GET[id]' LIMIT 1";
			$result = mysql_query($sql);
		}		
		
		//==================================================
		// Print out our users table
		//==================================================
		$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "users` ORDER BY users_username ASC";
		$result = mysql_query($sql);
		
		$x = 1; //reset the variable we use for our row colors	
		
		$content = "<table class=\"tableBorder\" cellpadding=\"0\" cellspacing=\"1\" width=\"100%\">
							<tr>
								<td class=\"title1\" colspan=\"4\">
									<div style='float: right;'><a href=\"$menuvar[USERS]&amp;action=newuser\"><img src='images/plus.png' alt='Add a new user' /></a></div>
									Current Users
								</td>
							</tr>							
							<tr class=\"title2\">
								<td><strong>Username</strong></td><td><strong>Full Name</strong></td><td><strong>User Level</strong></td><td></td>
							</tr>";
							
		while ($row = mysql_fetch_array($result)) {
			$level = ($row[users_user_level] == ADMIN) ? "Administrator" : "Moderator";
			$level = ($row[users_user_level] == USER) ? "User" : $level;
			$level = ($row[users_user_level] == BANNED) ? "Banned" : $level;
			
			$content .=			"<tr class=\"row" . $x . "\">
									<td>$row[users_username]</td>
									<td>$row[users_full_name]</td>
									<td>$level</td>
									<td>
										<center><a href=\"$menuvar[USERS]&amp;action=edituser&amp;id=$row[users_id]\"><img src='images/check.png' alt='Edit User Details' /></a> <a href=\"$menuvar[USERS]&amp;action=deleteuser&amp;id=$row[users_id]\"><img src=\"images/x.png\" alt='Delete User' /></a></center>
									</td>
								</tr>";
			$x = ($x==2) ? 1 : 2;
		}
		mysql_free_result($result);
		
	
		$content .=		"</table>";
	}
	$page->setTemplateVar("PageContent", $content);
}
else {
	$page->setTemplateVar("PageContent", "\nYou Are Not Authorized To Access This Area. Please Refrain From Trying To Do So Again.");
}
?>