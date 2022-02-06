<?php

require_once('./Models/Task.php');

class TaskController{

    private $task = NULL;

    public function __construct() {
        $this->task = new Task();
    }

    public function readAll(){
        header("Access-Control-Allow-Origin: GET");
        header("Content-Type: application/json; charset=UTF-8");

        http_response_code(200);

        echo json_encode($this->task->read());
    }

    public function readById($taskId){
        header("Access-Control-Allow-Origin: GET");
        header("Content-Type: application/json; charset=UTF-8");

        http_response_code(200);

    }

    public function create($newTask){
        header("Access-Control-Allow-Origin: POST");
        header("Content-Type: application/json; charset=UTF-8");

        http_response_code(201);
        
        echo json_encode(array("data"=>array('created'=>true,'object' => $this->task->create($newTask))));
    }

    public function updateById($object){

        $result = $this->task->update($object);

        if($result == -1){
            header("HTTP/1.0 400 Bad syntax");
            header("Content-Type: application/json; charset=UTF-8");

            echo json_encode(array("data"=>array("updated"=>false,'message'=> 'id invalid')));
            
        }else{
            header("Access-Control-Allow-Origin: PUT");
            header("Content-Type: application/json; charset=UTF-8");

            http_response_code(200);

            echo json_encode($this->task->update($object));
        }
    }

    public function delete($object){

        $result = $this->task->delete($object);
        if($result == -1){
            header("HTTP/1.0 400 Bad syntax");
            header("Content-Type: application/json; charset=UTF-8");

            echo json_encode(array("data"=>array("deleted"=>false,'message'=> 'id invalid')));
            
        }else{
            header("Access-Control-Allow-Origin: DELETE");
            header("Content-Type: application/json; charset=UTF-8");

            http_response_code(200);

            echo json_encode(array("data"=>array("deleted"=>true)));
        }

    }
}

?>