<? 
/***************************************************************************
 *                               register.php
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

	$done = 0;

	//==================================================
	// Handle editing, adding, and deleting of users
	//==================================================	
	if (isset($_POST[submit])) {
		$username = keeptasafe($_POST[username]);
		$password = md5(keeptasafe($_POST[password1]));
		$email = keeptasafe($_POST[email_address]);
		$full_name = keeptasafe($_POST[full_name]);
		$website = keeptasafe($_POST[website]);
	
		// Check and make sure username is available
		$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "users` WHERE users_username = '$username'";
		$result = mysql_query($sql);
		
		$usernameCheck = mysql_num_rows($result);
		
		if ($usernameCheck == 0) {
			if ($_POST[password1] == $_POST[password2]) {
				$password = md5($_POST[password]);
								
				$sql = "INSERT INTO `" . $DBTABLEPREFIX . "users` (`users_full_name`, `users_username`, `users_password`, `users_email_address`, `users_user_level`, `users_joined`) VALUES ('$full_name', '$username', '$password', '$email', '" . USER . "', '" . time() . "')";
				$result = mysql_query($sql);
				
				if ($result) {
					$content = "<center>Your account has been created, and you are being redirected to the main page.</center>
								<meta http-equiv='refresh' content='1;url=$menuvar[HOME]'>";
					$done = 1;
				}
				else {
					$content = "<center>There was an error while creating your new user. Please try again later.</center>";						
				}
			}
			else {
				$content = "<center>The passwords you supplied do not match. Please check them and try again.</center>";			
			}
		}
		else {
			$content = "<center>The username you specified has already been taken, please try again.</center>";			
		}		
	}
	if ($done != 1) {
		$content .= "
				<div id=\"formHolder\">
					<form id=\"newUserForm\" name=\"newUserForm\" action=\"$menuvar[REGISTER]\" method=\"post\">
						<table class=\"tableBorder\" cellpadding=\"0\" cellspacing=\"1\" width=\"100%\">
							<tr>
								<td class=\"title1\" colspan=\"2\">Create An Account</td>
							</tr>
							<tr class=\"row1\">
								<td><strong>Username:</strong> <em>*</em></td><td><div id=\"usernameCheckerHolder\" style=\"float: right;\"><a style=\"cursor: pointer; cursor: hand; color: red;\" onclick=\"new Ajax.Updater('usernameCheckerHolder', 'ajax.php?action=checkusername&value=' + document.newUserForm.username.value, {asynchronous:true});\">[Check]</a></div><input name=\"username\" id=\"username\" type=\"text\" size=\"60\" class=\"required validate-alphanum\" title=\"" . $T_Desired_Username_Error . "\" value=\"" . $_POST['username'] . "\" /></td>
							</tr>
							<tr class=\"row2\">
								<td><strong>Password:</strong> <em>*</em></td><td><input name=\"password1\" id=\"password1\" type=\"password\" size=\"55\" class=\"required validate-password\" title=\"" . $T_Password_Error . "\" value=\"\" /></td>
							</tr>
							<tr class=\"row1\">
								<td><strong>Confirm Password:</strong> <em>*</em></td><td><input name=\"password2\" id=\"password2\" type=\"password\" size=\"55\" class=\"required validate-password-confirm\" /></td>
							</tr>
							<tr class=\"row2\">
								<td><strong>Full Name:</strong> <em>*</em></td><td><input name=\"full_name\" id=\"full_name\" type=\"text\" size=\"60\" class=\"required validate-alphanum\" title=\"\" value=\"" . $_POST['full_name'] . "\" /></td>
							</tr>
							<tr class=\"row1\">
								<td><strong>Email Address:</strong> <em>*</em></td><td><div id=\"emailaddressCheckerHolder\" style=\"float: right;\"><a style=\"cursor: pointer; cursor: hand; color: red;\" onclick=\"new Ajax.Updater('emailaddressCheckerHolder', 'ajax.php?action=checkemailaddress&value=' + document.newUserForm.email_address.value, {asynchronous:true});\">[Check]</a></div><input name=\"email_address\" id=\"email_address\" type=\"text\" size=\"60\" class=\"required validate-email\" value=\"" . $_POST['email_address'] . "\" /></td>
							</tr>
						</table>									
						<br />
						<center><input type=\"submit\" name=\"submit\" class=\"button\" value=\"Create My Account!\" /></center>
					</form>
				</div>";
	}

	$content .= "\n	<script type=\"text/javascript\">
		var valid = new Validation('newUserForm', {immediate : true, useTitles:true});
						Validation.addAllThese([
							['validate-password', 'Your password must be more than 6 characters and not be \'password\' or the same as your username', {
								minLength : 7,
								notOneOf : ['password','PASSWORD','1234567','0123456'],
								notEqualToField : 'username'
							}],
							['validate-password-confirm', 'Your confirmation password does not match your first password, please try again.', {
								equalToField : 'password1'
							}]
						]);
	</script>";
	
	$page->setTemplateVar('PageContent', $content);	
?>