<?php
// simple timer class
class Timer {
    private $time = null;
    public function __construct($name = null) {
        $this->time = time();
        $this->name = (!empty($name)) ? $name : 'Time';
    }

    public function __destruct() {
        Debugger::debug((time()-$this->time) . ' seconds.', 'Total time', false, $this->name);
    }
}