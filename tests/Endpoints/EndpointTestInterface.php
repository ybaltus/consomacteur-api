<?php
namespace App\Tests\Endpoints;

interface EndpointTestInterface
{
    public function testGet(): void;

    public function testGetJsonContains(): void;

    public function testGetCollection(): void;

}
