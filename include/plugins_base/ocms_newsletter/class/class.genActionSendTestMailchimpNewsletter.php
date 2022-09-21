<?php

class genActionSendTestMailchimpNewsletter extends baseAction {

    function checkCondition() {
        return true;
    }

    function doIt() {
        mailChimp::sendTestNewsletter($this->id);
    }

}