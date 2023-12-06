<?php

if( ! \function_exists('pretty_print') ){
    function pretty_print(mixed $data)
    {
        echo '<pre>';
        \print_r($data);
        echo '</pre>';
    }
}

if( ! \function_exists('pretty_print_and_die') ){
    function pretty_print_and_die(mixed $data)
    {
        pretty_print($data);
        exit;
    }
}

?>