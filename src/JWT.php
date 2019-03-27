<?php

namespace CodeBlog\JWT;

use CodeBlog\JWT\Helpers;

/**
 * Class CodeBlog JWT
 *
 * @author Whallysson Avelino <https://github.com/whallysson>
 * @package CodeBlog\JWT
 */

class JWT extends JWTAuth
{

    // Converte e assina um objeto ou matriz PHP em uma cadeia JWT.
    /**
     * @param array $payload
     * @param string $secret
     * @param string $hash
     *
     * @return string
     */
    public function encode(array $payload, string $secret, string $hash = 'HS256'): string
    {
        $header = array('typ' => 'JWT', 'alg' => $hash);

        $sing_header = Helpers::encode(json_encode($header));
        $sing_payload = Helpers::encode(json_encode($payload));

        $signature = $this->signature($sing_header, $sing_payload, $secret, $hash);

        return "{$sing_header}.{$sing_payload}.{$signature}";
    }

    // Decodifica uma string JWT em um objeto PHP.

    /**
     * @param string $jwt
     * @param string $secret
     *
     * @return null|\stdClass
     */
    public function decode(string $jwt, string $secret): ?\stdClass
    {
        if (empty($secret)) {
            Helpers::throwError(401, 'A Key não pode ser vazia');
        }

        if (self::validate($jwt, $secret)) {
            return $this->getPayloadDecodeJson();
        }

        return null;
    }

    /**
     * @return string
     */
    public function authHeader(): string
    {
        $allHeaders = array_change_key_case(getallheaders(), CASE_LOWER);
        $authorization = !empty($allHeaders['authorization']) ? $allHeaders['authorization'] : null;

        // Verifica se o Token foi informado
        if (empty($authorization)) {
            return Helpers::throwError(401);
        }

        $parts = explode(' ', $authorization);

        // Verifica o formato do Token
        if (count($parts) !== 2) {
            return Helpers::throwError(401, 'Token error');
        }

        list($scheme, $token) = $parts;

        // Verifica se existe a palavra "Bearer" no Token
        if (!preg_match('/^Bearer$/i', $scheme)) {
            return Helpers::throwError(401, 'Token mal formado');
        }

        return $token;
    }

    /**
     * Validate a JSON Web Token's expiration and signature
     *
     * @param string $token
     * @param string $secret
     *
     * @return bool
     */
    public function validate(string $token, string $secret): bool
    {
        return $this->splitToken($token)
            ->validateHeader()
            ->validatePayload()
            ->validateExpiration()
            ->validateSignature($secret);
    }

}
