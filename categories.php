<? 
/***************************************************************************
 *                               categories.php
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
if ($_SESSION['user_level'] == ADMIN || $_SESSION['user_level'] == MOD) {
	//==================================================
	// Handle editing, adding, and deleting of pages
	//==================================================	
	if ($_GET[action] == "newcategory") {
		if (isset($_POST[submit])) {
			$sql = "INSERT INTO `" . $DBTABLEPREFIX . "categories` (`cat_icon`, `cat_name`, `cat_desc`) VALUES ('$_POST[caticon]', '$_POST[catname]', '$_POST[catdesc]')";
			$result = mysql_query($sql);
				
			if ($result) {
				$content = "
							<center>Your new category has been added, and you are being redirected to the main page.</center>
							<meta http-equiv='refresh' content='1;url=$menuvar[CATEGORIES]'>";
			}
			else {
				$content = "
							<center>There was an error while creating your new category. You are being redirected to the main page.</center>
							<meta http-equiv='refresh' content='5;url=$menuvar[CATEGORIES]'>";						
			}
		}
		else {
			$content .= "
						<form name=\"newcategoryform\" action=\"$menuvar[CATEGORIES]&amp;action=newcategory\" method=\"post\">
							<table class=\"tableBorder\" cellpadding=\"0\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"2\">New Category Information</td>
								</tr>
								<tr class=\"row1\">
									<td width=\"20%\"><strong>Category Icon:</strong></td><td width=\"80%\"><input name=\"caticon\" type=\"text\" size=\"60\" value=\"http://\" /></td>
								</tr>
								<tr class=\"row2\">
									<td width=\"20%\"><strong>Category Name:</strong></td><td width=\"80%\"><input name=\"catname\" type=\"text\" size=\"60\" /></td>
								</tr>
								<tr class=\"row1\">
									<td width=\"20%\"><strong>Category Description:</strong></td><td width=\"80%\"><textarea name=\"catdesc\" cols=\"57\" rows=\"10\"></textarea></td>
								</tr>
							</table>									
							<br />
							<center><input type=\"submit\" name=\"submit\" class=\"button\" value=\"Create New Category!\" /></center>
						</form>";	
		}
	}	
	elseif ($_GET[action] == "editcategory" && isset($_GET[id])) {
		if (isset($_POST[submit])) {
			$sql = "UPDATE `" . $DBTABLEPREFIX . "categories` SET cat_icon = '$_POST[caticon]', cat_name = '$_POST[catname]', cat_desc = '$_POST[catdesc]' WHERE cat_id = '$_GET[id]'";
			$result = mysql_query($sql);
			
			if ($result) {
				$content = "
							<center>Your category's details have been updated, and you are being redirected to the main page.</center>
							<meta http-equiv='refresh' content='1;url=$menuvar[CATEGORIES]'>";
			}
			else {
				$content = "
							<center>There was an error while updating your category's details. You are being redirected to the main page.</center>
							<meta http-equiv='refresh' content='5;url=$menuvar[CATEGORIES]'>";						
			}
		}
		else {
			$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "categories` WHERE cat_id = '$_GET[id]' LIMIT 1";
			$result = mysql_query($sql);
			
			if (mysql_num_rows($result) == 0) {
				$content = "
							<center>There was an error while accessing the category's details you are trying to update. You are being redirected to the main page.</center>
							<meta http-equiv='refresh' content='5;url=$menuvar[CATEGORIES]'>";	
			}
			else {
				$row = mysql_fetch_array($result);
				
				$content .= "
							<form name=\"editcategoryform\" action=\"$menuvar[CATEGORIES]&amp;action=editcategory&amp;id=$row[cat_id]\" method=\"post\">
								<table class=\"tableBorder\" cellpadding=\"0\" cellspacing=\"1\" width=\"100%\">
									<tr>
										<td class=\"title1\" colspan=\"2\">Edit Category Information</td>
									</tr>
									<tr class=\"row1\">
										<td width=\"20%\"><strong>Category Icon:</strong></td><td width=\"80%\"><input name=\"caticon\" type=\"text\" size=\"60\" value=\"$row[cat_icon]\" /></td>
									</tr>
									<tr class=\"row2\">
										<td width=\"20%\"><strong>Category Name:</strong></td><td width=\"80%\"><input name=\"catname\" type=\"text\" size=\"60\" value=\"$row[cat_name]\" /></td>
									</tr>
									<tr class=\"row1\">
										<td width=\"20%\"><strong>Category Description:</strong></td><td width=\"80%\"><textarea name=\"catdesc\" cols=\"57\" rows=\"10\">$row[cat_desc]</textarea></td>
									</tr>
								</table>									
								<br />
								<center><input type=\"submit\" name=\"submit\" class=\"button\" value=\"Update Category Information\" /></center>
							</form>";							
			}			
		}
	}
	else {
		if ($_GET[action] == "deletecategory") {
			$sql = "DELETE FROM `" . $DBTABLEPREFIX . "categories` WHERE cat_id='$_GET[id]' LIMIT 1";
			$result = mysql_query($sql);
		}		
		
		//==================================================
		// Print out our users table
		//==================================================
		$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "categories` ORDER BY cat_name ASC";
		$result = mysql_query($sql);
		
		$x = 1; //reset the variable we use for our row colors	
		
		$content = "<table class=\"tableBorder\" cellpadding=\"0\" cellspacing=\"1\" width=\"100%\">
							<tr>
								<td class=\"title1\" colspan=\"4\">
									<div style='float: right;'><a href=\"$menuvar[CATEGORIES]&amp;action=newcategory\"><img src='images/plus.png' alt='Add a new category' /></a></div>
									Current Categories
								</td>
							</tr>							
							<tr class=\"title2\">
								<td>Icon</td><td>Name</td><td>Description</td><td></td>
							</tr>";
							
		while ($row = mysql_fetch_array($result)) {
			$icon = ($row['cat_icon'] == "") ? "No Icon" : "<img src=\"" . $row['cat_icon'] . "\" alt=\"" . $row['cat_name'] . "\" />";
			
			$content .=			"<tr class=\"row" . $x . "\">
									<td>$icon</td>
									<td>$row[cat_name]</td>
									<td>$row[cat_desc]</td>
									<td style=\"\">
										<center><a href=\"$menuvar[CATEGORIES]&amp;action=editcategory&amp;id=$row[cat_id]\"><img src='images/check.png' alt='Edit User Details' /></a> <a href=\"$menuvar[CATEGORIES]&amp;action=deletecategory&amp;id=$row[cat_id]\"><img src=\"images/x.png\" alt='Delete User' /></a></center>
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