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

/**
 * Description of genParaContact
 *
 * @author Celio@Opixido
 */
class genParaContact {

    function __construct($para, $template) {

        if ($para['fk_contact_id']) {

            $sql = 'SELECT Con.*, Ron.rubrique_id FROM plug_contact AS Coff , plug_contact AS Con,
                                s_rubrique AS Roff , s_rubrique AS Ron
                        WHERE
                                Coff.contact_id = ' . sql($para['fk_contact_id']) . '
                                AND Coff.fk_rubrique_id = Roff.rubrique_id
                                AND Roff.fk_rubrique_version_id = Ron.rubrique_id
                                AND Con.fk_rubrique_id = Ron.rubrique_id
                                AND Coff.contact_email = Con.contact_email

                    ';

            $res = GetAll($sql);

            if (count($res)) {

                $template->contact = '<a href="' . getUrlFromId($res[0]['rubrique_id'], LG, array('c_qui' => $res[0]['contact_id'])) . '" class="contact_link">' . t('nous_contacter') . '</a>';
            } else {

                $template->contact = ' ';
            }
        }
    }

}

