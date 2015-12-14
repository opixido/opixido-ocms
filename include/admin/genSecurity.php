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
# @copyright opixido 2009
# @link http://code.google.com/p/opixido-ocms/
# @package ocms
#


if (!class_exists('genSecurity')) {

    /**
     * Gestion des autorisations d'accès
     */
    class genSecurity
    {

        var $authentified = false;
        var $adminpassword = "";
        var $adminuser = "";
        var $adminnom = "";
        var $adminid = "";
        var $checkedAuth = false;
        var $admintype = "";
        var $admindroits = "";
        var $superAdmin = false;
        var $myroles = false;
        var $tables = array();

        public function __construct()
        {

            global $alreadySecurising;
            global $gs_roles;

            /**
             * Type de Crypto
             */
            $this->crypto = new Crypt_AES();
            $this->crypto->setKey(crypto_key);
            $this->tables = getTables();

            /**
             * Clef unique pour la requete en cours
             */
            $this->uniqueId = rand();

            /**
             * Singleton manuel ...
             */
            if (ake($GLOBALS, 'gs_alreadySecurising')) {
                trigger_error('Trop d\'instances de genSecurity, verifier le code php', E_USER_ERROR);
            }
            $GLOBALS['gs_alreadySecurising'] = true;

            /**
             * Infos sur l'utilisateur
             */
            $this->roles = $gs_roles;
            $this->cacheRow = array();

            if (!empty($_SESSION['gs_adminpassword'])) {
                $this->adminpassword = $_SESSION['gs_adminpassword'];
            }


            if (ake($_POST, 'gs_adminpassword') && $_POST['gs_adminpassword']) {
                $_SESSION['gs_adminpassword'] = $this->adminpassword = $this->encrypt($_POST['gs_adminpassword']);
                setcookie('gs_admin', $this->adminpassword);
                $_COOKIE['gs_admin'] = $this->adminpassword;
            }

            if (ake($_POST, 'gs_logout') && $_GET['gs_logout']) {
                $this->clearAuth();
            }

            $_SESSION['gs_adminuser'] = $this->adminuser = ake($_POST, 'gs_adminuser') ? $_POST['gs_adminuser'] : akev($_SESSION, 'gs_adminuser');


            /**
             * Si on est bien authentifié, on redirige en fonction de ce qui était demandé précédement
             */
            if ($this->checkAuth() && ake($_POST, 'adminpassword') && strlen($_POST['adminpassword']) && strlen($_POST['gs_askedFor'])) {
                header('location:' . $_POST['gs_askedFor']);
            }
        }

        public function encrypt($str)
        {
            return $this->crypto->encrypt($str);
        }

        public function decrypt($str)
        {
            return $this->crypto->decrypt($str);
        }

        function recurseArbo($rubrique_id = 0, $fk_rub = 0)
        {
            /*
              On stock toutes les rubriques auquelles on a acc�
             */

            $this->allowedRubs[$rubrique_id] = true;
            if ($fk_rub) {
                $this->allowedRubs[$fk_rub] = true;
            }
            $sql = 'SELECT rubrique_id FROM s_rubrique WHERE  fk_rubrique_id = ' . $rubrique_id . ' ';
            $res = GetAll($sql);

            foreach ($res as $row) {
                $this->recurseArbo($row['rubrique_id']);
            }
        }

        function needAuth()
        {

            if (!$this->authentified) {

                $this->showForm();
                die();
            } else {
                return true;
            }
        }

        function notifyAdd($table, $id)
        {
            /*             * *
              Methode à appeller apres un ajout dans une table, afin de mettre a jour le cache
             */
            if (!empty($this->myroles[$table]) && $this->myroles[$table]['type'] !== 'all') {
                $this->myroles[$table]['rows'][] = $id;
                $this->myroles[$table]['initrows'][] = $id;
                $_SESSION['myRoles'][$table]['rows'][] = $id;
                $_SESSION['myRoles'][$table]['initrows'][] = $id;
                $this->cacheRow[$table . "_-_" . $id] = false;
            }
        }

        function isLogged()
        {
            return $this->checkAuth();
        }

        function checkAuth()
        {
            /*
              Connexion et verification
             */
            if (!$this->checkedAuth) {

                if (strlen($this->adminpassword)) {

                    if (!empty($_SESSION['gs_adminpassword']) && !empty($_COOKIE['gs_admin'])) {
                        if ($_SESSION['gs_adminpassword'] != $_COOKIE['gs_admin']) {
                            mail('conort@gmail.com', 'INED ADMIN session mismatch', var_export($_SESSION, true) . ' ----- ' . var_export($_COOKIE, true) . var_export($_SERVER, true));
                            $this->clearAuth();
                        }
                    } else {
                        $this->clearAuth();
                    }

                    $sql = "SELECT admin_nom,admin_pwd,admin_type,admin_id, admin_last_cx,admin_email
                			FROM s_admin
                			WHERE 
                			admin_login like '" . mes($this->adminuser) . "' ";

                    $row = GetSingle($sql);

                    $this->checkedAuth = true;

                    if (is_array($row) && array_key_exists("admin_nom", $row) && password_verify($this->decrypt($this->adminpassword), $row['admin_pwd'])) {

                        $this->authentified = true;

                        $this->adminnom = $row['admin_nom'];
                        $this->adminid = $row['admin_id'];
                        $this->admintype = $row['admin_type'];
                        $this->adminemail = $row['admin_email'];

                        if (empty($_SESSION['update_lastcx'])) {
                            $_SESSION['last_cx'] = $row['admin_last_cx'];
                            $sql = 'update s_admin set admin_last_cx= NOW() where admin_id=' . $this->adminid;
                            doSql($sql);
                            $_SESSION['update_lastcx'] = true;
                            $_SESSION['gs_admin_id'] = $row['admin_id'];
                        }

                        $this->getRoles();

                        $_SESSION['superAdmin'] = $this->superAdmin;


                        return true;
                    } else {
                        if ($_POST) {
                            $GLOBALS['errors'] = ta('error_bad_login_or_password');
                        }
                        $this->authentified = false;

                        return false;
                    }
                }
            } else {

                return $this->authentified;
            }
        }

        /**
         * Récupère la liste de mes roles
         * et les droits associés aux tables et enregistrements
         *
         */
        function getRoles($force = false)
        {

            global $_Gconfig;

            if (!empty($_SESSION['myRoles']) && !$force) {
                $this->myroles = $_SESSION['myRoles'];
                return;
            }
            $this->myroles = array();

            $sql = 'SELECT * FROM s_admin_role AS AR, s_role AS R , s_role_table AS RT WHERE
    					AR.fk_admin_id = "' . $this->adminid . '" 
    					AND
    					AR.fk_role_id = R.role_id
    					AND 
    					RT.fk_role_id = R.role_id
    					
    				';
            $res = GetAll($sql);

            if ($res[0]['role_table_table'] == 'all') {
                $this->superAdmin = True;

                return;
            }

            foreach ($res as $row) {

                if (strpos($row['role_table_champs'], ',')) {
                    $champs = $row['role_table_champs'] ? explode(',', $row['role_table_champs']) : 'all';
                } else {
                    $row['role_table_champs'] = str_replace("\r", "\n", $row['role_table_champs']);
                    $row['role_table_champs'] = str_replace("\n\n", "\n", $row['role_table_champs']);
                    $champs = $row['role_table_champs'] ? explode("\n", $row['role_table_champs']) : 'all';
                }

                $acs = array_values(explode(',', $row['role_table_actions']));
                $actions = array();
                foreach ($acs as $v) {
                    if ($v)
                        $actions[$v] = true;
                }
                /*
                  $actions[] = 'edit';
                  $actions[] = 'view';
                 */

                /**
                 * On definit les droits par défaut
                 */
                $tro = array(
                    'view' => $row['role_table_view'],
                    'add' => $row['role_table_add'],
                    'edit' => $row['role_table_edit'],
                    'del' => $row['role_table_delete'],
                    'champs' => $champs,
                    'condition' => array('arbo' => 1, 'proprio' => 1),
                    'actions' => $actions,
                    'type' => $row['role_table_type'],
                    'rows' => array(),
                    'initrows' => array()
                );

                if (is_array($this->myroles[$row['role_table_table']])) {
                    $this->myroles[$row['role_table_table']]['rows'] = array_merge($this->myroles[$row['role_table_table']]['rows'], $tro['rows']);
                    $this->myroles[$row['role_table_table']]['initrows'] = array_merge($this->myroles[$row['role_table_table']]['initrows'], $tro['initrows']);
                } else {
                    $this->myroles[$row['role_table_table']] = $tro;
                }


                /**
                 * Si on est dans un type PerUser on sélectionne les rows auquel cet user a accès
                 */
                if ($row['role_table_type'] == 'per_user') {


                    $sql = 'SELECT * FROM s_admin_rows
    						WHERE fk_admin_id = "' . $this->adminid . '" 
    						AND fk_table = "' . $row['role_table_table'] . '"';
                    $rRes = GetAll($sql);

                    /**
                     * On autorise toutes ces rows là
                     */
                    foreach ($rRes as $rRow) {
                        $this->myroles[$row['role_table_table']]['rows'][] = $rRow['fk_row_id'];
                        $this->myroles[$row['role_table_table']]['initrows'][] = $rRow['fk_row_id'];
                        /*
                          $tmp = array();
                          if ($row['role_table_table'] == 's_rubrique') {
                          $sql = 'SELECT rubrique_id FROM s_rubrique WHERE rubrique_id = ' . $rRow['fk_row_id'];
                          $rowww = GetSingle($sql);
                          $tmp[] = $rowww['rubrique_id'];

                          }
                         */
                    }
                    /* debug($rRes);
                      $this->myroles[$row['role_table_table']]['rows'] = array_merge($this->myroles[$row['role_table_table']]['rows'], $tmp);
                      $this->myroles[$row['role_table_table']]['initrows'] = array_merge($this->myroles[$row['role_table_table']]['initrows'], $tmp);
                     */

                    /**
                     * On autorise toutes les tables de relation inverse
                     * avec comme condition "arbo"
                     */
                    $this->recurvRelInv($row['role_table_table']);
                    $this->recurvRelTable($row['role_table_table']);
                }


                /**
                 * Si c'est specifique à ce role on voit à quoi ce role a accès
                 */
                if ($row['role_table_type'] == 'specific') {

                    /**
                     * On ajoute les identifiants listés
                     */
                    if (is_array($this->myroles[$row['role_table_table']]['rows'])) {
                        $this->myroles[$row['role_table_table']]['initrows'] = array_merge(explode(',', $row['role_table_specific']), $this->myroles[$row['role_table_table']]['initrows']);
                        $this->myroles[$row['role_table_table']]['rows'] = array_merge(explode(',', $row['role_table_specific']), $this->myroles[$row['role_table_table']]['rows']);
                    } else {
                        $this->myroles[$row['role_table_table']]['initrows'] = $this->myroles[$row['role_table_table']]['rows'] = explode(',', $row['role_table_specific']);
                    }

                    /**
                     * On autorise toutes les tables de relation inverse
                     * avec comme condition "arbo"
                     */
                    $this->recurvRelInv($row['role_table_table']);
                    $this->recurvRelTable($row['role_table_table']);
                } else if ($row['role_table_type'] == 'all') {
                    $this->recurvRelInv($row['role_table_table']);
                    $this->recurvRelTable($row['role_table_table']);
                }


                /**
                 * Si on peut ajouter dans cette table
                 * alors on regarde tous les enregistrements déjà ajoutés
                 * par cet utilisateur
                 *
                 * et le laisse les modifier
                 *
                 */
                if ($row['role_table_add']) {
                    if (in_array($row['role_table_table'], $this->tables)) {
                        $table = getTabField($row['role_table_table']);
                        $pk = getPrimaryKey($row['role_table_table']);

                        $chp = false;
                        if (!empty($table[$_Gconfig['field_creator']])) {
                            $chp = $_Gconfig['field_creator'];
                        } else if (!empty($table['fk_admin_id'])) {
                            $chp = 'fk_admin_id';
                        }

                        if ($chp) {
                            $sql = 'SELECT ' . $pk . ' FROM ' . $row['role_table_table'] . ' WHERE ' . $chp . ' = ' . sql($this->adminid);
                            $res = GetAll($sql);

                            foreach ($res as $Aow) {
                                $this->myroles[$row['role_table_table']]['rows'][] = $Aow[$pk];
                            }
                        }
                    }
                }
            }
            if (count($this->myroles['s_rubrique']['initrows'])) {
                $ar = $this->myroles['s_rubrique']['initrows'];
                foreach ($ar as $rid) {
                    $this->recurseRubrique($rid);
                }
            }
            $_SESSION['myRoles'] = $this->myroles;
        }

        function recurseRubrique($rid)
        {
            $sql = 'SELECT rubrique_id FROM s_rubrique WHERE '
                . 'fk_rubrique_id = ' . sql($rid);
            $res = DoSql($sql);
            foreach ($res as $r) {
                $this->myroles['s_rubrique']['rows'][] = $r['rubrique_id'];
                $this->recurseRubrique($r['rubrique_id']);
            }
        }

        function recurvRelTable($table)
        {

            global $tablerel, $_Gconfig;

            reset($tablerel);
            foreach ($tablerel as $reltable => $tab) {
                $otherTable = '';
                $found = false;
                foreach ($tab as $k => $v) {
                    if ($v == $table) {
                        $found = true;
                    } else if ($v != 's_admin') {
                        $otherTable = $v;
                    }
                }
                if ($found && $otherTable && $otherTable != $table) {
                    if (empty($this->myroles[$otherTable]) && !@in_array($table . '.' . $otherTable, $_Gconfig['gsNoFollowRel'])) {
                        $this->myroles[$otherTable] = array(
                            'view' => true,
                            'add' => true,
                            'edit' => true,
                            'del' => false,
                            'champs' => 'all',
                            'type' => 'all',
                            'condition' => 'none',
                            'actions' => array()
                        );
                    }
                }
            }

            reset($tablerel);
        }

        /**
         * We have access to all relinv linked to an allowed table
         *
         * @param string $table
         */
        function recurvRelInv($table)
        {

            global $relinv, $co;

            reset($relinv);

            /**
             * No relinv ... nothing to do
             */
            if (!ake($relinv, $table)) {
                return;
            }


            foreach ($relinv[$table] as $fkChamp => $tableau) {

                /**
                 * if relinv is not linking to self table
                 */
                if ($tableau[0] != $table) {

                    /**
                     * Can do anything
                     */
                    if (empty($this->myroles[$tableau[0]])) {

                        $this->myroles[$tableau[0]] = array(
                            'view' => true,
                            'add' => true,
                            'edit' => true,
                            'del' => true,
                            'champs' => 'all',
                            'type' => 'all',
                            'condition' => array('arbo', 'proprio'),
                            'actions' => array(),
                            'rows' => array()
                        );
                        if (!empty($this->myroles[$table]['rows'])) {
                            $this->myroles[$tableau[0]]['conditionSqlWhere'] = ' AND ' . $tableau[1] . ' IN (' . implode(',', $this->myroles[$table]['rows']) . ') ';
                        }
                    }

                    /**
                     * If parent tables has limited access
                     */
                    if (!empty($this->myroles[$table]['rows'])) {
                        /**
                         * Foreign key
                         */
                        $a = ($relinv[$table][$fkChamp]);

                        /**
                         * Selecting allowed parents to get allowed children
                         */
                        $sql = 'SELECT REL.' . getPrimaryKey($a[0]) . ' , REL.' . getPrimaryKey($a[0]) . '
								FROM ' . $a[0] . ' AS REL, ' . $table . ' AS T 
								WHERE REL.' . $a[1] . ' = T.' . getPrimaryKey($table) . '
								AND T.' . getPrimaryKey($table) . ' IN (' . implode(",", $this->myroles[$table]['rows']) . ') ';

                        $res = $co->GetAssoc($sql);


                        /**
                         * Avoid array_merge warning on non-array argument
                         */
                        if (!($this->myroles[$tableau[0]]['rows'])) {
                            $this->myroles[$tableau[0]]['rows'] = array(0);
                        }

                        /**
                         * Adding allowed rows of relinv
                         */
                        $this->myroles[$tableau[0]]['rows'] = array_merge(array_keys($res), $this->myroles[$tableau[0]]['rows']);

                        $this->recurvRelInv($tableau[0]);
                    } /**
                     * If access to all parents, then access all children ...
                     */ else if ($this->myroles[$table]['type'] == 'all') {
                        $this->myroles[$tableau[0]]['type'] = 'all';
                    }
                } else {
                    $this->myroles[$tableau[0]] = $this->myroles[$table];
                }
            }

            reset($relinv);
        }

        function saveAuth()
        {

        }

        /**
         * Clearing authentification informations
         *
         * @return bool
         */
        function clearAuth()
        {

            $gl = new GenLocks();
            $_SESSION['gs_adminuser'] = $_SESSION['gs_adminpassword'] = $this->adminpassword = $this->adminuser = false;
            $_SESSION = array();
            session_regenerate_id();
            session_destroy();
            setcookie('gs_admin', 0, time() - 3600);
            setcookie('PHPSESSID', 0, time() - 3600);

            if (!array_key_exists('gs_adminpassword', $_SESSION)) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Logging action
         *
         * @param string $action
         * @param string $table
         * @param array $row
         * @param mixed $id
         * @param string $champ
         * @param mixed $valeur
         */
        function logAction($action, $table, $row = array(), $id = 0, $champ = "", $valeur)
        {

            $this->doneActions[] = array($action, $table, $row, $id, $champ, $valeur);
        }

        /**
         * Returns allowed actions for table/$id
         *
         * @param string $table
         * @param mixed $id
         * @param array $tab_default_field
         * @return array Actions
         */
        function getActions($table, $id = 0, $tab_default_field = array())
        {

            global $_Gconfig;

            /**
             * Default defined actions
             */
            $actions = akev($_Gconfig['rowActions'], $table);


            /**
             * Compatibility with old syntax
             */
            if (is_array($actions)) {
                $actions = array_keys($actions);
            } else {
                $actions = array();
            }

            /**
             * Adding default "view" action
             */
            if ($this->can('view', $table, $id)) {
                array_unshift($actions, 'view');
            }

            /**
             * Adding default "edit" action
             */
            if ($this->can('edit', $table, $id)) {
                array_unshift($actions, 'edit');
            }
            $delete = true;

            if ($id) {
                $tab_field = getTabField($table);
                if (!count($tab_default_field)) {
                    $tab_default_field = getRowFromId($table, $id);
                }

                /**
                 * On ajoute l'action VALIDER / Masquer si l'on est dans une rubrique avec VERSION_FIELD
                 */
                if (in_array($table, $_Gconfig['versionedTable'])) {


                    if (!ake(ONLINE_FIELD, $tab_field) || !ake(VERSION_FIELD, $tab_field)) {
                        derror(t('dev_please_create_online_field_and_version_field_in_table_to_make_it_versioned') . ' : ' . ONLINE_FIELD . ' : ' . VERSION_FIELD);
                    } else {
                        $actions[] = 'validateVersion';
                        $actions[] = 'hideVersion';
                        $actions[] = 'askValidation';
                        $actions[] = 'refuseValidation';
                    }
                } /**
                 * On ajoute l'action MASQUER / Mettre en ligne pour les objets avec ONLINE_FIELD
                 */ else if (in_array($table, $_Gconfig['hideableTable'])) {

                    if (!ake(ONLINE_FIELD, $tab_field)) {
                        derror(t('dev_please_create_online_field_in_table_to_make_it_hideable') . ' : ' . ONLINE_FIELD);
                    } else
                        $actions[] = 'hideObject';
                    $actions[] = 'showObject';
                } else if (isMultiVersion($table)) {
                    $oldActions = $actions;
                    array_shift($oldActions);
                    $actions = (array('showMV'));

                    if ($tab_default_field[MULTIVERSION_STATE] === MV_STATE_ARCHIVE) {
                        $actions[] = 'unarchiveMv';
                        //$actions[] = 'del';

                        $actions[] = 'duplicateMV';
                        $actions[] = 'publishMV';
                    } else
                        if ($tab_default_field[MULTIVERSION_STATE] === MV_STATE_DRAFT) {
                            $actions[] = 'edit';
                            //$actions[] = 'del';

                            $actions[] = 'duplicateMV';
                            $actions[] = 'publishMV';
                        } else if ($tab_default_field[MULTIVERSION_STATE] === MV_STATE_ONLINE) {
                            $actions[] = 'editOtherMV';
                            $actions[] = 'unpublishMV';
                            //$actions[] = 'del';
                            //$actions[] = 'deleteMV';
                            $actions[] = 'duplicateMV';
                            $delete = true;
                        } else if ($tab_default_field[MULTIVERSION_STATE] === MV_STATE_OFFLINE) {
                            $actions[] = 'edit';

                            $actions[] = 'publishMV';
                            //$actions[] = 'del';
                            //$actions[] = 'deleteMV';
                            $actions[] = 'duplicateMV';
                            $delete = true;
                        }

                    $actions = array_merge($actions, $oldActions);
                }
            } else {
                if (in_array($table, $_Gconfig['hideableTable'])) {
                    $actions[] = 'hideObject';
                    $actions[] = 'showObject';
                }
            }

            if ($delete && $this->can('del', $table, $id)) {
                $actions[] = 'del';
            }

            return $actions;
        }

        /**
         * Trigger use error
         * Unauthorized action
         *
         */
        function showError()
        {

            $i = array_pop($this->doneActions);

            debug_print_backtrace();
            trigger_error('
        <h4><a href="javascript:history.go(-1);">&laquo; retour</a></h4>
        <h2>Action non autorise</h2>
        <p>
        <span style="font-weight:bolder;color:red;">' . implode('</span> &raquo; <span style="font-weight:bolder;color:red;">', $i) . '</span>
        </p>' . '
         ', E_USER_ERROR); //,'rubrique_url_fr','rubrique_url_en'
        }

        function can($action, $table = "", $row = array(), $id = "0", $champ = "", $valeur = 0)
        {


            $tmpcans = $action . '-' . $table . '-' . $id . '-' . $champ . '-' . $valeur;

            if (@ake($GLOBALS['cans'], $tmpcans)) {
                //	return $GLOBALS['cans'][$tmpcans];
            }

            $return = false;

            if ($action == "edit" && $id != "0") {
                $gl = new GenLocks();
                $lt = $gl->getLock($table, $id);
                if (is_array($lt)) {
                    return false;
                }
            }

            $this->logAction($action, $table, $row, $id, $champ, $valeur);

            if ($this->superAdmin) {
                /* Par defaut le superadmin peut tout faire, donc on ne check plus rien */
                $return = true;
            } else if (akev($this->myroles, $table) == 'all') {
                /* Administrateur de "Table" peut tout faire dedans */
                $return = true;
            } else if (strlen($champ) && strlen($table) && ((is_array($row) && count($row)) || $id != "0") && strlen($action)) {

                $return = $this->canChamp($action, $table, $row, $id, $champ, $valeur);
            } else if (strlen($table) && ((is_array($row) && count($row)) || ($id != "0" && $id != "")) && strlen($action)) {

                $return = $this->canRow($action, $table, $row, $id);
            } else if (strlen($action) && $table) {
                $return = $this->canTable($action, $table, $champ, $valeur);
            } else if (strlen($action)) {

                $return = $this->canGlobalAction($action);
            }

            $GLOBALS['cans'][$tmpcans] = $return;


            return $return;
        }

        function canGlobalAction($action)
        {


            if ($this->superAdmin) {
                /* Par defaut le superadmin peut tout faire, donc on ne check plus rien */
                return true;
            }

            return false;
        }

        function canTable($action, $table, $champ = "", $valeur = "")
        {
            /*
              Retourne true ou false selon les droits sur une table en particulier
             */
            if (@array_key_exists($table, $this->myroles)) {
                if ($action == "add") {
                    if ($this->myroles[$table]['add']) {
                        if ($this->myroles[$table]['condition'] == 'none')
                            return true;
                        /* else if(@in_array('arbo',$this->myroles[$table]['condition']))
                          return $this->checkRelInvField($table,$champ,$valeur);
                         */
                    }
                }
                if (!empty($this->myroles[$table][$action])) {
                    return true;
                } else if (!empty($this->myroles[$table]['actions'][$action])) {
                    return true;
                }
            }
            return false;
        }

        function canRow($action, $table, $row = array(), $id = 0)
        {

            /*
              Verifie dans l'ordre si on peut modifier la table, puis la ligne en question
             */


            if (!is_array($row) || !count($row)) {

                $row = $this->idToRow($table, $id);
            }
            if (!$id) {
                $id = $row[getPrimaryKey($table)];
            }

            if (isMultiVersion($table)) {
                $id = getRealRubId($row);
                $row = $this->idToRow($table, $id);
            }


            if (!empty($this->myroles[$table]) && (ake("all", $this->myroles[$table]) || $this->myroles[$table]['type'] == 'all')) {
                return true;
            } else if (!empty($this->myroles[$table]) && (!empty($this->myroles[$table][$action]) || !empty($this->myroles[$table]['actions'][$action]))) {

                if ($id == "new") {
                    return true;
                }

                if (array_key_exists("condition", $this->myroles[$table])) {

                    if ($this->myroles[$table]['condition'] == 'none') {
                        return true;
                    } else {
                        return $this->checkCondition($this->myroles[$table]['condition'], $table, $row);
                    }
                } else {
                    return true;
                }
            } else {


                return ($this->reverseRecurseArbo($table, $row, $id));
            }

            return false;
        }

        function canChamp($action, $table, $row = array(), $id = 0, $champ, $valeur)
        {


            $myChamps = $this->myroles[$table]['champs'];

            if ($this->canRow($action, $table, $row, $id)) {

                if ($myChamps == 'all') {
                    return true;
                } else if (is_array($myChamps)) {

                    if (in_array($champ, $myChamps)) {

                        return true;
                    } else {
                        return $this->checkRelInvField($table, $champ, $valeur);
                    }
                }
            } else {
                return $this->checkRelInvField($table, $champ, $valeur);
            }

            return false;
        }

        function checkRelInvField($table, $champ, $valeur)
        {
            /* Est ce un champ autoris�par l'arborescence ??? */

            global $relinv;
            reset($relinv);
            $monrelinv = &$relinv;

            if (!strlen($champ)) {
                foreach ($monrelinv as $mTable => $mChamp) {
                    foreach ($mChamp as $mChamp => $mArray) {
                        if ($mArray[0] == $table) {
                            $champ = $mArray[1];
                            $valeur = $_POST['genform_' . $champ];
                        }
                    }
                }
            }

            reset($monrelinv);


            foreach ($monrelinv as $mTable => $mChamp) {
                reset($mChamp);
                foreach ($mChamp as $mChamp => $mArray) {

                    if ($mTable == 's_rubrique' && $mArray[0] == $table && $mArray[1] == $champ) {

                        if ($this->isAllowedRubs($valeur)) {
                            return true;
                        } else {
                            return false;
                        }
                    } else if ($mArray[0] == $table && $mArray[1] == $champ) {

                        $sql = 'SELECT * FROM ' . $mTable . ' WHERE ' . GetPrimaryKey($mTable) . ' = ' . $valeur;

                        $mRow = GetSingle($sql);

                        $ret = $this->reverseRecurseArbo($mTable, $mRow);

                        return $ret;
                    }
                }
            }

            return false;
        }

        function checkCondition($condition, $table, $row)
        {
            /*
              Conditions
              Du genre checkarbo, chekowner, ...

             */
            global $_Gconfig;
            if (@in_array($row[getPrimaryKey($table)], $this->myroles[$table]['rows'])) {
                return true;
            }

            if (in_array('proprio', $condition) || ake('proprio', $condition)) {
                if ($row[$_Gconfig['field_creator']] == $this->adminid || isNull($row[getPrimaryKey($table)])) {
                    return true;
                }
            }

            if (in_array('arbo', $condition) || ake('arbo', $condition)) {

                if ($table == "s_rubrique" && false) {

                    if ($this->isAllowedRubs($row['rubrique_id'])) {
                        return true;
                    }
                } else if ($condition == "none") {
                    return true;
                } else {

                    return $this->reverseRecurseArbo($table, $row);
                }
            }


            return false;
        }

        function isAllowedRubs($id)
        {
            if ($this->allowedRubs[$id]) {
                return true;
            }
            return false;
        }

        function reverseRecurseArbo($table, $row = array(), $id = 0)
        {

            global $relinv;
            reset($relinv);
            $monrelinv = &$relinv;


            foreach ($monrelinv as $mTable => $mChamp) {

                foreach ($mChamp as $mChamp => $mArray) {
                    /*
                      if($mTable == 's_rubrique' && $mArray[0] == $table) {
                      if($this->isAllowedRubs($row[$mArray[1]])) {
                      return true;
                      }
                      else if(@in_array($row[$mArray[1]],$this->myroles[$table]['rows'])) {
                      return true;

                      }
                      }

                     */
                    if ($mArray[0] == $table && $row[$mArray[1]]) {

                        $pk = getPrimaryKey($mTable);

                        $mRow = GetRowFromId($mTable, $row[$mArray[1]]);

                        if (!empty($this->myroles[$mTable]) && (@in_array($mRow[$pk], $this->myroles[$mTable]['rows']) || $this->myroles[$mTable]['type'] == 'all')) {

                            return true;
                        }
                        $ret = $this->reverseRecurseArbo($mTable, $mRow);

                        return $ret;
                    }
                }
            }

            return false;
        }

        /**
         * Alias de getRowFromId
         *
         * @param unknown_type $table
         * @param unknown_type $id
         * @return unknown
         */
        function idToRow($table, $id)
        {

            return getRowFromId($table, $id);
        }

        /**
         * Show login-form
         *
         */
        function showForm()
        {


            global $gb_obj;

            $gb_obj->includeFile('inc.header.php', 'admin_html');
            $row = GetAll('SELECT admin_id FROM s_admin LIMIT 0,1');

            if (!($row)) {
                //$GLOBALS['gb_obj']->includeFile('genInstall.php','');
                $gb_obj->includeFile('install.php', '.');
                $gi = new genInstall();
                $gi->gen();
            } else {
                $gb_obj->includeFile('form.login.php', 'admin_html');
            }
            $gb_obj->includeFile('inc.footer.php', 'admin_html');
        }

        /**
         * Injects SQL in a query to return only allowed rows of a table
         *
         * @param string $table
         * @param string $alias
         * @return string
         */
        function sqlCanRow($table, $alias = '')
        {
            $a = '';
            if ($alias) {
                $a = $alias . '.';
            }

            if ($this->superAdmin)
                return '';

            if (!empty($this->myroles[$table]['view'])) {

                /**
                 * Ajout de Olivier le 24/11/2010 && count($this->myroles[$table]['rows'])
                 */
                if ($this->myroles[$table]['type'] == 'all') {
                    return ' ';
                } else if (is_array($this->myroles[$table]['rows']) && count($this->myroles[$table]['rows'])) {

                    $v = implode($this->myroles[$table]['rows'], '","');

                    if ($v) {
                        $a = ' AND ' . $a . getPrimaryKey($table) . ' IN ("' . $v . '") ';

                        return $a;
                    } else {
                        return ' AND 0 = 1 ';
                    }
                } else {
                    return ' AND 0 = 1 ';
                }
            }
        }

    }

}


