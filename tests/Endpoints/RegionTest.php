<?php

namespace App\Tests\Endpoints;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Region;

class RegionTest extends ApiTestCase implements EndpointTestInterface
{
    public function testGet(): void
    {
        $iri = $this->findIriBy(Region::class, ['nameSlug' => 'bretagne']);
        $response = static::createClient()->request('GET', $iri);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testGetJsonContains(): void
    {
        $iri = $this->findIriBy(Region::class, ['nameSlug' => 'bretagne']);
        $response = static::createClient()->request('GET', $iri);

        $this->assertJsonContains([
            'name' => 'Bretagne',
            'nameSlug' => 'bretagne',
        ]);
    }

    public function testGetCollection(): void
    {
        $response = static::createClient()->request('GET', self::BASE_URL.'/energy_types');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }
}
