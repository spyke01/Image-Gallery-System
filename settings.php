<? 
/***************************************************************************
 *                               settings.php
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
	
	if (isset($_POST[submit])) {
		$sql = "UPDATE `" . $DBTABLEPREFIX . "config` SET config_value='$_POST[site_title]' WHERE config_name='ftsigs_site_title'";
		$result = mysql_query($sql);
		
		$sql = "UPDATE `" . $DBTABLEPREFIX . "config` SET config_value='$_POST[active]' WHERE config_name='ftsigs_active'";
		$result = mysql_query($sql);
		
		$sql = "UPDATE `" . $DBTABLEPREFIX . "config` SET config_value='$_POST[cookie_name]' WHERE config_name='ftsigs_cookie_name'";
		$result = mysql_query($sql);

		$sql = "UPDATE `" . $DBTABLEPREFIX . "config` SET config_value='$_POST[inactive_msg]' WHERE config_name='ftsigs_inactive_msg'";
		$result = mysql_query($sql);

		$sql = "UPDATE `" . $DBTABLEPREFIX . "config` SET config_value='$_POST[per_page]' WHERE config_name='ftsigs_per_page'";
		$result = mysql_query($sql);
	}
	
	$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "config`";
	$result = mysql_query($sql);
	
	// This is used to let us get the actual items and not just config_name and config_value
	while ($row = mysql_fetch_array($result)) {
		extract($row);
		$config_name = $row['config_name'];
		$config_value = $row['config_value'];
		$current_config[$config_name] = $config_value;
	}	
	extract($current_config);
		
	// Give our template the values
	$content = "<form action=\"$menuvar[SETTINGS]\" method=\"post\" target=\"_top\">
					<table class=\"tableBorder\" cellpadding=\"0\" cellspacing=\"1\" width=\"100%\">
						<tr><td class=\"title1\" colspan=\"2\">Blog Settings</td></tr>
						<tr class=\"row1\">
							<td width=\"32%\"><strong>Blog Title: </strong></td>
							<td width=\"68%\"><input type=\"text\" name=\"site_title\" size=\"50\" value=\"$ftsigs_site_title\" /></td>
						</tr>
						<tr class=\"row2\">
							<td width=\"32%\"><strong>Posts Shown on Each Page: </strong></td>
							<td width=\"68%\"><input type=\"text\" name=\"per_page\" size=\"50\" value=\"$ftsigs_per_page\" /></td>
						</tr>
						<tr class=\"row1\">
							<td width=\"32%\"><strong>Cookie Name: </strong></td>
							<td width=\"68%\"><input type=\"text\" name=\"cookie_name\" size=\"50\" value=\"$ftsigs_cookie_name\" /></td>
						</tr>
						<tr class=\"row2\">
							<td width=\"32%\"><strong>Active: </strong></td>
							<td width=\"68%\">
								<select name=\"active\" class=\"settingsDropDown\">
									<option value=\"". ACTIVE . "\"";							
	if ($ftsigs_active == ACTIVE) { $content .= " selected"; }
	$content .= 					">Active</option>
									<option value=\"". INACTIVE . "\"";							
	if ($ftsigs_active == INACTIVE) { $content .= " selected"; }
	$content .= 					">Inactive</option>";
							
	$content .=					"</select>
							</td>
						</tr>
						<tr class=\"row1\">
							<td width=\"32%\"><strong>Inactive Message: </strong></td>
							<td width=\"68%\">
								<textarea name=\"inactive_msg\" cols=\"40\" rows=\"10\">$ftsigs_inactive_msg</textarea>
							</td>
						</tr>
					</table>
					<br />
					<center><input type=\"submit\" name=\"submit\" class=\"button\" value=\"Update Settings\" /></center>
				</form>";

	$page->setTemplateVar('PageContent', $content);
}
else {
	$page->setTemplateVar('PageContent', "\nYou Are Not Authorized To Access This Area. Please Refrain From Trying To Do So Again.");
}
?>