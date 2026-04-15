<?php

/**
 * University of Illinois - AITS Admissions Decision Processing
 * API Wrapper
 *
 * Will handle all calls to Banner API endpoints. Currently:
 * - POST create-decision
 *
 * @author Jeremy Jones
 * @license MIT
 */

namespace Uicosss\AITS;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

class AdmissionsDecisionProcessing
{
    const BANNER_ENVIRONMENTS = [
        'BANUSER',
        'BANQA',
        'BANDEV'
    ];

    /**
     * @var string
     */
    private string $apiUrl;

    /**
     * @var string
     */
    private string $subscriptionKey;

    /**
     * Sets the two necessary variables for the AITS API call to operate successfully
     *
     * @param string $baseUrl AITS API URL without leading "https:" or trailing "/"
     * @param string $subscriptionKey AITS Subscription Key pulled from the necessary profile
     * @throws Exception
     */
    public function __construct(string $baseUrl, string $subscriptionKey)
    {
        $this->setApiUrl($baseUrl);
        $this->setSubscriptionKey($subscriptionKey);
    }

    /**
     * @param mixed $studentId
     * @param mixed $termCode
     * @param mixed $applicationNumber
     * @param mixed $decisionCode
     * @param string|null $bannerEnvironment
     * @return AdmissionsDecisionResponse
     * @throws GuzzleException
     * @throws Exception
     */
    public function create(mixed $studentId, mixed $termCode, mixed $applicationNumber, mixed $decisionCode, ?string $bannerEnvironment = null): AdmissionsDecisionResponse
    {
        if (empty($studentId) || !is_numeric($studentId)) {
            throw new Exception('ID cannot be empty or non numeric');
        }

        if (empty($termCode) || !is_numeric($termCode)) {
            throw new Exception('Term code cannot be empty or non numeric');
        }

        if (empty($applicationNumber) || !is_numeric($applicationNumber)) {
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
            'ownerId' => (string) $studentId,
            'term' => [
                'code' => (string) $termCode
            ],
            'applicationNumber' => (string) $applicationNumber,
            'decision' => [
                'code' => (string) $decisionCode
            ],
        ]);

        $apiFullUrl = $this->apiUrl . 'create-decision' . $this->buildEnvironmentParameter($bannerEnvironment);

        return $this->sendRequest('POST', $apiFullUrl, $requestHeaders, $requestBody);
    }

    /**
     * Will determine if the given Banner environment is valid and return the query parameter or empty string.
     * @param $bannerEnvironment
     * @return string
     */
    private function buildEnvironmentParameter($bannerEnvironment): string
    {
        return in_array($bannerEnvironment, self::BANNER_ENVIRONMENTS) ? '?env=' . $bannerEnvironment : '';
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $headers
     * @param string $jsonBody
     * @return AdmissionsDecisionResponse
     * @throws GuzzleException
     * @throws Exception
     */
    private function sendRequest(string $method, string $url, array $headers, string $jsonBody): AdmissionsDecisionResponse
    {
        try {
            $client = new Client();
            $request = new Request($method, $url, $headers, $jsonBody);
            $response = $client->send($request);

            return new AdmissionsDecisionResponse($response);
        } catch (ClientException|Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
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
