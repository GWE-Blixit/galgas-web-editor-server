<?php
/**
 * Created by PhpStorm.
 * User: blixit
 * Date: 10/06/17
 * Time: 14:57
 */

namespace GWA\Tests;

//required from the app dir by phpunit
require_once 'src/lib/Project.php';

use DateTime;
use GWA\Project;
use Tests\Functional\BaseTestCase;

class ProjectTest extends BaseTestCase
{
    public function testProject(){
        $this->assertClassHasAttribute('name', Project::class);

        $timestamp = (new DateTime())->getTimestamp();
        $project = new Project('name',$timestamp,[
            'M'=>0, 'm'=>0, 'r'=>0
        ],[],[],"My description" );

        $this->assertSame(null, $project->getId());
        $this->assertSame('name', $project->getName());
        $this->assertContains(date('Y',$timestamp), date('Y',$project->getCreation()));
        $this->assertArrayHasKey('M',$project->getVersion());
        $this->assertArrayHasKey('m',$project->getVersion());
        $this->assertArrayHasKey('r',$project->getVersion());

    }

    public  function testGetInstance(){
        $project = Project::getInstance();

        $this->assertInstanceOf(Project::class,$project);
    }

    public function testMapping(){
        $this->assertClassHasAttribute('name', Project::class);

        $project = Project::getInstance();
        $timestamp = 0;

        $project->fromArray([
            'name'  =>  'jsonlikeql',
            'creation'  =>  $timestamp,
            'description'  =>  'description',
            'version'  =>  ['M'=>5, 'm'=>3, 'r'=>1],
            'targets'  =>  ['my', 'targets'],
            'properties'  =>  ['my'=> 5],
        ]);

        $this->assertSame('jsonlikeql', $project->getName());
        $this->assertContains(date('Y',$timestamp), date('Y',$project->getCreation()));
        $this->assertSame(5, $project->getVersion()['M']);
        $this->assertSame(3, $project->getVersion()['m']);
        $this->assertSame(1, $project->getVersion()['r']);
        $this->assertSame('description', $project->getDescription());
        $this->assertArrayHasKey('my',$project->getProperties());
        $this->assertTrue(in_array('my',$project->getTargets()));

    }

    public function testUnMapping(){

        $timestamp = (new DateTime())->getTimestamp();
        $project = new Project('name',$timestamp,[
            'M'=>0, 'm'=>0, 'r'=>0
        ],[],[],"My description" );

        $array = $project->toArray();

        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('version', $array);
        $this->assertArrayHasKey('description', $array);
        $this->assertArrayHasKey('creation', $array);
        $this->assertArrayHasKey('targets', $array);
        $this->assertArrayHasKey('properties', $array);
        $count = count( (new \ReflectionClass(Project::class))->getProperties() );
        $this->assertTrue($count == 7);
    }
}