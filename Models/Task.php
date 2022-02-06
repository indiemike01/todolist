<?php
class Task{
    
    private $lastTaskId;

    public function __construct(){
        $this->lastTaskId = $this->getLastTaskId();
    }

    // Read tasks
    public function read(){
        return $this->getAllTasks();
    }

    public function create($new_task){
        
        $new_task['id'] = $this->lastTaskId + 1;

        $tasks = $this->getAllTasks();

        array_push($tasks, $new_task);

        // encode array to json
        $json = json_encode(array('data' => $tasks));

        //write json to file
        if (file_put_contents("./config/data.json", $json))
            return $new_task;
        else 
            return -1;

    }

    // Update tasks
    public function update($object){
        
        $tasks = $this->getAllTasks();

        if($this->checkId($object['id']) == -1){
            return -1;
        }

        $key = array_search($object['id'], array_column($tasks, 'id'));

        if(isset($object['title'])){
            $tasks[$key]['title'] = $object['title'];
        }

        if(isset($object['notes'])){
            $tasks[$key]['notes'] = $object['notes'];
        }

        if(isset($object['date'])){
            $tasks[$key]['date'] = $object['date'];
        }

        if(isset($object['priority'])){
            $tasks[$key]['priority'] = $object['priority'];
        }

        // encode array to json
        $json = json_encode(array('data' => $tasks));

        //write json to file
        if (file_put_contents("./config/data.json", $json))
            return $object;
        else 
            return -1;
    }

    // Delete tasks
    function delete($object){

        $tasks = $this->getAllTasks();
        if($this->checkId($object['id']) == -1){
            return -1;
        }

        $key = array_search($object['id'], array_column($tasks, 'id'));

        unset($tasks[$key]);
        $tasks = array_values($tasks);

        // encode array to json
        $json = json_encode(array('data' => $tasks));

        //write json to file
        if (file_put_contents("./config/data.json", $json))
            return 1;
        else 
            return -1;
    }

    // Get all tasks
    function getAllTasks(){
        $tasks = json_decode(file_get_contents("./config/data.json"), true);
        return $tasks["data"];
    }

    // Get the last id of the data.json file
    private function getLastTaskId(){

        $tasks = $this->getAllTasks();

        if(count($tasks) == 0){
            return 0;
        }
        return end($tasks)['id'];
    }

    // Check if the id used dor update or delete is valid/exists
    private function checkId($taskId){
        $tasks = $this->getAllTasks();
        $key = array_search($taskId, array_column($tasks, 'id'));

        if(!is_bool($key))
            return 1;
        return -1;
    }


}
?>