<?php

class ocms_jstradFront extends ocmsPlugin {

    public function afterInit() {

        global $_Gconfig, $co;
        $sql = 'SELECT '
                . 'trad_id,' . sqlLgValue('trad') . ' '
                . 'FROM s_trad '
                . 'WHERE '
                . 'trad_id IN ("' . implode('","', $_Gconfig['jstrad']['ids']) . '")';

        $this->trads = $co->getAssoc($sql);

        $this->site->g_headers->addHtmlHeaders('<script>window.Trads = ' . json_encode($this->trads) . ';function t(s){return window.Trads[s]?window.Trads[s]:s;}</script>');
    }

}
