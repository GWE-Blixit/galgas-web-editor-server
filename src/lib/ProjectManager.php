<?php
/**
 * Created by PhpStorm.
 * User: blixit
 * Date: 10/06/17
 * Time: 18:13
 */

namespace GWA;

//required from the app dir by phpunit
use GWA\FileManagement\FileManagerInterface;
use GWA\FileManagement\ProjectFsManager;

require_once '../src/lib/Project.php';
require_once '../src/lib/FileManagement/ProjectFsManager.php';
require_once '../src/lib/FileManagement/ProjectDbManager.php';
require_once '../src/lib/FileSystem.php';

class ProjectManager
{
    const ROOT = __DIR__.'/../..';
    const DATA = self::ROOT.'/data';

    /**
     * @var FileManagerInterface
     */
    private $manager;

    /**
     * @var Controller
     */
    private $controller;

    function __construct($managerName, Controller $controller)
    {
        $this->manager = (new \ReflectionClass($managerName))->newInstance();
        $this->controller = $controller;
    }

    public function getFileManager(): FileManagerInterface {
        return $this->manager;
    }

    public function findAll(array $options = []){
        $directories = $this->manager->scandir(self::DATA);
        $projects = [];

        foreach ($directories as $directory) {
            $info = self::DATA.'/'.$directory.'/info.json';
            if(! is_file($info))
                continue;
            $content = $this->manager->read($info);
            $projects[] = json_decode($content,true);
        }

        return $projects;
    }

    /**
     * A METTRE DANS UN TRAIT
     * @param array $options
     * @return string
     */
    public function findJqueryTree(array $options = ['directory' => '']){
        $localdirectory = $options['directory'];
        $directory = self::DATA.'/'.$localdirectory;
        $files = $this->manager->scandir($directory);

        natcasesort($files);
        //$content = '';
        // $content .= "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
            //     // All dirs
            //     foreach( $files as $file ) {
            //         if( file_exists($directory.'/'.$file) && is_dir($directory.'/'.$file) ) {
            //             $content .= "<li class=\"directory collapsed \"><a href=\"#\" rel=\"" . htmlentities($localdirectory.'/'.$file) . "/\">" . htmlentities($file) . "</a></li>";
            //         }
            //     }
            //     // All files
            //     foreach( $files as $file ) {
            //         if( file_exists($directory.'/'.$file) && !is_dir($directory.'/'.$file) ) {
            //             $ext = preg_replace('/^.*\./', '', $file);
            //             $content .= "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"" . htmlentities($localdirectory.'/'.$file) . "\">" . htmlentities($file) . "</a></li>";
            //         }
            //     }
            // $content .= "</ul>";
        
        $content = [];
        // All dirs
        foreach( $files as $file ) {
            if( file_exists($directory.'/'.$file) && is_dir($directory.'/'.$file) ) {
                $content[] = [
                    'path' => htmlentities($localdirectory.'/'.$file),
                    'name' => htmlentities($file),
                    'dir'  => true,
                ];
            }
        }
        // All files
        foreach( $files as $file ) {
            if( file_exists($directory.'/'.$file) && !is_dir($directory.'/'.$file) ) {
                $ext = preg_replace('/^.*\./', '', $file);
                $content[] = [
                    'path' => htmlentities($localdirectory.'/'.$file),
                    'name' => htmlentities($file),
                    'dir'  => false,
                ];
            }
        }
        
        return json_encode($content);
    }

    public function findOne(array $options = ['id'=>null]){
        $directories = $this->manager->scandir(self::DATA);
        $project = [];

        foreach ($directories as $directory) {
            if($directory != $options['id'])
                continue;

            $info = self::DATA.'/'.$directory.'/info.json';
            if(! is_file($info))
                continue;
            $content = $this->manager->read($info);
            $project = json_decode($content,true);
        }

        return $project;
    }

    public function getFile(array $options = ['path'=>null]){

        $file = self::DATA.'/'.$options['path'];
        if(! is_file($file))
            return null;

        return $this->manager->read($file);
    }

    public function create(Project $project){
        if(empty($project->getName()) ){
            throw new \Exception("Project name can not be empty");
        }
        $project->setId(time().'_'.$project->getName(),$this);

        $directory = self::ROOT.'/data/'.$project->getId();

        $this->manager->rmdir($directory);
        $this->manager->mkdir($directory);

        //check directory
        if(! $this->manager->is_dir($directory))
            throw new \Exception("Failed to write the project directory");

        $filename = $directory.'/'.$project->getName().'.galgasProject';

        $content = $this->getProjectFileContent($project);

        $this->manager->write($filename,$content);

        if($this->manager instanceof ProjectFsManager){
            $filename = $directory.'/info.json';
            $content = json_encode($project->toArray());
            $this->manager->write($filename,$content);
        }
    }

    public function remove(Project $project){
        $directory = self::ROOT.'/data/'.$project->getId();

        $this->manager->rmdir($directory);

        return true;
    }

    private function getProjectFileContent(Project $project){
        $version = $project->getVersion();
        $version = $version['M'].':'.$version['m'].':'.$version['r'];
        $content = "project (".$version.") -> \"".$project->getName()."\" {\n";

        $content .= "#-- Properties\n";
        if(! empty($project->getProperties()))
        foreach ($project->getProperties() as $k => $v){
            $content .= "  %".$k.(! empty($v) ? ": \"$v\"" : "")."\n";
        }

        $content .= "#-- Targets\n";
        if(! empty($project->getTargets()))
        foreach ($project->getTargets() as $target){
            $content .= "  %$target\n";
        }
        $content .= "}\n";

        return $content;
    }
}