<?php

namespace App\Tests\Trait;

use ApiPlatform\Symfony\Bundle\Test\Client;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

trait AppTestTrait
{
    protected string $tokenApi = '';

    public function initTokenAPI(Client $client, string $username, string $password): void
    {
        if (!$this->tokenApi) {
            // retrieve a token
            $response = $client->request('POST', '/api/login_check', [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    'username' => $username,
                    'password' => $password,
                ],
            ]);

            $json = $response->toArray();
            $this->tokenApi = $json['token'];
        }
    }

    /**
     * Check the violations with the Validator Component.
     *
     * @return array<ConstraintViolationListInterface|string> [0: violations, 1: message]
     */
    public function assertViolationsWithValidator(ValidatorInterface $validator, object $entity): array
    {
        $violations = $validator->validate($entity);

        $errorMessages = [];
        /*
         * @var ConstraintViolation $error
         */
        foreach ($violations as $violation) {
            $errorMessages[] = $violation->getPropertyPath().' - '.$violation->getMessage();
        }

        return [$violations, implode(', ', $errorMessages)];
    }
}
