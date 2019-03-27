<?php

namespace CodeBlog\JWT;

/**
 * Class CodeBlog Helpers
 *
 * @author Whallysson Avelino <https://github.com/whallysson>
 * @package CodeBlog\JWT
 */

class Helpers
{

    /**
     * Encode a JSON string to a base64 Url string
     *
     * @param string $jsonTokenString
     *
     * @return string
     */
    public static function encode(string $jsonTokenString): string
    {
        return str_replace('=', '', strtr(base64_encode($jsonTokenString), '+/', '-_'));
    }

    /**
     * Decode a base64 Url string to a JSON string
     *
     * @param string $base64UrlString
     *
     * @return string
     */
    public static function decode(string $base64UrlString): string
    {
        $remainder = strlen($base64UrlString) % 4;
        if ($remainder) {
            $base64UrlString .= str_repeat('=', 4 - $remainder);
        }

        return base64_decode(strtr($base64UrlString, '-_', '+/'));
    }

    /**
     * @param int $code
     * @param $data
     */
    public static function returnResponse(int $code, $data): void
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($code);
        die(json_encode(['response' => ['status' => $code, 'result' => $data]]));
    }

    /**
     * @param int $code
     * @param null|string $message
     */
    public static function throwError(int $code, ?string $message = null): void
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($code);
        die(json_encode([
            'error' => [
                'status' => $code,
                'message' => ($message ? $message : 'Nenhum token fornecido'),
            ],
        ]));
    }

}
