<?php 

class o_paragraphsFront extends ocmsPlugin {
    
    public function init() {


        /**
         * CSS des paragraphes
         * */
        $this->site->g_headers->addCss('include/plugins/o_paragraphs/public/css/o_paragraphs.css','global');
        $this->site->g_headers->addCss('include/plugins/o_paragraphs/public/vendor/assets/owl.carousel.min.css','global');
        $this->site->g_headers->addCss('include/plugins/o_paragraphs/public/vendor/assets/owl.theme.default.min.css','global');
        
        $this->site->g_headers->addScript('include/plugins/o_paragraphs/public/vendor/owl.carousel.js',false,'footer_global');
        $this->site->g_headers->addScript('include/plugins/o_paragraphs/public/js/o_paragraphs.js',false,'footer_global');
        
    }
    
}