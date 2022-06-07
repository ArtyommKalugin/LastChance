<?php

    include_once 'helpers/headers.php';
    include_once 'helpers/check.php';
    include_once 'helpers/requester.php';
    include_once 'helpers/output.php';

    global $Link;
        
    header("Content-type: application/json");
        
    function getData($method) {
        $data = new stdClass();
        if ($method != "GET") {
            $data->body = json_decode(file_get_contents('php://input'));
        } 

        $data->parameters = [];
            $dataGet = $_GET;

            foreach ($dataGet as $key => $value) {
                if ($key != "q") {
                    $data->parameters[$key] = $value;
                }
            }

        return $data;
    }

    function getMethod() {
        return $_SERVER["REQUEST_METHOD"];
    }

    $Link = mysqli_connect("localhost", "backend", "221001", "backend");
    if (!$Link) {
        setHTTPStatus("500");
    }

    $url = isset($_GET["q"]) ? $_GET["q"] : "";
    $url = rtrim($url, "/");
    $urlList = explode("/", $url);

    $router = $urlList[0];
    $requestData = getData(getMethod());
    $method = getMethod();

    if(file_exists(realpath(dirname(__FILE__)) . "/routers/" . $router . ".php")) {
        include_once "routers/" . $router . ".php";
        route($method, $urlList, $requestData);
    } else {
        setHTTPStatus("404");
    }

    mysqli_close($Link);
    return;

?>
