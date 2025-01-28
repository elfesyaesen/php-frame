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

    public function __construct()
    {
        $this->server = $this->sanitize($_SERVER);
        $this->headers = $this->getHeaders();
        $this->get = $this->sanitize($_GET);
        $this->post = $this->sanitize($this->parseInput('POST'));
        $this->put = $this->sanitize($this->parseInput('PUT'));
        $this->patch = $this->sanitize($this->parseInput('PATCH'));
        $this->files = $this->sanitize($_FILES);
    }

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
        }
        return $data;
    }

    private function errorResponse(string $key): never
    {
        http_response_code(400);
        echo json_encode(['error' => $key . ' : bulunamadı.']);
        exit;
    }
}
