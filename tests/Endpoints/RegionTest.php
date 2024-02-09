<?php

namespace App\Tests\Endpoints;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Region;
use App\Tests\Trait\AppTestTrait;

class RegionTest extends ApiTestCase implements EndpointTestInterface
{
    use AppTestTrait;

    /**
     * @before
     */
    public function testGet(): void
    {
        $client = self::createClient();

        $this->initTokenAPI($client, self::USER_CREDENTIALS[0], self::USER_CREDENTIALS[1]);

        $iri = $this->findIriBy(Region::class, ['nameSlug' => 'bretagne']);

        // Test not authorized
        $client->request('GET', $iri);
        $this->assertResponseStatusCodeSame(401);

        // Test authorized
        $response = static::createClient()->request('GET', $iri, ['auth_bearer' => $this->tokenApi]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testGetJsonContains(): void
    {
        $iri = $this->findIriBy(Region::class, ['nameSlug' => 'bretagne']);
        $response = static::createClient()->request('GET', $iri, ['auth_bearer' => $this->tokenApi]);

        $this->assertJsonContains([
            'name' => 'Bretagne',
            'nameSlug' => 'bretagne',
        ]);
    }

    public function testGetCollection(): void
    {
        $response = static::createClient()->request('GET', self::BASE_URL.'/regions', ['auth_bearer' => $this->tokenApi]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }
}
