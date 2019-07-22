<?php


namespace application\core;

use SimpleXMLElement;


class DataViewer
{
    public function __construct()
    {

    }

    public function sendResponse($format, $response){
        switch ($format) {
            case "json":
                echo json_encode($response);
                break;
            case "txt":
                $result = $this->toText($response);
                print_r($result);
                break;
            case "xml":
                echo $this->toXml($response);
                break;
            case "html":
                echo $this->toHtml($response);
                break;
            default:
                break;
        }
    }

    public function toText($var){
        $result = '';
        foreach($var as $key => $val){
            if(is_array($val)){
                foreach($val as $key1 => $val1){
                    $result .= "{$key1}::\n\n";
                    if(is_array($val1)){
                        foreach ($val1 as $key2 => $val2){
                            foreach ($val2 as $key3 => $val3){
                                $result .= "{$key3}: {$val3}\n";
                            }
                            $result .= "\n";
                        }
                    }elseif(is_string($val1)){
                        $result .= "{$key1}: {$val1}\n";
                    }
                }
            }elseif(is_string($val)){
                $result .= "{$key}: {$val}\n";
            }
        }
        return $result;
    }

    public function toXml($var){
        $xml = new SimpleXMLElement('<data/>');
        if (is_array($var))
        {
            foreach ($var as $key => $val)
            {
                if (is_array($val))
                {
                    $item = $xml->addChild('node');
                    foreach ($val as $key1 => $val1)
                    {
                        if (is_array($val1)){
                            foreach ($val1 as $key2 => $val2){
                                $item1 = $item->addChild('item');
                                if (is_array($val2)){
                                    foreach ($val2 as $key3 => $val3){
                                        $item1->addChild($key3, $val3);
                                    }
                                }elseif (is_string($val2)){
                                    $xml->addChild($key2, $val2);
                                }
                            }
                        }elseif (is_string($val1)){
                            $xml->addChild($key1, $val1);
                        }
                    }
                }
                if(is_string($val))
                {
                    $xml->addChild($key, $val);
                }
            }
            $result = $xml->asXML();
            return $result;
        }
    }

    public function toHtml($var){
        if(is_array($var)){
            $result = "<div class='response'>\n";
            foreach($var as $key => $val){
                if (is_array($val)){
                    $result .= "<div class='data'>\n";
                    foreach($val as $key1 => $val1){
                        $result .= "<div class='item'>\n";
                        if(is_array($val1)){
                            foreach ($val1 as $key2 => $val2){
                                foreach ($val2 as $key3 => $val3){
                                    $result .= "<div class='{$key3}'>$val3</div>\n";
                                }
                                $result .= "<br/>\n";
                            }
                        }elseif(is_string($val1)){
                            $result .= "<div class='{$key1}'>$val1</div>\n";
                        }
                    }
                    $result .= "</div></div>\n";
                }else if(is_string($val)){
                    $result .= "<div class='{$key}'>$val</div>\n";
                }
            }
            $result .= "</div>\n";
            return $result;
        }
        return false;
    }

}