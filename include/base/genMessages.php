<?php

#
# This file is part of oCMS.
#
# oCMS is free software: you can redistribute it and/or modify
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

class genMessages
{

    public $messages = [];
    

    public function add($txt, $level = 'error')
    {
        $this->messages[$level][] = $txt;
    }

    public function addInstant($txt, $level = 'error')
    {
        $this->genCss();
        p('
			<div id="genMessages">
			');
        $this->genMessage($txt, $level);
        p('</div>');
    }

    public function gen()
    {
        if (count($this->messages)) {

            $this->genCss();
            p('
			<div id="genMessages">


			');
            foreach ($this->messages as $level => $messages) {
                if (count($messages)) {

                    foreach ($messages as $msg) {
                        $this->genMessage($msg, $level);
                    }
                }
            }
            p('</div>');
        }
    }

    public function genMessage($msg, $level)
    {
        $level = $level == 'info' ? 'secondary' : 'danger';
        p('<div class="box-shadow alert ' . $level . '" ><a  onclick="$(this).parent().remove();" href="#">X</a>');

        if (is_array($msg) || is_object($msg)) {
            p('<pre>');
            print_r($msg);
            p('</pre>');
        } else {
            p($msg);
        }

        p('</div>');
    }

    public function genCss()
    {
        if (!$GLOBALS['genMessageCss']) {

            $GLOBALS['genMessageCss'] = true;

            p('
			<style type="text/css">

				#genMessages {
					position:absolute;
					left:50%;
					width:400px;
					margin-left:-200px;
					top:0px;
					z-index:500000000;
                                        margin-top:30px;
				}

                                #genMessages div {
                                    margin-top:15px;
                                }



			</style>');
        }
    }

}

$GLOBALS['genMessageCss'] = false;
