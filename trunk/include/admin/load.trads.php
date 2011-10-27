<?php

global $admin_trads, $_Gconfig;

$v = LG;

$sql = 'SELECT trad_id,trad_'.$v.' FROM s_trad';
$res = GetAll($sql);

if ($res) {
    foreach ($res as $row) {
        $admin_trads[$row['trad_id']][$v] = $row['trad_' . $v];
    }
}

$sql = 'SELECT admin_trad_id,admin_trad_'.$v.' FROM s_admin_trad';
$res = GetAll($sql);

if ($res) {
    foreach ($res as $row) {
        $row['admin_trad_' . $v] = str_replace(array('[ADMIN_PICTOS_FOLDER]',
            '[ADMIN_PICTOS_ARBO_SIZE]',
            '[ADMIN_PICTOS_FORM_SIZE]')
                , array(ADMIN_PICTOS_FOLDER,
            ADMIN_PICTOS_ARBO_SIZE,
            ADMIN_PICTOS_FORM_SIZE), $row['admin_trad_' . $v]);
        $admin_trads[$row['admin_trad_id']][$v] = $row['admin_trad_' . $v];
    }
}

