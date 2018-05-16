<?php
/**
 * Created by PhpStorm.
 * User: blixit
 * Date: 10/06/17
 * Time: 18:15
 */

namespace GWA\Tests;

//required from the app dir by phpunit
require_once 'src/lib/Project.php';
require_once 'src/lib/ProjectManager.php';

use GWA\Project;
use GWA\ProjectManager;
use Tests\Functional\BaseTestCase;

class ProjectManagerTest extends BaseTestCase
{
    public function testProjectManager()
    {
        $array = $this->getMethodes(ProjectManager::class);
        $this->assertTrue(in_array('create', $array));
        $this->assertTrue(in_array('getProjectFileContent', $array));
    }

    public function testProjectManagerFs(){
        $project = $this->getMockBuilder(Project::class)->disableOriginalConstructor()
            ->setMethods(['setId'])
            ->getMock();

        $manager = $this->getMockBuilder(ProjectManager::class)->disableOriginalConstructor()
            ->setMethods(['create', 'getProjectFileContent'])
            ->getMock();

        $manager->expects($this->atLeastOnce())->method('create')->with($project)
            ->willThrowException(new \Exception("Project name can not be empty"));
        $manager->expects($this->atLeastOnce())->method('getProjectFileContent')->with($project)
            ->willReturn(null);

        try{
            $manager->create($project);
        }catch (\Exception $e){
            $this->assertContains('empty', $e->getMessage());
            $project->setName("my_project");

            $project->expects($this->atLeastOnce())->method('setId');
            $manager->create($project);
            var_dump($project);
            die('erer');
        }

    }
}
