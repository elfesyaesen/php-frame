<?php

namespace System\Engine;

trait Response
{
    public function jsonResponse(string $status, array|string $data = null): void
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Methods: GET, POST");
        header("Access-Control-Allow-Headers: Origin, Content-Type, Accept");
        header("Content-Type: application/json");
        if ($status === "success") {
            $response = ["status" => $status];
            if ($data !== null) {
                $response["data"] = $data;
            }
            print json_encode($response, 256);
            exit;
        } elseif ($status == "error") {
            $response = ["status" => $status];
            if ($data !== null) {
                $response["message"] = $data;
            }
            print json_encode($response, 256);
            exit;
        }
    }
}
