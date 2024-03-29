<?php
namespace App\Tests\Endpoints;

interface EndpointTestInterface
{
    public const BASE_URL = '/api/opendata';
    public const USER_CREDENTIALS = ['toto', 'toto1234'];

    public function testGet(): void;

    public function testGetJsonContains(): void;

    public function testGetCollection(): void;

}
