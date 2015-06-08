<?php
function getPath(){
    $filepath = explode('/', $_SERVER['PHP_SELF'], -1);
    $filepath = implode('/', $filepath);
    $redirect = "http://" .$_SERVER['HTTP_HOST'].$filepath;
    return $redirect;
}

function get_page_url(){
    // Find out the URL of a PHP file
    $url = 'http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$_SERVER['SERVER_NAME'];
    if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != ''){
        $url.= $_SERVER['REQUEST_URI'];
    }
    else{
        $url.= $_SERVER['PATH_INFO'];
    }
    return $url;
}

function redirect($url){
    header("Location: $url");
    exit();
}
?>