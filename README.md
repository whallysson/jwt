# JWT Library 

[![Maintainer](http://img.shields.io/badge/maintainer-@whallysson-blue.svg?style=flat-square)](https://twitter.com/whallysson)
[![Source Code](http://img.shields.io/badge/source-codeblog/jwt-blue.svg?style=flat-square)](https://github.com/whallysson/jwt)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/codeblog/jwt.svg?style=flat-square)](https://packagist.org/packages/codeblog/jwt)
[![Latest Version](https://img.shields.io/github/release/whallysson/jwt.svg?style=flat-square)](https://github.com/whallysson/jwt/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build](https://img.shields.io/scrutinizer/build/g/whallysson/jwt.svg?style=flat-square)](https://scrutinizer-ci.com/g/whallysson/jwt)
[![Quality Score](https://img.shields.io/scrutinizer/g/whallysson/jwt.svg?style=flat-square)](https://scrutinizer-ci.com/g/whallysson/jwt)
[![Total Downloads](https://img.shields.io/packagist/dt/codeblog/jwt.svg?style=flat-square)](https://packagist.org/packages/codeblog/jwt)

###### JWT is a component that simplifies the handling of authentication in APIs. JWT contains information and metadata that describes the user's entity, authorization data, token validity, valid domain, etc.

JWT é um componente que simplifica o tratamento de autenticação em APIs. JWT contém informações e metadados que descrevem a entidade do usuário, dados de autorização, validade do token, domínio válido, etc.


### Highlights

- Simple installation (Instalação simples)
- Creating authenticated tokens for API (Criação de tokens autenticados para API)
- Token authenticity check (Verificação de autenticidade do token)
- Token expiration check (Verificação de expiração do token)

## Installation

JWT is available via Composer:

```bash
"codeblog/jwt": "^1.0"
```

or run

```bash
composer require codeblog/jwt
```

## Documentation

###### For details on how to use, see a sample folder in the component directory. In it you will have an example of use for each class. It works like this:

Para mais detalhes sobre como usar, veja uma pasta de exemplo no diretório do componente. Nela terá um exemplo de uso para cada classe. Ele funciona assim:

#### Create JWT:

```php
<?php

require __DIR__ . '/../vendor/autoload.php';

use CodeBlog\JWT\JWT;

$key = 'codeblog.com.br'; // your secret key

$payload = array(
    "iss" => "api.dominio.com", // the token emitter. You can use the domain where your api is. Ex: api.domain.com
    "iat" => time(), // The time the JWT was issued. Can be used to determine the age of JWT.
    "exp" => time() + (60 * 60), // This is likely to be the most commonly used registered claim. This will set the expiration on the NumericDate value. The expiration MUST be after the current date / time.
    "data" => [
        "user_id" => 10,
        "user_email" => "fulano@gmail.com"
    ]
);


$jwt = (new JWT)->encode($payload, $key);
var_dump($jwt);

//$decoded = JWT::decode($jwt, $key);
//var_dump($decoded);
```

#### Auth JWT:

```php
<?php

require __DIR__ . '/../vendor/autoload.php';

use CodeBlog\JWT\JWT;

$key = 'codeblog.com.br'; // your secret key

$jwt = new JWT();
$token = $jwt->authHeader();

$decoded = $jwt->decode($token, $key);
var_dump($decoded);

```


## Contributing

Please see [CONTRIBUTING](https://github.com/whallysson/jwt/blob/master/CONTRIBUTING.md) for details.

## Support

###### Security: If you discover any security related issues, please email whallyssonallain@gmail.com instead of using the issue tracker.

Se você descobrir algum problema relacionado à segurança, envie um e-mail para whallyssonallain@gmail.com em vez de usar o rastreador de problemas.

Thank you

## Credits

- [Whallysson Avelino](https://github.com/whallysson) (Developer)
- [CodBlog](https://github.com/whallysson) (Team)
- [All Contributors](https://github.com/whallysson/jwt/contributors) (This Rock)

## License

The MIT License (MIT). Please see [License File](https://github.com/whallysson/jwt/blob/master/LICENSE) for more information.
