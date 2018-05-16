<?php
/**
 * Created by PhpStorm.
 * User: blixit
 * Date: 11/06/17
 * Time: 01:07
 */

namespace GWA\FileManagement;

require_once 'src/lib/FileManagement/FileManagerInterface.php';
require_once 'src/lib/ProjectManager.php';

use GWA\ProjectManager;

class ProjectDbManager
    implements FileManagerInterface
{

    function write($resource, $content)
    {
        // TODO: Implement write() method.
    }

    function read($resource)
    {
        // TODO: Implement read() method.
    }

    function update($resource, $content)
    {
        // TODO: Implement update() method.
    }

    function remove($resource)
    {
        // TODO: Implement remove() method.
    }

    function mv($from, $to)
    {
        // TODO: Implement mv() method.
    }

    function is_dir($resource)
    {
        // TODO: Implement is_dir() method.
    }

    function mkdir($resource, $recursive = true)
    {
        // TODO: Implement mkdir() method.
    }

    function rmdir($resource)
    {
        // TODO: Implement rmdir() method.
    }

    function mvdir($from, $to)
    {
        // TODO: Implement mvdir() method.
    }

    function scandir($resource)
    {
        // TODO: Implement scandir() method.
    }
}