<?php
/**
 * Created by PhpStorm.
 * User: blixit
 * Date: 05/06/17
 * Time: 22:25
 */

namespace GWA;
require_once 'src/lib/ProjectManager.php';

use GWA\FileManagement\ProjectDbManager;
use GWA\FileManagement\ProjectFsManager;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

class Controller
{
    protected $logger;
    protected $renderer;
    private $API = '/gwa/';

    function __construct(ContainerInterface $container)
    {
        $this->logger = $container->get('logger');
        $this->renderer = $container->get('renderer');
    }

    public function get($manager){
        if($manager == 'fs')
            return new ProjectManager(ProjectFsManager::class,$this);
        if($manager == 'db')
            return new ProjectManager(ProjectDbManager::class,$this);
        return null;
    }

    function allowCorsLocally(ResponseInterface $response){
        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }


}