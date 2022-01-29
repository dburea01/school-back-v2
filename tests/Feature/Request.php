<?php

namespace tests\Feature;

trait Request
{
    public function getEndPoint(): string
    {
        return '/api/v1';
    }

    /*
    public function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ];
    }
    */
}
