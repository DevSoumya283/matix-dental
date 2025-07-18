<?php

/**
* Debugging class - writes to log in live environment
* logging turn offable in config to stop large log files on live server
*
* Author: Len Lyle
*/
Class Debugger
{
    private static $newline = "\n";

    private static $logPath = "/var/www/log/";

    private static $logfile = "debuglog";

    private static $dirSplitter = "/";

    public static function setPath($path)
    {
        self::$logPath = $path;
    }

    public static function debug($data = null, $name = '', $showBacktrace = false, $logfile = null)
    {
        // do not log live site
        if(empty(config_item('debugging'))) {
            return;
        }

        if(!is_dir(self::$logPath)){
            //echo self::$logPath;
            mkdir(self::$logPath);
        }

        if(empty($logfile)){
            $logfile = self::$logfile;
        }

        // backtrace to source file.
        $backtraceData = debug_backtrace();
        $file = $backtraceData[0]['file'];
        $line = $backtraceData[0]['line'];
        if(!isset(self::$logfile)){
            $filebits = explode(self::$dirSplitter, $file);
            self::$logfile = array_pop($filebits);
        }

        // parse the backtrace
        $backtrace = self::parseBacktrace($backtraceData);

        if (is_array($data) || is_object($data)) {
            $logdata = self::obsafePrintR($data, TRUE);
        } else {
            $logdata = $data;
        }

        // create time stamp;
        $dt = new \DateTime;
        $time = $dt->format('D M j G:i:s');
        //print_r($time);
        // [Tue Jun 20 07:35:01.901106 2017]
        $logmessage = '' .
                      self::$newline .
                      '[' . $time . '] ' .
                      (($name) ? $name : 'Debug') . " - File:" . $file . " - Line:" . $line .
                      self::$newline .
                      $logdata .
                      (($showBacktrace) ?
                          self::$newline .
                          self::$newline .
                          "-------------------------------" .
                          self::$newline .
                          "Backtrace: " . $backtrace .
                          self::$newline .
                          "-------------------------------" .
                          self::$newline
                      : "") .
                      self::$newline .
                      self::$newline;

        self::Writelog(self::$logPath . '/' . $logfile, $logmessage);

    }

    public static function getItems()
    {
        return self::$debugItems;
    }

    private static function obsafePrintR($var, $return = false, $html = false, $level = 0)
    {
        $spaces = "";
        $space = $html ? "&nbsp;" : " ";
        for ($i = 1; $i <= 6; $i++) {
            $spaces .= $space;
        }
        $tabs = $spaces;
        for ($i = 1; $i <= $level; $i++) {
            $tabs .= $spaces;
        }
        if (is_array($var)) {
            $title = "Array";
        } elseif (is_object($var)) {
            $title = get_class($var)." Object";
        }
        $output = $title . self::$newline . self::$newline;
        foreach($var as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $level++;
                $value = self::obsafePrintR($value, true, $html, $level);
                $level--;
            }
            $output .= $tabs . "[" . $key . "] => " . $value . self::$newline;
        }
        if ($return) {
            return $output;
        }else{
            echo $output;
        }
    }

    private static function parseBacktrace($raw)
    {
        unset($raw[0]); // take debug class out of the back trace
        $output = "\n\n";

        foreach($raw as $entry){
            $output .= "    File: " . (!empty($entry['file']) ? $entry['file'] : "") ." (Line: " .
                                  (!empty($entry['line']) ? $entry['line'] : "") . ")\n" .
                       "    Function: " . (!empty($entry['function']) ? $entry['function'] : "") . "()\n";
        }

        return trim($output);
    }

    public static function writelog($file, $message)
    {
        error_log($message, 3, $file . ".log");
    }
}
