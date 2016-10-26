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


define('FMT_DATEFR', '%d/%m/%Y');
define('FMT_DATEUS', '%m/%d/%Y');
define('FMT_DATEISO', '%Y%m%dT%H%M%S');
define('FMT_DATELDAP', '%Y%m%d%H%M%SZ');
define('FMT_DATEMYSQL', '%Y-%m-%d %H:%M:%S');
define('FMT_DATERFC822', '%a, %d %b %Y %H:%M:%S');
define('FMT_TIME', '%H:%M');
define('WDAY_SUNDAY', 0);
define('WDAY_MONDAY', 1);
define('WDAY_TUESDAY', 2);
define('WDAY_WENESDAY', 3);
define('WDAY_THURSDAY', 4);
define('WDAY_FRIDAY', 5);
define('WDAY_SATURDAY', 6);
define('SEC_MINUTE', 60);
define('SEC_HOUR', 3600);
define('SEC_DAY', 86400);

class Date {
    /* unix timestamp */

    var $ts;
    var $Y;
    var $M;
    var $D;
    var $h;
    var $m;
    var $s;
    /* @scope protected */
    var $change = 0; // 1 if date needs recalculation

    function Date($ts = "") {
        $this->input = $ts;
        if ($ts) {
            $this->setTimestamp($ts);
        } else {
            $this->setTimestamp(time());
        }
    }

    /*     * **
     * 	Build a date from an ISO datetime string
     * @params $datetime string the iso-X datetime with both date and time components
     * @static @factory
     * @return a Date object if ok, NULL otherwise
     *
     * tolerant: accepts format variants with or without separators: "-" for date and ":" for time
     * 	20010801123059 => OK
     * 	20010801T123059Z => OK
     * 	2001-08-01T12:30:59 => OK
     * 	20010801T12:30:59Z => OK
     * 2001-08-01 => error
     * 2001-08-01T01:30 => error
     * 2001-08-01T1:30:59 => error
     * 	timezone code is yet ignored ( not handled )
     */

    function fromDatetime($datetime) {
        if (!preg_match("/^(\d{4})-?(\d{2})-?(\d{2})T?(\d{2}):?(\d{2}):?(\d{2})(.?)$/", $datetime, $a)) {
            return NULL;
        }
        $obj = new Date();
        $obj->setDate($a[1], $a[2], $a[3]);
        $obj->setTime($a[4], $a[5], $a[6]);
        return $obj;
    }

    function toString($format) {
        return strftimeloc($format, $this->getTimestamp());
    }

    function __tostring() {

        if ($this->ts <= 0) {
            return false;
        }
        return $this->input;
    }

    /*
     * can use as static form eg: Date::format( "%Y", $ts )
     * @static
     */

    function format($format, $timestamp) {
        return strftimeloc($format, $timestamp);
    }

    /*     * ************************************************** GETTERS *** */

    function getYear() {
        if ($this->change)
            $this->_calc();
        return $this->Y;
    }

    function getMonth() {
        if ($this->change)
            $this->_calc();
        return $this->M;
    }

    function getDay() {
        if ($this->change)
            $this->_calc();
        return $this->D;
    }

    function getWeekday() {
        if ($this->change)
            $this->_calc();
        return $this->weekday;
    }

    function getYearDay() {
        if ($this->change)
            $this->_calc();
        return date("z", $this->ts);
    }

    function getHours() {
        if ($this->change)
            $this->_calc();
        return $this->h;
    }

    function getMinutes() {
        if ($this->change)
            $this->_calc();
        return $this->m;
    }

    function getSeconds() {
        if ($this->change)
            $this->_calc();
        return $this->s;
    }

    function getSecondsInDay() {
        if ($this->change)
            $this->_calc();
        $ts1 = mktime(0, 0, 0, $this->M, $this->D, $this->Y);
        return $this->ts - $ts1;
    }

    // return Unix timestamp (seconds since epoch )
    function getTimestamp() {
        if ($this->change)
            $this->_calc();
        return $this->ts;
    }

    function daysInMonth() {
        if ($this->change)
            $this->_calc();
        return date("t", $this->ts);
    }

    function daysInYear() {
        if ($this->change)
            $this->_calc();
        return date("t", $this->ts);
    }

    function DaysTo($date) {
        if (!is_object($date) || get_class($date) != "date")
            return false;
        $deltats = $date->getTimestamp() - $this->getTimestamp();
        if ($deltats > 0)
            return (int) floor($deltats / SEC_DAY);
        else
            return (int) ceil($deltats / SEC_DAY);
    }

    function compareTo($date) {
        if (!is_object($date) || get_class($date) != "date")
            return false;
        return $this->getTimestamp() - $date->getTimestamp();
    }

    function addDays($numdays) {
        $this->D += $numdays;
        $this->_calc();
    }

    function addMonths($num) {
        $this->M += $num;
        $this->_calc();
    }

    function addYears($num) {
        $this->Y += $num;
        $this->_calc();
    }

    function addHours($num) {
        $this->h += $num;
        $this->_calc();
    }

    function addMinutes($num) {
        $this->m += $num;
        $this->_calc();
    }

    function addSeconds($num) {
        $this->s += $num;
        $this->_calc();
    }

    /*     * ************************************************** SETTERS *** */

    function setTimestamp($ts) {
        // TODO : basic validation
        if (!is_int($ts)) {
            $this->ts = strtotime($ts);
        } else {
            $this->ts = $ts;
        }
        $a = getdate($this->ts);
        $this->Y = $a['year'];
        $this->M = $a['mon'];
        $this->D = $a['mday'];
        $this->h = $a['hours'];
        $this->m = $a['minutes'];
        $this->s = $a['seconds'];
        $this->weekday = $a['wday'];
        $this->change = 0;
        unset($a);
    }

    function setDate($Y, $M, $D = 1) {
        $this->Y = $Y;
        $this->M = $M;
        $this->D = $D;
        $this->change = 1;
    }

    function setTime($h, $m, $s = 0) {
        $this->h = $h;
        $this->m = $m;
        $this->s = $s;
        $this->change = 1;
    }

    function setHours($val) {
        $this->h = $val;
        $this->change = 1;
    }

    function setMinutes($val) {
        $this->m = $val;
        $this->change = 1;
    }

    function setSeconds($val) {
        $this->s = $val;
        $this->change = 1;
    }

    function setYear($val) {
        $this->Y = $val;
        $this->change = 1;
    }

    function setMonth($val) {
        $this->M = $val;
        $this->change = 1;
    }

    function setDay($val) {
        $this->D = $val;
        $this->change = 1;
    }

    // setWeekday( [0-6] ) 
    function setWeekday($weekday) {
        $this->D += ($weekday - $this->weekday);
        $this->change = 1;
    }

    function isValid() {
        if (!checkdate($this->M, $this->D, $this->Y))
            return false;
        if ($this->Y < 1970 || $this->Y > 2038)
            return false;
        if ($this->h < 0 || $this->h > 23 || $this->m < 0 || $this->m > 59 || $this->s < 0 || $this->s > 59)
            return false;
        return true;
    }

    /**
     * 	@protected 
     */
    function _calc() {
        $this->ts = mktime($this->h, $this->m, $this->s, $this->M, $this->D, $this->Y);
        $a = getdate($this->ts);
        $this->Y = $a['year'];
        $this->M = $a['mon'];
        $this->D = $a['mday'];
        $this->h = $a['hours'];
        $this->m = $a['minutes'];
        $this->s = $a['seconds'];
        $this->weekday = $a['wday'];
        $this->change = 0;
    }

}

?>
