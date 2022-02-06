<?php

class Route{

  private static $routes = Array();
  private static $pathNotFound = null;
  private static $methodNotAllowed = null;
  private static $createFields = array('title', 'date');
  private static $updateFields = array('id');
  private static $deleteFields = array('id');

  public static function add($expression, $function, $method = 'get', $params = false){
    array_push(self::$routes,Array(
      'expression' => $expression,
      'function' => $function,
      'method' => $method,
      'params' => $params
    ));
  }

  public static function pathNotFound($function){
    self::$pathNotFound = $function;
  }

  public static function methodNotAllowed($function){
    self::$methodNotAllowed = $function;
  }

  public static function run($basepath = '/'){

    // Parse current url
    $parsed_url = parse_url($_SERVER['REQUEST_URI']);//Parse Uri

    if(isset($parsed_url['path'])){
      $path = $parsed_url['path'];
    }else{
      $path = '/';
    }

    // Get current request method
    $method = $_SERVER['REQUEST_METHOD'];

    $path_match_found = false;

    $route_match_found = false;

    foreach(self::$routes as $route){

      // If the method matches check the path

      // Add basepath to matching string
      if($basepath!=''&&$basepath!='/'){
        $route['expression'] = '('.$basepath.')'.$route['expression'];
      }

      // Add 'find string start' automatically
      $route['expression'] = '^'.$route['expression'];

      // Add 'find string end' automatically
      $route['expression'] = $route['expression'].'$';

      // Check path match	
      if(preg_match('#'.$route['expression'].'#',$path,$matches)){

        $path_match_found = true;

        // Check method match
        if(strtolower($method) == strtolower($route['method'])){

          array_shift($matches);// Always remove first element. This contains the whole string

          if($basepath!=''&&$basepath!='/'){
            array_shift($matches);// Remove basepath
          }

          $badSyntax = false;
          $error = array();

          // Check the required fields
          switch ($method) {
            case 'POST':

              if(!isset($_POST['title']) && !isset($_POST['date'])){
                $badSyntax = true;
                header("HTTP/1.0 400 Bad syntax");
                header("Content-Type: application/json; charset=UTF-8");
                $route_match_found = true;
                
                call_user_func_array(function(){
                  echo json_encode(array('data' => array ('created' => false, 'title' => 'required', 'date' => 'required')));
                }, Array($path,$method));

              break;
              }
              
              if(!isset($_POST['title'])){
                $badSyntax = true;
                header("HTTP/1.0 400 Bad syntax");
                header("Content-Type: application/json; charset=UTF-8");
                $route_match_found = true;
                call_user_func_array(function(){
                  echo json_encode(array('data' => array ('created' => false, 'title' => 'required')));
                }, Array($path,$method));

              break;
              }

              if(!isset($_POST['date'])){
                $badSyntax = true;
                header("HTTP/1.0 400 Bad syntax");
                header("Content-Type: application/json; charset=UTF-8");
                $route_match_found = true;
                call_user_func_array(function(){
                  echo json_encode(array('data' => array ('created' => false, 'date' => 'required')));
                }, Array($path,$method));
              }
              break;
            case 'PUT':

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

              if(!isset($final['id'])){
                $badSyntax = true;
                header("HTTP/1.0 400 Bad syntax");
                header("Content-Type: application/json; charset=UTF-8");
                $route_match_found = true;
                call_user_func_array(function(){
                  echo json_encode(array('data' => array ('UPDATED' => false, 'id' => 'required')));
                }, Array($path,$method));
              }

              break;
            case 'DELETE':

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

              if(!isset($final['id'])){
                $badSyntax = true;
                header("HTTP/1.0 400 Bad syntax");
                header("Content-Type: application/json; charset=UTF-8");
                $route_match_found = true;
                call_user_func_array(function(){
                  echo json_encode(array('data' => array ('deleted' => false, 'id' => 'required')));
                }, Array($path,$method));
              }

              break;
            default:
              break;
          }
          
          if($badSyntax === false){
              echo self::$methodNotAllowed;
              header("HTTP/1.0 400 Bad syntax"); 
              header("Content-Type: application/json; charset=UTF-8"); 

              call_user_func_array($route['function'], $matches);

              $route_match_found = true;
          }
          
          // Do not check other routes
          break;
        }
      }
    }

    // No matching route was found
    if(!$route_match_found){

      // But a matching path exists
      if($path_match_found){
        header("HTTP/1.0 405 Method Not Allowed");
        if(self::$methodNotAllowed){
          call_user_func_array(self::$methodNotAllowed, Array($path,$method));
        }
      }else{
        header("HTTP/1.0 404 Not Found");
        echo self::$pathNotFound;
        if(self::$pathNotFound){
          call_user_func_array(self::$pathNotFound, Array($path));
        }
      }

    }

  }

}