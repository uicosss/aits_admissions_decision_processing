<?php

/**
 * University of Illinois - AITS Admissions Decision Processing
 * API Wrapper
 *
 * @author Jeremy Jones
 * @license MIT
 */

namespace Uicosss\AITS;

use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;

class AdmissionsDecisionProcessing
{
    /**
     * @var string
     */
    private string $apiUrl;

    /**
     * @var string
     */
    private string $subscriptionKey;

    /**
     * @var string|null
     */
    private ?string $rawBody;

    /**
     * @var mixed
     */
    private mixed $jsonBody;

    /**
     * @var int
     */
    private int $httpCode = 500;

    /**
     * @var array
     */
    private array $errors = [];

    /**
     * Sets the two necessary variables for the AITS API call to operate successfully
     *
     * @param string $apiUrl AITS API URL without leading "https:" or trailing "/"
     * @param string $subscriptionKey AITS Subscription Key pulled from the necessary profile
     * @throws Exception
     */
    public function __construct(string $apiUrl, string $subscriptionKey)
    {
        $this->setApiUrl($apiUrl);
        $this->setSubscriptionKey($subscriptionKey);
    }

    /**
     * @param mixed $studentId
     * @param mixed $termCode
     * @param mixed $applNo
     * @param mixed $decisionCode
     * @param $env
     * @return mixed
     * @throws Exception
     */
    public function post(mixed $studentId, mixed $termCode, mixed $applNo, mixed $decisionCode, $env = null): mixed
    {
        try {
            if (empty($studentId) || !is_numeric($studentId)) {
                throw new Exception('ID cannot be empty or non numeric');
            }

            if (empty($termCode) || !is_numeric($termCode)) {
                throw new Exception('Term code cannot be empty or non numeric');
            }

            if (empty($applNo) || !is_numeric($applNo)) {
                throw new Exception('Application number cannot be empty or non numeric');
            }

            if (empty($decisionCode) || !is_numeric($decisionCode)) {
                throw new Exception('Decision code cannot be empty or non numeric');
            }

            $requestHeaders = [
                'Cache-Control' => 'no-cache',
                'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                'Content-Type' => 'application/json',
            ];

            $requestBody = json_encode([
                'ownerId' => $studentId,
                'term' => [
                    'code' => $termCode
                ],
                'applicationNumber' => $applNo,
                'decision' => [
                    'code' => $decisionCode
                ],
            ]);

            $apiFullUrl = $this->apiUrl;

            if ($env !== null) {
                $apiFullUrl .= '?env=' . $env;
            }

            $client = new Client();
            $request = new Request('POST', $apiFullUrl, $requestHeaders, $requestBody);
            $response = $client->send($request);

            $this->httpCode = $response->getStatusCode();
            $this->rawBody = $response->getBody();
            $this->jsonBody = json_decode($response->getBody());

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('AITS API response was not valid JSON');
            }

            if ($this->httpCode !== 200) {
                $this->errors = !empty($this->jsonBody->errors) ? $this->jsonBody->errors : [];
                throw new Exception('AITS API response code: ' . $this->httpCode . '. Check errors for more details.');
            }

            return $this->jsonBody;

        } catch (ClientException $e) {
            $this->httpCode = $e->getCode();
            $json = json_decode($e->getResponse()->getBody());
            $error = $json->errors[0]->message . ' ' . $json->errors[0]->description;
            throw new Exception(json_last_error() == JSON_ERROR_NONE ? $error : 'An error as occurred');
        } catch (ServerException|BadResponseException|GuzzleException|Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param bool $raw Boolean flag for whether to return raw JSON string or decoded JSON array
     * @return mixed Will return the JSON string or decoded JSON array
     */
    public function getResponseBody(bool $raw = false): mixed
    {
        return ($raw) ? $this->rawBody : $this->jsonBody;
    }

    /**
     * @return int
     */
    public function getHttpResponseCode(): int
    {
        return $this->httpCode;
    }

    /**
     * @return array
     */
    public function getResponseErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param string $apiUrl AITS API URL with protocol, trailing slash optional
     * @throws Exception
     */
    private function setApiUrl(string $apiUrl): void
    {
        if (empty($apiUrl)) {
            throw new Exception("The apiUrl cannot be blank. Please contact AITS for the Azure Gateway API URLs.");
        }

        $trimmedApiUrl = trim($apiUrl);

        $this->apiUrl = (str_ends_with($trimmedApiUrl, '/')) ? $trimmedApiUrl : $trimmedApiUrl . '/';
    }

    /**
     * @param string $subscriptionKey AITS Subscription Key pulled from the necessary profile
     * @throws Exception
     */
    private function setSubscriptionKey(string $subscriptionKey): void
    {
        if (empty($subscriptionKey)) {
            throw new Exception("The subscriptionKey cannot be blank. Refer to the Azure Gateway API profile Subscription Keys.");
        }

        $this->subscriptionKey = trim($subscriptionKey);
    }
}
