<?php

namespace App\Tests\Endpoints;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\EnergyType;

class EnergyTypeTest extends ApiTestCase implements EndpointTestInterface
{
    public function testGet(): void
    {
        $iri = $this->findIriBy(EnergyType::class, ['nameSlug' => 'electrique']);
        $response = static::createClient()->request('GET', $iri);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testGetJsonContains(): void
    {
        $iri = $this->findIriBy(EnergyType::class, ['nameSlug' => 'electrique']);
        $response = static::createClient()->request('GET', $iri);

        $this->assertJsonContains([
            'name' => 'Électrique',
            'nameSlug' => 'electrique',
        ]);
    }

    public function testGetCollection(): void
    {
        $response = static::createClient()->request('GET', self::BASE_URL.'/energy_types');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }


}
