<?php

namespace App\Tests\Endpoints;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\OpenDataRaw;
use App\Tests\Trait\AppTestTrait;
use Symfony\Component\HttpFoundation\Response;

class OpenDataRawTest extends ApiTestCase implements EndpointTestInterface
{
    use AppTestTrait;

    /**
     * @before
     */
    public function testGet(): void
    {
        $client = self::createClient();

        $this->initTokenAPI($client, self::USER_CREDENTIALS[0], self::USER_CREDENTIALS[1]);

        $iri = $this->findIriBy(OpenDataRaw::class, []);
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
        $response = static::createClient()->request('GET', self::BASE_URL.'/open_data_raws', ['auth_bearer' => $this->tokenApi]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testGetCollectionWithRegionFilter(): void
    {
        $response = static::createClient()->request('GET', self::BASE_URL.'/open_data_raws?region=Bret', ['auth_bearer' => $this->tokenApi]);
        $response = json_decode($response->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertGreaterThanOrEqual(1, $response['hydra:totalItems']);
    }

    public function testGetCollectionWithCodeInseeFilter(): void
    {
        $response = static::createClient()->request('GET', self::BASE_URL.'/open_data_raws?code_insee=1', ['auth_bearer' => $this->tokenApi]);
        $response = json_decode($response->getContent(), true);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertGreaterThanOrEqual(1, $response['hydra:totalItems']);
    }

}
