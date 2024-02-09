<?php

namespace App\Tests\Endpoints;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\EnergyConsumption;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class EnergyConsumptionTest extends ApiTestCase implements EndpointTestInterface
{
    public function testGet(): void
    {
        $iri = $this->findIriBy(EnergyConsumption::class, []);
        $response = static::createClient()->request('GET', $iri);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testGetJsonContains(): void
    {
        // Get request is disabled
        $this->assertTrue(true);
    }

    public function testGetCollection(): void
    {
        $response = static::createClient()->request('GET', self::BASE_URL.'/energy_consumptions');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }


    public function testGetCollectionWithEnergytypeFilter(): void
    {
        $response = static::createClient()->request('GET', self::BASE_URL.'/energy_consumptions?energyType[nameSlug]=electrique');
        $response = json_decode($response->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertGreaterThanOrEqual(1, $response['hydra:totalItems']);
    }

    public function testGetCollectionWithRegionFilter(): void
    {
        $response = static::createClient()->request('GET', self::BASE_URL.'/energy_consumptions?region[nameSlug]=bretagne');
        $response = json_decode($response->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertGreaterThanOrEqual(1, $response['hydra:totalItems']);
    }

}
