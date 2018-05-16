<?php
/**
 * Created by PhpStorm.
 * User: blixit
 * Date: 10/06/17
 * Time: 15:08
 */

namespace GWA;


use DateTime;
use phpDocumentor\Reflection\Types\Null_;

class Project
{

    private $id;
    private $name = '';
    private $creation ;
    private $description;
    private $version = ['M'=>0, 'm'=>0, 'r'=>0];
    private $targets = [];
    private $properties = [];

    public function __construct($name, $creation, $version, $targets, $properties,$description)
    {
        $this->name = $name;
        $this->creation = $creation;
        $this->description = $description;
        $this->setVersion($version);
        $this->targets = $targets;
        $this->properties = $properties;
    }

    public function getFriend(){
        return [ProjectManager::class];
    }

    public function setId($id, $manager){

        if(! in_array(get_class($manager),$this->getFriend()))
            return false;
        $this->id = $id;
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCreation()
    {
        return $this->creation;
    }

    /**
     * @param mixed $creation
     */
    public function setCreation($creation)
    {
        $this->creation = $creation;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return array
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param array $version
     */
    public function setVersion($version)
    {
        $this->version =[
            'M' => intval($version['M']),
            'm' => intval($version['m']),
            'r' => intval($version['r'])
        ] ;
    }

    /**
     * @return array
     */
    public function getTargets()
    {
        return $this->targets;
    }

    /**
     * @param array $targets
     */
    public function setTargets($targets)
    {
        foreach ($targets as $item){
            if(empty($item))
                continue;
            $this->targets[] = $item;
        }
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param array $properties
     */
    public function setProperties($properties)
    {
        foreach ($properties as $k => $item){
            if(empty($k))
                continue;
            $this->properties[$k] = $item;
        }
    }

    public static function getInstance(){
        return new Project(
            '',
            (new DateTime())->getTimestamp(),
            [
                'M'=>0, 'm'=>0,'r'=>0
            ],
            [],
            [],
            ""
        );
    }

    public function fromArray(array $body){
        $this->setName(isset($body['name']) ? $body['name'] : '');
        $this->setVersion(isset($body['version']) ? $body['version'] : []);
        $this->setCreation(isset($body['creation']) ? $body['creation'] : (new DateTime())->getTimestamp());
        $this->setDescription(isset($body['description']) ? $body['description'] : '');
        $this->setProperties(isset($body['properties']) ? $body['properties'] : []);
        $this->setTargets(isset($body['targets']) ? $body['targets'] : []);
    }

    public function toArray(){
        $array = (array)($this);
        $className = (new \ReflectionClass(Project::class))->getName();

        foreach ($array as $k => $v){
            $newkey = str_replace("\0".$className."\0","",$k);
            $array[$newkey] = $v;
            unset($array[$k]);
        }
        return $array;
    }



}