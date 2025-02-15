<?php

namespace System\Engine;

trait Request
{
    private array $get;
    private array $post;
    private array $headers;
    private array $files;
    private array $server;
    private array $put;
    private array $patch;

    public function server(string $key): mixed
    {
        return $this->server[$key] ?? $this->errorResponse($key);
    }

    public function get(string $key): mixed
    {
        return $this->get[$key] ?? $this->errorResponse($key);
    }

    public function post(string $key): mixed
    {
        return $this->post[$key] ?? $this->errorResponse($key);
    }

    public function put(string $key): mixed
    {
        return $this->put[$key] ?? $this->errorResponse($key);
    }

    public function patch(string $key): mixed
    {
        return $this->patch[$key] ?? $this->errorResponse($key);
    }

    public function header(string $key): mixed
    {
        return $this->headers[$key] ?? $this->errorResponse($key);
    }

    public function all(): array
    {
        return array_merge($this->get, $this->post, $this->put, $this->patch);
    }

    private function getHeaders(): array
    {
        return getallheaders() ?: [];
    }

    private function parseInput(string $method): array
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        if ($requestMethod !== strtoupper($method)) {
            return [];
        }

        $contentType = $this->headers['Content-Type'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            return json_decode(file_get_contents('php://input'), true) ?? [];
        } elseif (strpos($contentType, 'application/x-www-form-urlencoded') !== false) {
            parse_str(file_get_contents('php://input'), $data);
            return $data;
        } elseif (strpos($contentType, 'multipart/form-data') !== false) {
            if (isset($_FILES)) {
                return $_POST + ['files' => $_FILES];
            } else {
                return $_POST;
            }
        }

        return [];
    }

    private function sanitize(array|string $data): array|string
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$this->sanitize($key)] = $this->sanitize($value);
            }
        } else {
            $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
            $data = stripslashes($data);
        }
        return $data;
    }

    public function slug(string $string, string $separator = '-'): string
    {
        $string = str_replace(
            ['ı', 'ğ', 'ü', 'ş', 'i', 'ö', 'ç', 'İ', 'Ğ', 'Ü', 'Ş', 'İ', 'Ö', 'Ç'],
            ['i', 'g', 'u', 's', 'i', 'o', 'c', 'i', 'g', 'u', 's', 'i', 'o', 'c'],
            $string
        );
        $string = mb_strtolower($string, 'UTF-8');
        $string = preg_replace('/[^a-z0-9\-]/', $separator, $string);
        $string = preg_replace('/' . preg_quote($separator, '/') . '+/', $separator, $string);
        $string = trim($string, $separator);
        return $string;
    }
    
    private function errorResponse(string $key): never
    {
        http_response_code(400);
        echo json_encode(['error' => $key . ' : bulunamadı.']);
        exit;
    }
}
