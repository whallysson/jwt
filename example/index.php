<?php

require __DIR__ . '/../vendor/autoload.php';

use CodeBlog\JWT\JWT;

$key = 'codeblog.com.br'; // sua chave secreta

$payload = array(
    "iss" => "api.dominio.com", // o emissor do token. Pode usar o dominio onde está sua api. Ex: api.dominio.com
    "iat" => time(), // O horário em que o JWT foi emitido. Pode ser usado para determinar a idade do JWT.
    "exp" => time() + (60 * 60), // Essa provavelmente será a reivindicação registrada mais usada. Isso definirá a expiração no valor NumericDate. A expiração DEVE ser depois da data/hora atual.
    "data" => [
        "user_id" => 10,
        "user_email" => "fulano@gmail.com"
    ]
);


$jwt = (new JWT)->encode($payload, $key);
var_dump($jwt);

//$decoded = JWT::decode($jwt, $key);
//var_dump($decoded);
