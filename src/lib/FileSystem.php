<?php

namespace GWA;

/**
 * Created by PhpStorm.
 * User: blixit
 * Date: 05/06/17
 * Time: 22:48
 */
class FileSystem
{
    const BIN_GALGAS = __DIR__.'../../bin/galgas';
    const GALGAS_TEST_COMMAND = "cd /home2/Code/galgas-custom/assistant/../ && /home2/Code/galgas-custom/assistant/../makefile-unix/galgas assistant/fixtures/web.assistant --ast-location=./assistant/fixtures/web.assistant --emit-issue-json-file=/home2/Code/galgas-custom/assistant/log.json --max-errors=5 --ast-successlog=kl";
    //-ast-location=./${TESTFILE} --emit-issue-json-file=${LOGFILE} --max-errors=5 --ast-successlog=kl

    private function call($callback, array $args = []){
        if(is_null($callback))
            return false;

        if(! is_callable($callback))
            throw new BadCallableException();

        call_user_func_array($callback, $args);
        return true;
    }

    public function exec($cmd, callable $callback = null){
        exec ( $cmd , $lines, $return_var );
        $this->call($callback,[$lines, $return_var]);
    }

    public function read($file, callable $callback = null){
        $content = file_get_contents($file);
        return ($this->call($callback,[$content])) ? '' : $content;
    }

    public function write($file, $data, callable $callback = null){
        $bytes = file_put_contents($file,$data);
        return ($this->call($callback,[$bytes])) ? '' : $bytes;
    }

    public function galgas($args, callable $callback = null){
        exec ( "galgas ".$args , $lines, $return_var );
        $this->call($callback,[$lines, $return_var]);

    }
}