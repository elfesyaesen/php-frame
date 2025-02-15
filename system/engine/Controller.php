<?php

namespace System\Engine;

use System\Engine\Request;
use System\Engine\Response;

class Controller
{
    use Response, Request;
    protected $data = null;
    public function __construct()
    {
        if (isset($_SESSION["csrf"])) {
            $this->data["csrf"] = $_SESSION["csrf"];
        } else {
            $this->data["csrf"] = $_SESSION["csrf"] = bin2hex(random_bytes(16));
        }

        $this->server = $this->sanitize($_SERVER);
        $this->headers = $this->getHeaders();
        $this->get = $this->sanitize($_GET);
        $this->post = $this->sanitize($this->parseInput('POST'));
        $this->put = $this->sanitize($this->parseInput('PUT'));
        $this->patch = $this->sanitize($this->parseInput('PATCH'));
        $this->files = $this->sanitize($_FILES);
    }

    public function view(string $path, array $data = []): void
    {
        $view = APP_ROOT . '/view/' . mb_strtolower($path, 'UTF-8') . '.php';
        if (file_exists($view)) {
            extract($data);
            require_once($view);
        } else {
            throw new \Exception("view dosyası bulunamadı : " . $view);
        }
    }
}
