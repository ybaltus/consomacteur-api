<?php

namespace App\Tests\Endpoints;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\EnergyConsumption;
use App\Tests\Trait\AppTestTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class EnergyConsumptionTest extends ApiTestCase implements EndpointTestInterface
{
    use AppTestTrait;

    /**
     * @before
     */
    public function testGet(): void
    {
        $client = self::createClient();

        $this->initTokenAPI($client, self::USER_CREDENTIALS[0], self::USER_CREDENTIALS[1]);

        $iri = $this->findIriBy(EnergyConsumption::class, []);

        // Test not authorized
        $client->request('GET', $iri);
        $this->assertResponseStatusCodeSame(401);

        // Test authorized
        $iri = $this->findIriBy(EnergyConsumption::class, []);
        $response = static::createClient()->request('GET', $iri, ['auth_bearer' => $this->tokenApi]);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testGetJsonContains(): void
    {
        // Get request is disabled
        $this->assertTrue(true);
    }

    public function testGetCollection(): void
    {
        $response = static::createClient()->request('GET', self::BASE_URL.'/energy_consumptions', ['auth_bearer' => $this->tokenApi]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }


    public function testGetCollectionWithEnergytypeFilter(): void
    {
        $response = static::createClient()->request('GET', self::BASE_URL.'/energy_consumptions?energyType[nameSlug]=electrique', ['auth_bearer' => $this->tokenApi]);
        $response = json_decode($response->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertGreaterThanOrEqual(1, $response['hydra:totalItems']);
    }

    public function testGetCollectionWithRegionFilter(): void
    {
        $response = static::createClient()->request('GET', self::BASE_URL.'/energy_consumptions?region[nameSlug]=bretagne', ['auth_bearer' => $this->tokenApi]);
        $response = json_decode($response->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertGreaterThanOrEqual(1, $response['hydra:totalItems']);
    }

}
