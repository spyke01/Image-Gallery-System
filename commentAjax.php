<? 
/***************************************************************************
 *                               updatecomment.php
 *                            -------------------
 *   begin                : Tuseday, March 14, 2006
 *   copyright            : (C) 2006 Fast Track Sites
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
	
	$actual_id = keepsafe($_GET[id]);
		
	//================================================
	// Update our comment into the database
	//
	// Thanks to Joe for all his hard work and help with the AJAX
	//================================================
	// Update a comment
	if ($_GET[action] == "updatecomment") {
		$comments = keeptasafe($_POST[value]);
		
		$sql = "UPDATE `" . $DBTABLEPREFIX . "comments` SET comments_comment = '$comments' WHERE comments_id = '$actual_id'";
		$result = mysql_query($sql);

		$comments = stripslashes($comments);
		$comments = bbcode($comments);
		
		echo bbcode($comments);
	}
	// Delete a comment
	elseif ($_GET[action] == "deletecomment") {
		$sql = "DELETE FROM `" . $DBTABLEPREFIX . "comments` WHERE comments_id = '$actual_id'";
		$result = mysql_query($sql);
	}
	// Get a comment's text
	elseif ($_GET[action] == "getcommenttext") {
		$sql = "SELECT comments_comment FROM `" . $DBTABLEPREFIX . "comments` WHERE comments_id = '$actual_id'";
		$result = mysql_query($sql);
		
		$row = mysql_fetch_array($result);
		$comments = stripslashes($row[comments_comment]);
		mysql_free_result($result);
		$comments = bbcode($comments);
		
		echo bbcode($comments);
	}
	// Post a comment
	elseif ($_GET[action] == "postcomment") {
		$name = keeptasafe($_POST[name]);
		$emailaddress = keeptasafe($_POST[emailaddress]);
		$website = keeptasafe($_POST[website]);
		$comments = keeptasafe($_POST[comments]);
		$website = (substr($website, 0, 4) == "http") ? $website : "http://" . $website;		
		
		if ($_SESSION['userid'] != "") { $sql = "INSERT INTO `" . $DBTABLEPREFIX . "comments` (comments_entry_id, comments_name, comments_user_id, comments_email, comments_website, comments_date, comments_comment) VALUES ('$actual_id', '$name', '" . $_SESSION['userid'] . "', '$emailaddress', '$website', '" . time() . "', '$comments')"; }
		else { $sql = "INSERT INTO `" . $DBTABLEPREFIX . "comments` (comments_entry_id, comments_name, comments_email, comments_website, comments_date, comments_comment) VALUES ('$actual_id', '$name', '$emailaddress', '$website', '" . time() . "', '$comments')"; }
		$result = mysql_query($sql);
		
		$newCommentId = mysql_insert_id();

		$sql2 = "SELECT * FROM `" . $DBTABLEPREFIX . "comments` WHERE comments_entry_id = '$actual_id' ORDER BY comments_date ASC";
		$result2 = mysql_query($sql2);
		
		$page_content = "";		
		
		if (mysql_num_rows($result2) == "0") { // No comments yet!
			$page_content .= "\n					<div class=\"comment\">";
			$page_content .= "\n						Be the first to post a comment!";
			$page_content .= "\n					</div><br />";	
		}
		else {	 // Print all our comments	
			while ($row2 = mysql_fetch_array($result2)) {
				if ($row2[comments_id] == $newCommentId) { $page_content .= "\n					<a name=\"newComment\"></a>"; }
							
				if ($newCommentId == $row2[comments_id]) { $page_content .= "\n					<div id=\"newComment\" class=\"comment\">"; }
				else { $page_content .= "\n					<div id=\"$row2[comments_id]\" class=\"comment\">"; }
				
				$poster = ($row2[comments_website] != "") ? "<a href=\"$row2[comments_website]\">$row2[comments_name]</a>" : "$row2[comments_name]";
				$page_content .= "\n						$poster<br />";
				if ($_SESSION['user_level'] == ADMIN || $_SESSION['user_level'] == MOD) { $page_content .= "\n						Email: $row2[comments_email]<br />"; }
				$page_content .= "\n						<small>Posted on " . makeDate($row2[comments_date]) . "</small><br /><br />";
				$page_content .= "\n						<div id=\"$row2[comments_id]_text\">";
				$page_content .= "\n							" . bbcode($row2[comments_comment]) . "<br /><br />";
				$page_content .= "\n						</div>";
						
				// Allow editing of comments
				if ($_SESSION['user_level'] == ADMIN || $_SESSION['user_level'] == MOD || $_SESSION['userid'] == $row2['comments_user_id']) {
					$page_content .= "\n						<script type=\"text/javascript\">";
					$page_content .= "\n							new Ajax.InPlaceEditor('$row2[comments_id]_text', 'updatecomment.php?action=updatecomment&id=$row2[comments_id]', {rows:8,cols:50,loadTextURL:'updatecomment.php?action=getcommenttext&id=$row2[comments_id]'});";
					$page_content .= "\n						</script>";
					$page_content .= "\n						<strong>[Click On Your Comment To Edit It]</strong><br />";
				}
							
				// Allow deletion of comments
				if ($_SESSION['user_level'] == ADMIN || $_SESSION['user_level'] == MOD || $_SESSION['userid'] == $row2['comments_user_id']) { $page_content .= "\n						<a onclick=\"new Ajax.Request('updatecomment.php?action=deletecomment&id=$row2[comments_id]', {asynchronous:true, onSuccess:function(){ new Effect.Squish('$row2[comments_id]');}});\">[Delete This Comment]</a>"; }
				$page_content .= "\n					</div><br />";
			}
		}
		
		echo $page_content;
	}
	
	else {
		// Do Nothing
	}

?>
