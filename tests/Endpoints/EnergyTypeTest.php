<?php

namespace App\Tests\Endpoints;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\EnergyType;
use App\Tests\Trait\AppTestTrait;

class EnergyTypeTest extends ApiTestCase implements EndpointTestInterface
{
    use AppTestTrait;

    /**
     * @before
     */
    public function testGet(): void
    {
        $client = self::createClient();

        $this->initTokenAPI($client, self::USER_CREDENTIALS[0], self::USER_CREDENTIALS[1]);

        // Test not authorized
        $iri = $this->findIriBy(EnergyType::class, ['nameSlug' => 'electrique']);
        $response = $client->request('GET', $iri);
        $this->assertResponseStatusCodeSame(401);

        // Test authorized
        $response = static::createClient()->request('GET', $iri, ['auth_bearer' => $this->tokenApi]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testGetJsonContains(): void
    {
        $iri = $this->findIriBy(EnergyType::class, ['nameSlug' => 'electrique']);
        $response = static::createClient()->request('GET', $iri, ['auth_bearer' => $this->tokenApi]);

        $this->assertJsonContains([
            'name' => 'Électrique',
            'nameSlug' => 'electrique',
        ]);
    }

    public function testGetCollection(): void
    {
        $response = static::createClient()->request('GET', self::BASE_URL.'/energy_types', ['auth_bearer' => $this->tokenApi]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }


}
