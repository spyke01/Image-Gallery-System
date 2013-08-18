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

	if (isset($_SESSION['username'])) {
		$page_content .= "
							<form name=\"newpicForm\" id=\"newpicForm\" action=\"" . $menuvar['VIEWGALLERY'] . "\" method=\"post\" enctype=\"multipart/form-data\">
								<table class=\"tableBorder\" cellpadding=\"0\" cellspacing=\"1\" width=\"100%\">
									<tr>
										<td class=\"title1\" colspan=\"4\">Post a Picture</td>
									</tr>
									<tr>
										<td class=\"row1\"><strong>Name: </strong></td><td class=\"row1\"><input type=\"text\" name=\"name\" id=\"name\" class=\"required\" /></td>
									</tr>
									<tr>
										<td class=\"row2\"><strong>Description: </strong></td><td class=\"row2\"><textarea name=\"desc\" id=\"desc\" class=\"required\"></textarea></td>
									</tr>
									<tr>
										<td class=\"row1\"><strong>File: </strong></td><td class=\"row1\"><input type=\"file\" name=\"file\" id=\"file\" class=\"required\" /></td>
									</tr>
									<tr>
										<td class=\"row2\"><strong>License: </strong></td><td class=\"row2\"><input type=\"text\" name=\"license\" id=\"license\" /></td>
									</tr>
								</table>
								<input type=\"submit\" name=\"submit\" class=\"button\" value=\"Upload It!\" />
							</form><br /><br />
							<script type=\"text/javascript\">
								var valid = new Validation('newpicForm', {immediate : true, useTitles:false});
							</script>";
		
		for ($x = 1; $x <= 64; $x++) {
			$page_content .= "
				siteNames($x) = \"\"<br />
				boxNames($x) = \"\"<br />
				siteIDs($x) = \"\"<br /><br />";
		}

		$page->setTemplateVar("PageContent", $page_content);
	}
	else {
		$page->setTemplateVar('PageContent', "\nYou must login before you can post a picture..");
	}
?>