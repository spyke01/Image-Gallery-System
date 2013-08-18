<? 
/***************************************************************************
 *                               index.php
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
// If the db connection file is missing we should redirect the user to install page
if (!file_exists('_db.php')) {
	header("Location: http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/install.php");	
	exit();
}

include 'includes/header.php';

$requested_page_id = $_GET['p'];
$requested_section = $_GET['s'];
$requested_id = $_GET['id'];
$requested_page = $_GET[page];

$actual_page_id = ($requested_page_id == "" || !isset($requested_page_id)) ? 1 : $requested_page_id;
$actual_page_id = parseurl($actual_page_id);
$actual_section = parseurl($requested_section);
$actual_id = parseurl($requested_id);
$actual_page = parseurl($requested_page);
$actual_page = (trim($actual_page) == "") ? "1" : $actual_page;
$page_content = "";

// Warn the user if the install.php script is present
if (file_exists('install.php')) {
	$page_content = "<div class=\"errorMessage\">Warning: install.php is present, please remove this file for security reasons.</div>";
}

// We want to show all of our menus by default
$page->setTemplateVar("options_active", ACTIVE);
$page->setTemplateVar("categories_active", ACTIVE);
//========================================
// Logout Function
//========================================
// Prevent spanning between apps to avoid a user getting more acces that they are allowed
if ($_SESSION['script_locale'] != rtrim(dirname($_SERVER['PHP_SELF']), '/\\') && session_is_registered('userid')) {
	session_destroy();
}

if ($actual_page_id == "logout") {
	define('IN_FTSIGS', true);
	include '_db.php';
	include_once ('includes/menu.php');
	include_once ('config.php');
	global $igs_config;
	
	//Destroy Session Cookie
	$cookiename = $igs_config['ftsigs_cookie_name'];
	setcookie($cookiename, false, time()-2592000); //set cookie to delete back for 1 month
	
	//Destroy Session
	session_destroy();
	if(!session_is_registered('first_name')){
		header("Location: http://" . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/index.php");	
		exit();
	}
}

//Check to see if advanced options are allowed or not
if (version_functions("advancedOptions") == true) {
	// If the system is locked, then only a moderator or admin should be able to view it
	if ($_SESSION['user_level'] != ADMIN && $_SESSION['user_level'] != MOD && $igs_config['ftsigs_active'] != ACTIVE) {
		if ($actual_page_id == "login") {
			include 'login.php';
		}
		else {	
			$page->setTemplateVar("PageTitle", 'Currently Disabled');
			$page->setTemplateVar("PageContent", bbcode($igs_config[ftsigs_inactive_msg]));
			$page->makeMenuItem("options", "Login", "index.php?p=login");
		}
	}
	else {
		//========================================
		// Admin panel options
		//========================================
		if ($actual_page_id == "admin") {
			if (!$_SESSION[username]) { include 'login.php'; }
			else {
				if ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) {
					if ($actual_section == "" || !isset($actual_section)) {
						include 'admin.php'; 
						$page->setTemplateVar("PageTitle", "Admin Panel");
					}
					elseif ($actual_section == "settings") {
						include 'settings.php';				
						$page->setTemplateVar("PageTitle", "Settings");
					}
					elseif ($actual_section == "categories") {
						include 'categories.php';		
						$page->setTemplateVar("PageTitle", "Categories");		
					}
					elseif ($actual_section == "themes") {
						include 'themes.php';			
						$page->setTemplateVar("PageTitle", "Themes");	
					}
					elseif ($actual_section == "users") {
						include 'users.php';			
						$page->setTemplateVar("PageTitle", "Users");	
					}
					elseif ($actual_section == "gallery") {
						include 'viewgallery.php';			
						$page->setTemplateVar("PageTitle", "Gallery");	
					}
					$page->makeMenuItem("options", "Settings", "index.php?p=admin&amp;s=settings");
					$page->makeMenuItem("options", "Menus", "index.php?p=admin&amp;s=menus");
					$page->makeMenuItem("options", "Categories", "index.php?p=admin&amp;s=categories");
					$page->makeMenuItem("options", "Themes", "index.php?p=admin&amp;s=themes");
					$page->makeMenuItem("options", "Users", "index.php?p=admin&amp;s=users");
					$page->makeMenuItem("options", "Gallery", "index.php?p=admin&amp;s=gallery");
					
					// We only want to see the options menu while in the admin panel
					$page->setTemplateVar("options_active", ACTIVE);
					$page->setTemplateVar("categories_active", INACTIVE);
				}
				else { setTemplateVar("PageContent", "You are not authorized to access the admin panel."); }
			}
		}
		elseif ($actual_page_id == "login") {
			include 'login.php';
			$page->setTemplateVar("PageTitle", "Login");	
			$page->setTemplateVar("options_active", ACTIVE);
			$page->setTemplateVar("categories_active", ACTIVE);
		}
		elseif ($actual_page_id == "memberlist") {
			include 'memberlist.php';
			$page->setTemplateVar("PageTitle", "Memberlist");
			$page->setTemplateVar("options_active", ACTIVE);
			$page->setTemplateVar("categories_active", ACTIVE);
		}
		elseif ($actual_page_id == "register") {
			include 'register.php';
			$page->setTemplateVar("PageTitle", "Create an Account");
			$page->setTemplateVar("options_active", ACTIVE);
			$page->setTemplateVar("categories_active", ACTIVE);
		}
		elseif ($actual_page_id == "postpic") {
			include 'postpic.php';
			$page->setTemplateVar("PageTitle", "Post a Picture");
			$page->setTemplateVar("options_active", ACTIVE);
			$page->setTemplateVar("categories_active", ACTIVE);
		}
		elseif ($actual_page_id == "viewgallery" && $actual_id != "") {
			include 'viewgallery.php';
			$page->setTemplateVar("PageTitle", "Viewing Gallery");
			$page->setTemplateVar("options_active", ACTIVE);
			$page->setTemplateVar("categories_active", ACTIVE);
		}
		else {
			//=================================================
			// If not in admin section, then print out our 
			// categories from the database
			//=================================================		
			$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "categories` ORDER BY cat_name ASC";
			$result = mysql_query($sql);
			
			if (mysql_num_rows($result) == "0") { $page_content .= "There were no categories found in the database, please add one and try again!"; }
			else {
				//================================================
				// Get Page Info
				//================================================			
				$totalPics = 0;
				$x = 2;
				
				$page_content .= "
										<table class=\"tableBorder\" cellpadding=\"0\" cellspacing=\"1\" width=\"100%\">
											<tr>
												<td class=\"title1\" colspan=\"4\">Current Categories</td>
											</tr>
											<tr>
												<td class=\"title2\">&nbsp;</td><td class=\"title2\">Name</td><td class=\"title2\">Description</td><td class=\"title2\">Pictures</td>
											</tr>";
										
				while ($row = mysql_fetch_array($result)) {
					$sql2 = "SELECT COUNT(*) AS numRows FROM `" . $DBTABLEPREFIX . "pics` WHERE pics_cat_id = '" . $row['cat_id'] . "'";
					$result2 = mysql_query($sql2);
					$totalPics = ($row2 = mysql_fetch_array($result2)) ? $row2['numRows'] : 0;
					mysql_free_result($result2);
				
					$icon = ($row['cat_icon'] == "") ? "" : "<img src=\"" . $row['cat_icon'] . "\" alt=\"" . $row['cat_name'] . "\" />";
				
					$page_content .= "
											<tr>
												<td class=\"row" . $x . "\">$icon</td><td class=\"row" . $x . "\"><a href=\"" . $menuvar['VIEWGALLERY'] . "&id=" . $row['cat_id'] . "\">" . $row['cat_name'] . "</a></td><td class=\"row" . $x . "\">" . $row['cat_desc'] . "</td><td class=\"row" . $x . "\">$totalPics</td>
											</tr>";
											
					$x = ($x == 1) ? 2 : 1;						
				}
				mysql_free_result($result);
							
				$page_content .= "
										</table>	
										<br /><br />";
				
				$page->setTemplateVar("PageTitle", $igs_config['ftsigs_site_title']);
				$page->setTemplateVar("PageContent", $page_content);		
			}
	
		}
	
		//================================================
		// Get Menus
		//================================================
		
		$page->makeMenuItem("top", "Home", "index.php");
	
		// Make default menu items
		if ($_SESSION['user_level'] == MOD || $_SESSION['user_level'] == ADMIN) {
			$page->makeMenuItem("top", "Admin Panel", "index.php?p=admin");
		}
		
		if (!isset($_SESSION[username])) {
			$page->makeMenuItem("options", "Login", "index.php?p=login");
			$page->makeMenuItem("options", "Create An Account", "index.php?p=register");
			$page->makeMenuItem("options", "Memberlist", "index.php?p=memberlist");
		}
		else {
			$page->makeMenuItem("options", "Quick Post", "index.php?p=postpic");
			$page->makeMenuItem("options", "Memberlist", "index.php?p=memberlist");
			$page->makeMenuItem("options", "Logout", "index.php?p=logout");
		}
		
		// Make category menu items
		$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "categories` ORDER BY cat_name";
		$result = mysql_query($sql);
				
		while ($row = mysql_fetch_array($result)) {	
			$sql2 = "SELECT COUNT(*) AS numRows FROM `" . $DBTABLEPREFIX . "pics` WHERE pics_cat_id = '" . $row['cat_id'] . "'";
			$result2 = mysql_query($sql2);
			$totalPics = ($row2 = mysql_fetch_array($result2)) ? $row2['numRows'] : 0;
			mysql_free_result($result2);
			
			if ($totalPics == "0") { $page->makeMenuItem("categories", "$row[cat_name] (" . $totalPics . ")", "index.php"); }
			else { $page->makeMenuItem("categories", "$row[cat_name] (" . $totalPics . ")", "index.php?p=viewcategory&amp;id=$row[cat_id]"); }
		}	
		
		mysql_free_result($result);
	}
}
else { $page->setTemplateVar("PageContent", version_functions("advancedOptionsText")); }

version_functions("no");
include "themes/" . $igs_config['ftsigs_theme'] . "/template.php";
?>