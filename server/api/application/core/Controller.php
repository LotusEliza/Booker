<?php
/**
 * Created by PhpStorm.
 * User: lotus
 * Date: 2/9/19
 * Time: 11:43 AM
 */

namespace application\core;

use application\core\View;
use application\core\DataViewer;
use Exception;
use \Firebase\JWT\JWT;


class Controller
{

    public $route;
    public $view;
    public $dataView;
    public $model;
    public $var;
    public $format;

    public function __construct($route, $var, $format)
    {
        $this->route = $route;
        $this->var = $var;
        $this->format = $format;
        $this->view = new View($route);
        $this->dataView = new DataViewer();
        $this->model = $this->loadModel($route['controller']);
    }

    public function loadModel($name){
        $path = 'application\models\\'.ucfirst($name);

        if(class_exists($path)){
            return new $path($this->var);
        }
    }

    public function response($status,$status_message,$data,$format)
    {
        if(!$format){
           $format = '.json';
        }
        $format = ltrim($format, '.');
        if ($format == 'json' || $format == 'txt' || $format == 'xml' || $format == 'html') {
            header("Access-Control-Allow-Origin: *");
            header('Access-Control-Allow-Methods: POST, GET, PUT, OPTIONS, DELETE, PATCH');
            header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type,token, Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");
            header("Access-Control-Expose-Headers: Location");
            header("HTTP/1.0 " . $status);
            header('Content-Type: application/' . $format);
            $response['status'] = $status;
            $response['status_message'] = $status_message;
            $response['data'] = $data;
            $this->dataView->sendResponse($format, $response);
        }
    }

//    public function format($format, $response){
//        switch ($format) {
//            case "json":
//                echo json_encode($response);
//                break;
//            case "txt":
//                $result = $this->toText($response);
//                print_r($result);
//                break;
//            case "xml":
//                echo $this->toXml($response);
//                break;
//            case "html":
//                echo $this->toHtml($response);
//                break;
//            default:
//                break;
//        }
//    }

    public function jwtValid()
    {
        $data = json_decode(file_get_contents("php://input"));
        $jwt=isset($data->jwt) ? $data->jwt : "";
        if ($jwt){
            try {
                $decoded = JWT::decode($jwt, SECRET_KEY, array('HS256'));
                if($decoded){
                    return true;
                }
            } catch (Exception $e) {
                $this->response(400, ERROR_INCORRECT_JWT, $e->getMessage(), $this->format);
            }
        }
    }

    public  function testInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }



    public function emptyVal($array){
        return $contains_empty = in_array("", $array, true);
    }

}