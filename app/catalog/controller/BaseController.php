<?php

namespace App\Catalog\Controller;

use System\Engine\Controller;

class BaseController extends Controller
{
    public function index(): void
    {
        $this->data["title"] = "Catalog index title";


        $this->view("catalog/index", $this->data);
    }
}
