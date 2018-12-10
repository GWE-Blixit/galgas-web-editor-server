<?php
/**
 * Created by PhpStorm.
 * User: blixit
 * Date: 11/06/17
 * Time: 01:06
 */

namespace GWA\FileManagement;

require_once '../src/lib/FileManagement/FileManagerInterface.php';
// require_once '../src/lib/ProjectManager.php';

use GWA\ProjectManager;


class ProjectFsManager
    implements FileManagerInterface
{

    function write($resource, $content)
    {
        if(! is_file($resource)){
            $handle = fopen($resource, "w+");
            fclose($handle);
        }
        return file_put_contents($resource, $content,LOCK_EX);
    }

    function read($resource)
    {
        return file_get_contents($resource);
    }

    function update($resource, $content)
    {
        // TODO: Implement update() method.
    }

    function remove($resource)
    {
        if(is_file($resource)){
            unlink($resource);
        }
    }

    function mv($from, $to)
    {
        // TODO: Implement mv() method.
    }

    function is_dir($resource)
    {
        return is_dir($resource);
    }

    function is_file(string $resource)
    {
        return file_exists($resource);
    }

    function mkdir($resource, $recursive = true)
    {
        mkdir($resource, 0777, true);

    }

    function rmdir($resource)
    {
        if(is_dir($resource))
            self::delTree($resource);
    }

    function mvdir($from, $to)
    {
        // TODO: Implement mvdir() method.
    }

    public function scandir($resource){
        return is_dir($resource) ? array_diff(scandir($resource), array('.','..')) : [];
    }

    public static function delTree($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }



}