<?php
 
 namespace Prastadev\PHP\MVC\App;
 

 class Router 
 {
     private static array $routes = [];

     static public function add(string $method,
     string $path,
     string $controller ,
     string $function,
     array $midleware = []):void
     {
         self::$routes[] = [
             'method' => $method,
             'path' => $path,
             'controller' => $controller,
             'function' => $function,
             'midleware' =>$midleware,
         ];

     }

     static public function run() :void 
     {
        $path = "/";

        if(isset($_SERVER["PATH_INFO"])){
            $path = $_SERVER["PATH_INFO"];
        }

        $method = $_SERVER['REQUEST_METHOD'];

        foreach(self::$routes as $route){
            $patern = "#^" . $route['path'] ."$#";
            if(preg_match($patern, $path, $variable ) && $method == $route["method"]){

                foreach($route['midleware'] as $midleware){
                    $instance = new $midleware;
                    $instance->before();
                }

                $controller = new $route['controller'];
                $function = $route['function'];
                // $controller->$function();

                array_shift($variable);

                call_user_func_array([$controller,$function], $variable);
                return;
            }
        }

        http_response_code(404);
        echo "controller not found";
        
     }

     static public function keren(){
         echo "kamu keren";
     }
 }




?>