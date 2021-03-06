<?php

class Processmanager {

    public $executable = "php"; //the system command to call
    public $root = "";  //the root path
    public $processes = 10;  //max concurrent processes
    public $sleep_time = 1;  //time between processes
    public $show_output = false; //where to show the output or not
    private $running = array(); //the list of scripts currently running
    private $scripts = array(); //the list of scripts - populated by addScript
    private $processesRunning = 0;  //count of processes running	

    function addScript($script, $max_execution_time = 300) {
        $this->scripts[] = array("script_name" => $script, "max_execution_time" => $max_execution_time);
    }

    function exec() {

        if ($this->show_output) {
            echo "" . count($this->scripts) . ' to launch ';
        }
        $i = 0;
        for (;;) {
            // Fill up the slots
            while (($this->processesRunning < $this->processes) and ( $i < count($this->scripts))) {
                if ($this->show_output) {
                    //ob_start();
                    echo "Adding script: " . $this->scripts[$i]["script_name"] . " (" . $i . "/" . count($this->scripts) . ") \n";
                    //ob_flush();
                    //flush();
                }
                $this->running[] = new Process($this->executable, $this->root, $this->scripts[$i]["script_name"], $this->scripts[$i]["max_execution_time"]);
                $this->processesRunning++;
                $i++;
            }

            // Check if done
            if (($this->processesRunning == 0) and ( $i >= count($this->scripts))) {
                break;
            }

            // sleep, this duration depends on your script execution time, the longer execution time, the longer sleep time
            sleep($this->sleep_time);

            // check what is done
            foreach ($this->running as $key => $val) {

                if (!$val->isRunning() or $val->isOverExecuted()) {
                    if ($this->show_output) {

                        if (!$val->isRunning()) {
                            echo "Done: " . $val->script . "\n";
                        } else {
                            echo "Killed: " . $val->script . "\n";
                        }
                    }
                    proc_close($val->resource);
                    unset($this->running[$key]);
                    $this->processesRunning--;
                }
            }
        }
    }

}

class Process {

    public $resource;
    public $pipes;
    public $script;
    public $max_execution_time;
    public $start_time;

    function __construct(&$executable, &$root, $script, $max_execution_time) {
        $this->script = $script;
        $this->max_execution_time = $max_execution_time;
        $descriptorspec = array(
            0 => array('pipe', 'r'),
            1 => array('pipe', 'w'),
            2 => array('pipe', 'w')
        );
        $this->resource = proc_open($executable . " " . $root . $this->script, $descriptorspec, $this->pipes, null, $_ENV);
        $this->start_time = time();
    }

    // is still running?
    function isRunning() {
        $status = proc_get_status($this->resource);
        return $status["running"];
    }

    // long execution time, proccess is going to be killer
    function isOverExecuted() {
        if ($this->start_time + $this->max_execution_time < time())
            return true;
        else
            return false;
    }

}
