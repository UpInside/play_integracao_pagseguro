<?php
/**
 * Created by PhpStorm.
 * User: gustavoweb
 * Date: 06/12/2017
 * Time: 18:44
 */

// Permite trafegar informações vindas do WebService de SandBox do PagSeguro
header("access-control-allow-origin: https://sandbox.pagseguro.uol.com.br");

// Resgata as informações
$post = filter_input_array(INPUT_POST, FILTER_DEFAULT);

if (!empty($post)) {
    $file = fopen('postback.json', 'a+');
    fwrite($file, json_encode($post));
    fclose($file);
}