<?php
/**
 * Created by PhpStorm.
 * User: blixit
 * Date: 10/06/17
 * Time: 03:25
 */

namespace GWA;

use GWA\Project;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ProjectController extends Controller
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array  $args){
        $manager = $this->get('fs');
        $projects = $manager->findAll([]);

        return $this->allowCorsLocally($response->withJson([
            'projects'=>$projects
        ]));
    }

    public function view(ServerRequestInterface $request, ResponseInterface $response, array  $args){
        $id = $args['id'];

        $manager = $this->get('fs');
        $project = $manager->findOne([
            'id' => $id
        ]);

        if( ! empty($project)){
            return $this->allowCorsLocally($response->withJson([
                'project'=>$project
            ]));
        }else{
            return $this->allowCorsLocally($response->withStatus(404)->withJson([
                'id'=>$id,
                'error' => "Project Not Found"
            ]));
        }

    }

    public function create(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        //retrieve data
        $posted = $request->getParsedBody();

        //set project
        $project = Project::getInstance();
        if(array_key_exists('creation',$posted))
            unset($posted['creation']);
        $project->fromArray($posted);

        //get Manager and create project
        $manager = $this->get('fs');
        $manager->create($project);

        return $this->allowCorsLocally($response->withJson([
            'received'=>true,
            'created'=>$project->getId()
        ]));
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, array $args){
        $id = $args['id'];

        $manager = $this->get('fs');
        $array = $manager->findOne([
            'id' => $id
        ]);

        if(! empty($array)){
            // Updating
            return $this->allowCorsLocally($response->withJson([
                'id'=>$id,
                'error'=>"Not implemented yet"
            ]));
        }else{
            return $this->allowCorsLocally($response->withStatus(404)->withJson([
                'id'=>$id,
                'error'=> "Not Found"
            ]));
        }
    }

    public function remove(ServerRequestInterface $request, ResponseInterface $response, array $args){
        $id = $args['id'];

        $manager = $this->get('fs');
        $array = $manager->findOne([
            'id' => $id
        ]);

        if(! empty($array)){
            $project = Project::getInstance();
            $project->fromArray($array);
            $project->setId($array['id'], $manager);
            $manager->remove($project);

            return $this->allowCorsLocally($response->withJson([
                'id'=>$id
            ]));
        }else{
            return $this->allowCorsLocally($response->withStatus(404)->withJson([
                'id'=>$id,
                'error'=> "Not Found"
            ]));
        }
    }
}