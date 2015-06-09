<?php
function getPath(){
    $filepath = explode('/', $_SERVER['PHP_SELF'], -1);
    $filepath = implode('/', $filepath);
    $redirect = "http://" .$_SERVER['HTTP_HOST'].$filepath;
    return $redirect;
}
function redirect($url){
    header("Location: ".getPath()."/$url", true);
}
?>
