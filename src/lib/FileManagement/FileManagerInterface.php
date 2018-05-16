<?php
/**
 * Created by PhpStorm.
 * User: blixit
 * Date: 11/06/17
 * Time: 00:41
 */

namespace GWA\FileManagement;

interface FileManagerInterface
{
    //Files
    function write($resource, $content);
    function read($resource);
    function update($resource, $content);
    function remove($resource);
    function mv($from, $to);

    //Directory
    function is_dir($resource);
    function mkdir($resource, $recursive = true);
    function rmdir($resource);
    function mvdir($from, $to);
    function scandir($resource);

}