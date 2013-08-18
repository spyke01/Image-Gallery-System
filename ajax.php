<? 
/***************************************************************************
 *                               ajax.php
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
	
	//get variables from posted form
	$action = $_REQUEST['action'];
	$text = trim($_REQUEST['message']);
	$submit = $_REQUEST['submit'];
	$value = $_GET['value'];

	//Make safe in case user tried hacking board
	$action = parseurl($action);
	$text = keeptasafe($text);
	$submit = parseurl($submit);
	$value = parseurl($value);
	
	// Checks to see if a username is already in use
	if ($action == 'checkusername') {	
		$sql_username_check = mysql_query("SELECT users_username FROM `" . $DBTABLEPREFIX . "users` WHERE users_username='$value'");
	
		if (mysql_num_rows($sql_username_check) > 0) {
			echo "<a style=\"cursor: pointer; cursor: hand; color: red;\" onclick=\"new Ajax.Updater('usernameCheckerHolder', 'ajax.php?action=checkusername&value=' + document.newUserForm.username.value, {asynchronous:true});\">[Already In Use]</a>";
		}
		else {
			echo "<a style=\"cursor: pointer; cursor: hand; color: green;\" onclick=\"new Ajax.Updater('usernameCheckerHolder', 'ajax.php?action=checkusername&value=' + document.newUserForm.username.value, {asynchronous:true});\">[Good]</a>";
		}
		mysql_free_result($sql_username_check);
	}
	// Checks to see if an email address is already in use
	elseif ($action == 'checkemailaddress') {	
		$sql_emailaddress_check = mysql_query("SELECT users_email_address FROM `" . $DBTABLEPREFIX . "users` WHERE users_email_address='$value'");
	
		if (mysql_num_rows($sql_emailaddress_check) > 0) {
			echo "<a style=\"cursor: pointer; cursor: hand; color: red;\" onclick=\"new Ajax.Updater('emailaddressCheckerHolder', 'ajax.php?action=checkemailaddress&value=' + document.newUserForm.email_address.value, {asynchronous:true});\">[Already In Use]</a>";
		}
		else {
			echo "<a style=\"cursor: pointer; cursor: hand; color: green;\" onclick=\"new Ajax.Updater('emailaddressCheckerHolder', 'ajax.php?action=checkemailaddress&value=' + document.newUserForm.email_address.value, {asynchronous:true});\">[Good]</a>";
		}
		mysql_free_result($sql_emailaddress_check);
	}
	elseif ($action == "deleteFile") {
		if ($_SESSION['user_level'] == ADMIN || $_SESSION['user_level'] == MOD) {
			// Delete the file
			unlink($_GET['id']);
		}
		else {
			echo "\nYou Are Not Authorized To Perform This Action. Please Refrain From Trying To Do So Again.";
		}
	}
	else {
		// Do Nothing
	}
?>