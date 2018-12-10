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
    public function version(ServerRequestInterface $request, ResponseInterface $response, array  $args) {
        
        $fs = new FileSystem();
        $fs->galgasVersion(function($lines, $return_var) use (&$data) {
            if (empty($lines)) {
                $data['version'] = 'not available';
                $data['code'] = $return_var;
                return;
            }
            $parts = explode(',', $lines[0]);
            // $parts[0] = "galgas : 3.3.3"
            // $parts[1] = " build with GALGAS GALGASBETAVERSION"

            $version = explode(':', $parts[0]);
            $data['version'] = trim($version[1]); 
        });

        return $this->allowCorsLocally($response->withJson($data));
    }

    public function compile(ServerRequestInterface $request, ResponseInterface $response, array $args) {
        $posted = $request->getParsedBody();
        
        $program = str_replace("<br>", "", (array_key_exists('query', $posted)) ? $posted['query'] : '');
        $project = $posted['project'];
        $filepath = array_key_exists('path', $posted) ? $posted['path'] : '';

        $data = [
            'project'=>$project,
            'program'=>$program
        ];
        $fs = new FileSystem();

        // directories
        $app_dir = __DIR__."/../..";
        $projectDirectory = $app_dir."/data/$project";
        // $file = "$projectDirectory/web.assistant";
        $file = $app_dir."/data/$filepath";
        $logDirectory = "$projectDirectory/logs";

        // paths
        $log = "$logDirectory/log.json";
        $option = " --emit-issue-json-file=$log";

        if(! is_dir($projectDirectory))
            mkdir($projectDirectory);

        if(! is_dir($logDirectory))
            mkdir($logDirectory);

        $fs->write($file, $data['program']);

        $ms = microtime(true);
        $fs->galgas("$file --ast-location=$file $option",function($lines, $return_var) use ($log, $file, $fs, &$data, $ms, $projectDirectory) {
            //$data['file'] = $file;
            $data['out'] = $lines;  //<-- active le mode apprentissage (ou debug)
            $data['return_var'] = $return_var;
            $data['errors'] = json_decode($fs->read($log),true);
            // remove server path from SOURCE path
            foreach($data['errors'] as $key => $error) {
                $data['errors'][$key]['SOURCE'] = str_replace(realpath(__DIR__ . '/../../data'), '', $data['errors'][$key]['SOURCE']);
            }

            $data['compiled'] = str_replace("<br>","",$fs->read($file));
            $data['duration'] = microtime(true) - $ms;
            $data['time'] = (new \DateTime('now'))->getTimestamp();
        });

        unset($data['program']);

        return $this->allowCorsLocally($response->withJson($data));

    }

}