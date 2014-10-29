<?php

#
# This file is part of oCMS.
#
# oCMS is free software: you cgan redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# oCMS is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with oCMS. If not, see <http://www.gnu.org/licenses/>.
#
# @author Celio Conort / Opixido 
# @copyright opixido 2012
# @link http://code.google.com/p/opixido-ocms/
# @package ocms
#

class progressBar {

    var $nbTotal = 0;
    var $nbCurrent = 0;
    var $nbPercent = 0;
    var $currentElement = '';
    var $titleText = 'Newsletter';
    var $waitingText = 'Envoi en cours, merci de ne pas fermer cette fen&ecirc;tre';
    var $doneText = 'Mails envoy&eacute;s';
    var $currentText = 'Mail en cours';
    var $secondLeftText = 'Secondes restantes';
    var $finishText = 'OK';
    var $extraFlush = false;

    function progressBar() {

        ob_start();
    }

    function updateNb() {
        
    }

    function updatePercent() {
        
    }

    function increment() {
        set_time_limit(30);
        $this->nbCurrent++;
        $this->update();
    }

    function update() {

        print('<script type="text/javascript">' . "\n");

        print('<!--' . "\n");

        if ($this->extraFlush) {
            print("
			// ******************************************* FLUSHING ************************************************ 
			// ******************************************* FLUSHING ************************************************ 
			// ******************************************* FLUSHING ************************************************ 
			// ******************************************* FLUSHING ************************************************ 
			// ******************************************* FLUSHING ************************************************ 
			// ******************************************* FLUSHING ************************************************ 
			");
        }
        print('cP(' . $this->nbCurrent . ', "' . $this->currentElement . '");' . "\n");

        print('-->');
        print('</script>' . "\n");

        flush();
        ob_flush();
        flush();
        ob_flush();
        flush();
        ob_flush();
        flush();
        ob_flush();
        flush();
        ob_flush();
        flush();
        ob_flush();
        ob_flush();
        ob_flush();
        ob_flush();
        ob_flush();
        ob_flush();
        ob_flush();
        flush();
        flush();
        flush();
        flush();
        flush();
        flush();
        flush();
        ob_flush();
        ob_flush();
        ob_flush();
        ob_flush();
        ob_flush();
        ob_flush();
        ob_flush();
    }

    /**
     * Prints the whole HTML CODE needed
     *
     */
    function genHtml() {

        print('
			<style type="text/css">		

			.tableaupercent {
			        border:1px solid #000;
			        padding:1px;
			}
			
			.tableaupercent * {
				font-family:arial, serif;
				font-size:11px;
			}
			
			.tableaupercent input {
				background:none;
				border:0;
				font-weight:bold;
			}
			
			
			
			</style>
			
			<form name="envoimail" style="width:700px;position:absolute;left:50%;margin-left:-350px;top:20px;z-index:5000">
				<table width="700" class="tableaupercent" >
					<tr>
						<td width="700" style="padding:5px;" align="center" bgcolor="#cccccc">
						<b>' . $this->titleText . '</b>
						</td></tr>
					<tr>
						<td width="700" style="padding:5px;" bgcolor="#dddddd">
						Status : 
						<input type="text" size="70" id="newsletter_status" name="status" style="color:red;font-weight:bold;font-size:14px;border:0px;" value="' . $this->waitingText . '" /><br />
						' . $this->doneText . ' : 
						<input type="text" size="70" id="newsletter_nbsent"  name="nbsent" value="0" style="text-align:left" /> <br />
						' . $this->currentText . ' : 
						<input type="text" size="70" id="newsletter_actuel"  name="actuel" value="0" style="text-align:left" /> <br />
						' . $this->secondLeftText . ' : 
						<input type="text" size="70" id="newsletter_secondes" name="secondes" value="0" style="text-align:left" /> <br />
						</td></tr>
						<tr>
						<td align="center" bgcolor="#dddddd">
							<table  border="0">
								<tr>
									<td width="702" class="tableaupercent" align="left">
									<div id="newsletter_imgpercent" style="width:1px;background:blue;" >&nbsp;</div>
									</td>
								</tr>
								<tr>
									<td width="702" align="center">
									<input type="text" size="4" value="0" id="newsletter_percent" name="percent" style="text-align:right;font-weight:bold"/> %<br /><img src="pixel.gif" height="1" width="702"></td>
									</td>
								</tr>
						
							</table>
						</td>
					</tr>
				</table>
			</form>
		
		
		
	<script language="javascript">
	
	function gid (ide) {
		return document.getElementById(ide);
	}
	finishText = "' . $this->finishText . '";
	debut = new Date();
	secstart = debut.getTime();
	zeTotal = ' . $this->nbTotal . ';
	imgwidth = 700;
	
	n_actuel = gid("newsletter_actuel");
	n_nbsent = gid("newsletter_nbsent");
	n_percent = gid("newsletter_percent");
	//n_nbsent = gid("newsletter_nbsent");
	n_imgpercent = gid("newsletter_imgpercent");
	function cP (zeNb,actuel) {		        
	        n_actuel.value = actuel;
	        n_nbsent.value = zeNb + " / "+zeTotal;
	        
	        if(zeNb == zeTotal) {
	        	gid("newsletter_status").value = finishText;
	        	gid("newsletter_status").style.color="green";
	        }
	        
	        n_percent.value = Math.floor((zeNb /zeTotal ) * 100);	
	        n_imgpercent.style.width = Math.floor((zeNb / zeTotal ) * imgwidth)+"px";
	        calcSec();
	        
	}
	
	function calcSec() {
	        maintenant = new Date();
	        secnow = maintenant.getTime();
	        sececoule = Math.floor(secnow - secstart) ;
	        document.forms.envoimail.secondes.value =  Math.floor ((( ( (100 )/ (document.forms.envoimail.percent.value-percentStart )) * sececoule ) - sececoule)/1000);
	        if(document.forms.envoimail.secondes.value == 0)
	                clearInterval();
	
	}
	
	//setInterval("calcSec()",500);
	percentStart = ' . $this->nbPercent . ';
	zePercent = ' . $this->nbPercent . ';
	zeNb = ' . $this->nbCurrent . ';
	
	</script>
		
			');
    }

}

?>