<?php

namespace Instabox;

use Instabox\Exceptions\ResponseException;
use Instabox\Exceptions\ServerException;
use Instabox\Exceptions\UnauthorizedException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class Connection
{
    private const TOKEN_URL = 'https://oauth.instabox.se/v1/token';
    private const GRAND_TYPE = 'client_credentials';

    protected Client|null $client = null;
    protected bool $isSandbox = false;
    private string $clientId;
    private string $clientSecret;
    private int $authTokenAttempts = 0;

    private string|null $token = null;

    public function __construct(string $clientId, string $clientSecret, bool $isSandbox = false)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->isSandbox = $isSandbox;
        $this->client = new Client();
    }

    public function isSandbox(): bool
    {
        return $this->isSandbox;
    }

    public function rawRequest(string $method, string $url, string $requestBody): ResponseInterface
    {
        try {
            $options = [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getBearerToken(),
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'Instabox Client v1.0'
                ],
                'body' => $requestBody
            ];

            var_dump( $requestBody);
            $response = $this->client->request($method, $url, $options);
        } catch (ConnectException $exception) {
            throw new ConnectException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        } catch (BadResponseException $exception) {
            $response = $exception->getResponse();
            $statusCode = $response->getStatusCode();


            $data = [];
            try {
                $data = $this->jsonDecodeBody($response);
            } catch (ResponseException $responseException) {
            }

            if ($statusCode == 401) {
                if ($this->authTokenAttempts === 0) {
                    $this->authTokenAttempts++;
                    $this->refreshBearerToken();
                    $this->rawRequest($method, $url, $requestBody);
                }

                throw new UnauthorizedException();
            } elseif (in_array($statusCode, [500, 502, 503, 503, 507])) {
                throw new ServerException();
            } elseif ($statusCode != 404) {
                throw new ResponseException();
            }
        } catch (GuzzleException $guzzleException) {
            throw new \Exception(
                "Unexpected Guzzle exception: " . $guzzleException->getMessage(),
                0,
                $guzzleException
            );
        }

        return $response;
    }

    protected function getBearerToken(): string
    {
        if (is_null($this->token)) {
            $this->refreshBearerToken();
        }

        return $this->token;
    }

    protected function refreshBearerToken(): void
    {
        $bearerResponse = $this->sendBearerTokenRequest();
        $bearerResponse = $this->jsonDecodeBody($bearerResponse);

        $this->token = $bearerResponse['token'];
    }

    protected function sendBearerTokenRequest(): ResponseInterface
    {
        try {
            $response = $this->client->request('POST', self::TOKEN_URL, [
                'form_params' => [
                    'grant_type' => self::GRAND_TYPE,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret
                ]
            ]);
        } catch (ConnectException $exception) {
            throw new ConnectException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        } catch (BadResponseException $exception) {
            $response = $exception->getResponse();
            $statusCode = $response->getStatusCode();

            if ($statusCode == 401) {
                throw new UnauthorizedException();
            } elseif (in_array($statusCode, [500, 502, 503, 503, 507])) {
                throw new ServerException();
            } elseif ($statusCode != 404) {
                throw new ResponseException();
            }
        } catch (GuzzleException $guzzleException) {
            throw new \Exception(
                "Unexpected Guzzle exception: " . $guzzleException->getMessage(),
                0,
                $guzzleException
            );
        }

        return $response;
    }

    protected function jsonDecodeBody(?ResponseInterface $response)
    {
        if ($response === null) {
            throw new ResponseException('No body received');
        }

        $data = json_decode((string)$response->getBody(), true);

        if (json_last_error() != JSON_ERROR_NONE) {
            throw new ResponseException('Body contains invalid JSON');
        }

        return $data;
    }
}
