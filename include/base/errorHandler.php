<?php

global $_Gconfig;

if (!empty($_Gconfig['useInternalErrorHandler'])) {


    global $cfgError;
    $cfgError = array();
    $cfgError['debug'] = "1";
    $cfgError['adminEmail'] = '';
    set_error_handler('errorHandler');

    function errorHandler($errno, $errstr='', $errfile='', $errline='') {
        // if error has been supressed with an @
        if (error_reporting() == 0) {
            return;
        }

        global $cfgError;


        // check if function has been called by an exception
        if (func_num_args() == 5) {
            // called by trigger_error()
            $exception = null;
            list($errno, $errstr, $errfile, $errline) = func_get_args();

            $backtrace = array_reverse(debug_backtrace());
        } else {
            // caught exception
            $exc = func_get_arg(0);
            $errno = $exc->getCode();
            $errstr = $exc->getMessage();
            $errfile = $exc->getFile();
            $errline = $exc->getLine();

            $backtrace = $exc->getTrace();
        }

        $errorType = array(
            E_ERROR => 'ERROR',
            E_WARNING => 'WARNING',
            E_PARSE => 'PARSING ERROR',
            E_NOTICE => 'NOTICE',
            E_CORE_ERROR => 'CORE ERROR',
            E_CORE_WARNING => 'CORE WARNING',
            E_COMPILE_ERROR => 'COMPILE ERROR',
            E_COMPILE_WARNING => 'COMPILE WARNING',
            E_USER_ERROR => 'USER ERROR',
            E_USER_WARNING => 'USER WARNING',
            E_USER_NOTICE => 'USER NOTICE',
            E_STRICT => 'STRICT NOTICE',
            E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR'
        );

        // create error message
        if (array_key_exists($errno, $errorType)) {
            $err = $errorType[$errno];
        } else {
            $err = 'CAUGHT EXCEPTION';
        }

        $errMsg = "$err: $errstr in $errfile on line $errline";

        //if($errno != E_NOTICE)
        debug($errMsg);
        return;

        // start backtrace
        foreach ($backtrace as $v) {

            if (isset($v['class'])) {

                $trace = 'in class ' . $v['class'] . '::' . $v['function'] . '(';

                if (isset($v['args'])) {
                    $separator = '';

                    foreach ($v['args'] as $arg) {
                        $trace .= "$separator" . getArgument($arg);
                        $separator = ', ';
                    }
                }
                $trace .= ')';
            } elseif (isset($v['function']) && empty($trace)) {
                $trace = 'in function ' . $v['function'] . '(';
                if (!empty($v['args'])) {

                    $separator = '';

                    foreach ($v['args'] as $arg) {
                        $trace .= "$separator" . getArgument($arg);
                        $separator = ', ';
                    }
                }
                $trace .= ')';
            }
        }

        // display error msg, if debug is enabled


        if ($cfgError['debug'] == "1") {
            echo '<h2>Debug Msg</h2>' . nl2br($errMsg) . '<br />
            Trace: ' . nl2br($trace) . '<br />';
            return;
        }

        // what to do
        switch ($errno) {
            default:
                if ($cfgError['debug'] == 0) {
                    // send email to admin
                    if (!empty($cfgError['adminEmail'])) {
                        @mail($cfgError['adminEmail'], 'critical error on ' . $_SERVER['HTTP_HOST'], $errorText, 'From: Error Handler');
                    } else {
                        
                    }
                    // end and display error msg
                    exit(displayClientMessage());
                }
                else
                    exit('<p>aborting.</p>');
                break;
        }
    }

// end of errorHandler()

    function displayClientMessage() {
        echo 'some html page with error message';
    }

    function getArgument($arg) {
        switch (strtolower(gettype($arg))) {

            case 'string':
                return( '"' . str_replace(array("\n"), array(''), $arg) . '"' );

            case 'boolean':
                return (bool) $arg;

            case 'object':
                return 'object(' . get_class($arg) . ')';

            case 'array':
                $ret = 'array(';
                $separtor = '';

                foreach ($arg as $k => $v) {
                    $ret .= $separtor . getArgument($k) . ' => ' . getArgument($v);
                    $separtor = ', ';
                }
                $ret .= ')';

                return $ret;

            case 'resource':
                return 'resource(' . get_resource_type($arg) . ')';

            default:
                return var_export($arg, true);
        }
    }

}