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
        $directory = $request->getParsedBody()['directory'];

        $tree = $manager->findJqueryTree([
            'directory' => $directory
        ]);
        $body = $response->getBody();
        $body->write($tree);

        return $response;
    }

    public function view(ServerRequestInterface $request, ResponseInterface $response, array  $args){
        $path = $request->getQueryParams()['path'];

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

    public function create(ServerRequestInterface $request, ResponseInterface $response, array  $args){
        $path = $request->getParsedBody()['path'];
        $content = $request->getParsedBody()['content'];

        $manager = $this->get('fs');

        $file = realpath(__DIR__."/../../data") . "/$path";
        $is_file = $manager->getFileManager()->is_file($file);

        if ($is_file) {
            return $this->allowCorsLocally($response->withStatus(400)->withJson([
                'path'=>$path,
                'error' => "File exists"
            ]));
        }

        try{
            $manager->getFileManager()->write($file, $content);
            return $this->allowCorsLocally($response->withJson([
                'path'=>$path,
            ]));
        } catch(\Exception $exception) {
            return $this->allowCorsLocally($response->withStatus(500)->withJson([
                'path'=>$path,
                'error' => "${$exception->getMessage()}"
            ]));
        }
    }

    public function save(ServerRequestInterface $request, ResponseInterface $response, array  $args){
        $path = $request->getParsedBody()['path'];
        $content = $request->getParsedBody()['content'];

        $manager = $this->get('fs');

        $file = realpath(__DIR__."/../../data") . "/$path";
        $is_file = $manager->getFileManager()->is_file($file);

        if (! $is_file) {
            return $this->allowCorsLocally($response->withStatus(400)->withJson([
                'path'=>$path,
                'error' => "File doesn't exist. Try to create it instead."
            ]));
        }

        try{
            $manager->getFileManager()->write($file, $content);
            return $this->allowCorsLocally($response->withJson([
                'path'=>$path,
            ]));
        } catch(\Exception $exception) {
            return $this->allowCorsLocally($response->withStatus(500)->withJson([
                'path'=>$path,
                'error' => "${$exception->getMessage()}"
            ]));
        }
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response, array  $args){
        $path = $request->getParsedBody()['path'];

        $manager = $this->get('fs');

        $file = realpath(__DIR__."/../../data") . "/$path";
        $is_file = $manager->getFileManager()->is_file($file);

        if (! $is_file) {
            return $this->allowCorsLocally($response->withStatus(400)->withJson([
                'path' => $path,
                'error' => "File doesn't exist"
            ]));
        }

        try{
            $manager->getFileManager()->remove($file);
            return $this->allowCorsLocally($response->withJson([
                'path'=>$path,
            ]));
        } catch(\Exception $exception) {
            return $this->allowCorsLocally($response->withStatus(500)->withJson([
                'path'=>$path,
                'error' => "${$exception->getMessage()}"
            ]));
        }
    }


}