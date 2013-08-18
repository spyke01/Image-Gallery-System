<? 
/***************************************************************************
 *                               viewgallery.php
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
	
	$sql = "SELECT cat_name FROM `" . $DBTABLEPREFIX . "categories` WHERE cat_id = '" . $actual_id . "'";
	$result = mysql_query($sql);
	if ($row = mysql_fetch_array($result)) { $currentGallery = $row['cat_name']; }
	mysql_free_result($result);
	
	$x = 2;
	$rowCount = 1;
			
	$page_content .= "
							<table class=\"tableBorder\" cellpadding=\"0\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td class=\"title1\" colspan=\"3\">Viewing Gallery - $currentGallery</td>
								</tr>";
									
	$sql = "SELECT * FROM `" . $DBTABLEPREFIX . "pics` WHERE pics_cat_id = '" . $actual_id . "' ORDER BY pics_timestamp DESC";
	$result = mysql_query($sql);
	
	if (mysql_num_rows($result) == 0) {
		$page_content .= "
										<td class=\"row" . $x . "\">
											This gallery does not currently contain any pictures.							
										</td>";
	}
	else {
		while ($row = mysql_fetch_array($result)) {
		
			$rating = number_format($row['pics_rating'] / $row['pics_total_ratings'], 0, '', '');
			$ratingWidth = $rating * 25;
							
			if ($rowCount == 1) {
				$x = ($x == 1) ? 2 : 1;	
				$page_content .= "\n								<tr>";
			}		
		
			$page_content .= "
										<td class=\"row" . $x . "\">
											<div style=\"text-align: center;\">
												" . $row['pics_name'] . "<br />
												<a href=\"" . $menuvar['VIEWPIC'] . "&id=" . $row['pics_id'] . "\"><img src=\"" . $row['pics_file'] . "\" alt=\"" . $row['pics_name'] . "\" /></a><br />
												<ul class='star-rating'>
													<li class='current-rating' style='width: " . $ratingWidth . "px;'>Currently $rating/5 Stars.</li>
													<li><a href='#' title='1 star out of 5' class='one-star'>1</a></li>
													<li><a href='#' title='2 stars out of 5' class='two-stars'>2</a></li>
													<li><a href='#' title='3 stars out of 5' class='three-stars'>3</a></li>
													<li><a href='#' title='4 stars out of 5' class='four-stars'>4</a></li>
													<li><a href='#' title='5 stars out of 5' class='five-stars'>5</a></li>
												</ul>		
											</div>							
										</td>";
		
			$page_content .= ($rowCount == 3) ? "\n								</tr>" : "";	
			$rowCount = ($rowCount == 3) ? 1 : $rowCount++;
		}
		mysql_free_result($result);
	}
					
	$page_content .= "
							</table>	
							<br /><br />";
			
	$page->setTemplateVar("PageTitle", "Viewing Gallery - $currentGallery");
	$page->setTemplateVar("PageContent", $page_content);
?>