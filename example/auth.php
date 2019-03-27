<?php

require __DIR__ . '/../vendor/autoload.php';

use CodeBlog\JWT\JWT;

$key = 'codeblog.com.br'; // sua chave secreta

$jwt = new JWT();
$token = $jwt->authHeader();

$decoded = $jwt->decode($token, $key);
var_dump($decoded);
