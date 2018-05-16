<?php
// Routes
require_once 'Controllers/Controller.php';

$app->group('/gwa',function (){
    //minimal requirements
    require_once 'Controllers/ProjectController.php';

    $this->map(['GET'],'/project/{id}','GWA\ProjectController:view');
    $this->map(['GET'],'/project','GWA\ProjectController:index');
    $this->map(['POST'],'/project','GWA\ProjectController:create');
    $this->delete('/project/{id}','GWA\ProjectController:remove');
    $this->put('/project/{id}','GWA\ProjectController:update');

});


$app->group('/gwa',function () {
    //minimal requirements
    require_once 'Controllers/FileController.php';

    $this->post('/file/tree','GWA\FileController:tree');
    $this->get('/file/{path:[a-zA-Z0-9-_.\+\/]+}','GWA\FileController:view');

});


$app->group('/gwa/comp',function () {
    //minimal requirements
    require_once 'Controllers/CompilatorController.php';

    $this->map(['POST'],'ile','GWA\CompilatorController:compile');

});