<?php
/**
 * Created by PhpStorm.
 * User: blixit
 * Date: 11/06/17
 * Time: 18:49
 */

namespace GWA;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class FileController extends Controller
{

    public function tree(ServerRequestInterface $request, ResponseInterface $response, array  $args){
        $manager = $this->get('fs');
        $directory = $request->getParsedBody()['dir'];

        $tree = $manager->findJqueryTree([
            'directory' => $directory
        ]);
        $body = $response->getBody();
        $body->write($tree);

        return $response;
    }

    public function view(ServerRequestInterface $request, ResponseInterface $response, array  $args){

        $path = $args['path'];

        $manager = $this->get('fs');
        $content = $manager->getFile([
            'path' => $path
        ]);


        if( ! is_null($content)){
            return $this->allowCorsLocally($response->withJson([
                'path'=>$path,
                'content'=>$content
            ]));
        }else{
            return $this->allowCorsLocally($response->withStatus(404)->withJson([
                'path'=>$path,
                'error' => "File Not Found"
            ]));
        }

    }


}