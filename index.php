<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include('./Controllers/TaskController.php');
include('./Controllers/Route.php');

// Add base route (startpage)
Route::add('/todolist/index.php',function(){  
   $controller->wrapGet();
});

// Get all tasks
Route::add('/todolist/index.php/tasks',function(){
    $controller = new TaskController();
    $controller->readAll();
});

// Create a new task
Route::add('/todolist/index.php/tasks',function(){

    if(isset($_POST) && count($_POST)>0){
        $controller = new TaskController();
        $controller->create($_POST);
    }
}, 'post', $_POST);

// Update a task
Route::add('/todolist/index.php/tasks',function(){
    $output = file_get_contents('php://input');

    $params = array_slice(explode("name",$output), 1); 

    $final = array();
    foreach ($params as $key => $value) {

        $x = explode("----", $value);
        $y = array_map('trim', explode("=", $x[0]));
        $z = explode("\"",$y[1]);

        $p = $z[1];
        $v = trim($z[2]);

        $final[$p] = $v;
    }

    $controller = new TaskController();
    $controller->updateById($final);
    
}, 'put');

// Delete a task
Route::add('/todolist/index.php/tasks',function(){
    $output = file_get_contents('php://input');

    $params = array_slice(explode("name",$output), 1); 

    $final = array();
    foreach ($params as $key => $value) {

        $x = explode("----", $value);
        $y = array_map('trim', explode("=", $x[0]));
        $z = explode("\"",$y[1]);

        $p = $z[1];
        $v = trim($z[2]);

        $final[$p] = $v;
    }

    $controller = new TaskController();
    $controller->delete($final);
    
}, 'delete');

Route::run('/');