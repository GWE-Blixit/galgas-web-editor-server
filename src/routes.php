<?php
// Routes
require_once 'Controllers/Controller.php';

$app->group('/gwa',function (){
    //minimal requirements
    require_once 'Controllers/ProjectController.php';

    $this->map(['GET'],'/projects/{id}','GWA\ProjectController:view');
    $this->map(['GET'],'/projects','GWA\ProjectController:index');
    $this->map(['POST'],'/projects','GWA\ProjectController:create');
    $this->delete('/projects/{id}','GWA\ProjectController:remove');
    $this->put('/projects/{id}','GWA\ProjectController:update');

});


$app->group('/gwa/files',function () {
    //minimal requirements
    require_once 'Controllers/FileController.php';

    $this->post('/tree','GWA\FileController:tree');
    /**
     * exemple: /single?path=path/to/get.extension
     */
    $this->get('/single','GWA\FileController:view');
    $this->post('/create','GWA\FileController:create');
    $this->post('/save','GWA\FileController:save');
    $this->post('/delete','GWA\FileController:delete');

});


$app->group('/gwa/comp',function () {
    //minimal requirements
    require_once 'Controllers/CompilatorController.php';

    $this->get('ile/version','GWA\CompilatorController:version');
    $this->map(['POST'],'ile','GWA\CompilatorController:compile');

});