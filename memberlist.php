<? 
/***************************************************************************
 *                               memberlist.php
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

		$content = "
					<br />
						<table class=\"tableBorder\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\">
							<tr class=\"title1\">
								<td class=\"VForumT1\" colspan=\"9\">Memberlist</td>
								</tr>
								<tr class=\"title2\"> 
									<td class=\"MLT2Column1\">Username</td>
									<td class=\"MLT2Column2\">Total Pictures Posted</td>
									<td class=\"MLT2Column3\">Joined</td>
								</tr>";



			$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "users` ORDER BY users_username";
			$result = mysql_query($sql);
			
			if(mysql_num_rows($result) == 0) {
				$content .= "\nError reading users table section 1.<br /><br />";
			} 
			else {
				$x = 1;	
				$totalPics = 0;
				
				while ( $row = mysql_fetch_array($result)) {
					$sql2 = "SELECT COUNT(*) AS numRows FROM `" . $DBTABLEPREFIX . "pics` WHERE pics_user_id = '" . $row['users_id'] . "'";
					$result2 = mysql_query($sql2);
					$totalPics = ($row2 = mysql_fetch_array($result2)) ? $row2['numRows'] : 0;
					mysql_free_result($result2);
				
					$content .= "
								<tr class=\"row" . $x . "\">			
									<td class=\"MLR1Column1\"><a href=\"$menuvar[PROFILE]&action=viewprofile&id=" . $row['users_id'] . "\">" . $row['users_username'] . "</a></td>
									<td class=\"MLR1Column2\">" . $totalPics . "</td>
									<td class=\"MLR1Column3\">" . makeDate($row['users_joined']) . "</td>
								</tr>";
								
					$x = ($x == 1) ? 2 : 1;						
				}
			}
			mysql_free_result($result); //free our query
		

$content .= "		
						</table>";


$page->setTemplateVar('PageContent', $content);	
?>
