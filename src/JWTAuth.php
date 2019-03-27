<?php

namespace CodeBlog\JWT;

use CodeBlog\JWT\Helpers;

/**
 * Class CodeBlog JWTAuth
 *
 * @author Whallysson Avelino <https://github.com/whallysson>
 * @package CodeBlog\JWT
 */

class JWTAuth
{

    /**
     * @var array
     */
    public static $algorithms = [
        'HS256' => 'SHA256',
        'HS384' => 'SHA384',
        'HS512' => 'SHA512',
    ];

    /**
     * @var string
     */
    private $header;
    /**
     * @var string
     */
    private $payload;
    /**
     * @var string
     */
    private $signature;
    /**
     * @var string
     */
    private $hash;

    /**
     * Check the JWT token string has a valid structre and it into its three
     * component parts, header, payload and signature
     *
     * @param string $tokenString
     *
     * @return JWTAuth
     */
    public function splitToken(string $tokenString): JWTAuth
    {
        $tokenParts = explode('.', $tokenString);
        if (count($tokenParts) === 3) {
            $this->header = $tokenParts[0];
            $this->payload = $tokenParts[1];
            $this->signature = $tokenParts[2];

            return $this;
        }

        Helpers::throwError(401,
            'A string de Token possui estrutura inválida, assegure três strings separadas por pontos.');
    }

    /**
     * @return JWTAuth
     */
    public function validateHeader(): JWTAuth
    {
        $header = json_decode(Helpers::decode($this->header));
        if (empty($header)) {
            Helpers::throwError(401, 'Codificação de segmento inválida');
        }

        if (empty($header->alg)) {
            Helpers::throwError(401, 'Algoritmo vazio');
        }

        if (empty(self::$algorithms[$header->alg])) {
            Helpers::throwError(401, 'Algoritmo não suportado');
        }

        $this->hash = $header->alg;

        return $this;
    }

    /**
     * @return JWTAuth
     */
    public function validatePayload(): JWTAuth
    {
        if (empty(json_decode($this->getPayload()))) {
            Helpers::throwError(401, 'Codificação de segmento inválida');
        }

        return $this;
    }

    /**
     * Validate that the JWT expiration date is valid and has not expired.
     *
     * @return JWTAuth
     */
    public function validateExpiration(): JWTAuth
    {
        if ($this->hasOldExpiration()) {
            Helpers::throwError(401, 'Este token expirou!');
        }
        return $this;
    }

    /**
     * @return bool
     */
    private function hasOldExpiration(): bool
    {
        $diff = $this->getExpiration() - time();
        return ($diff < 0 ? true : false);
    }

    /**
     * Generate a new Signature object based on the header, payload and secret
     * then check that the signature matches the token signature
     *
     * @param string $secret
     *
     * @return bool
     */
    public function validateSignature(string $secret): bool
    {
        if (false === (Helpers::decode($this->signature))) {
            Helpers::throwError(401, 'Codificação de assinatura inválida');
        }

        $signature = $this->signature($this->header, $this->payload, $secret, $this->getHash());
        if (hash_equals($signature, $this->signature)) {
            return true;
        }

        Helpers::throwError(401, 'A assinatura do token é inválida!! Entrada: ' . $this->signature);
    }

    /**
     * Assine uma string com uma determinada chave e algoritmo
     *
     * @return string
     */
    public function signature(string $header, string $payload, string $secret, string $hash = 'HS256'): string
    {
        if (empty(static::$algorithms[$hash])) {
            Helpers::throwError(401, 'Algoritmo não suportado');
        }

        return Helpers::encode(hash_hmac(static::$algorithms[$hash], "{$header}.{$payload}", $secret, true));
    }

    /**
     * Json decode the JWT payload and return the expiration attribute
     *
     * @return string
     */
    public function getExpiration(): string
    {
        $payload = json_decode($this->getPayload());
        if (isset($payload->exp)) {
            return $payload->exp;
        }

        Helpers::throwError(401, 'Objeto inválido, nenhum conjunto de parâmetros de expiração');
    }

    /**
     * Base 64 decode and return the JWT payload
     *
     * @return string
     */
    public function getPayload(): string
    {
        return Helpers::decode($this->payload);
    }

    /**
     * Base 64 decode and return the JWT header
     *
     * @return string
     */
    public function getHeader(): string
    {
        return Helpers::decode($this->header);
    }

    /**
     * Return payload but decode JSON string to stdClass first
     *
     * @return stdClass
     */
    public function getPayloadDecodeJson(): \stdClass
    {
        return json_decode($this->getPayload());
    }

    /**
     * Return header but decode JSON string to stdClass first
     *
     * @return stdClass
     */
    public function getHeaderDecodeJson(): \stdClass
    {
        return json_decode($this->getHeader());
    }

    /**
     * Return the hash type for the signature hashing
     *
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

}
