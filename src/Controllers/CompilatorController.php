<?php
/**
 * Created by PhpStorm.
 * User: blixit
 * Date: 05/06/17
 * Time: 21:21
 */

namespace GWA;

require_once __DIR__.'/../lib/FileSystem.php';

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CompilatorController extends Controller
{

    public function compile(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $posted = $request->getParsedBody();
        $program = str_replace("<br>","",(array_key_exists('query',$posted)) ? $posted['query'] : '');

        $data = [
            'program'=>$program
        ];
        $fs = new FileSystem();

        //paths
        $app_dir = __DIR__."/../..";
        $file = $app_dir."/data/user1/web.assistant";
        $log = $app_dir."/logs/log.json";
        $option = " --emit-issue-json-file=$log";

        if(! is_dir($app_dir."/data/user1"))
            mkdir($app_dir."/data/user1");

        $fs->write($file, $data['program']);

        $fs->galgas("$file --ast-location=$file $option",function($lines, $return_var) use ($log, $file, $fs, &$data) {
            //$data['file'] = $file;
            //$data['out'] = $lines;  //<-- active le mode apprentissage (ou debug)
            $data['return_var'] = $return_var;
            $data['errors'] = json_decode($fs->read($log),true);
            $data['file'] = str_replace("<br>","",$fs->read($file));

        });

        return $this->allowCorsLocally($response->withJson($data));

    }

}