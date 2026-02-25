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
     * @param mixed $applNo
     * @param mixed $decisionCode
     * @param $env
     * @return mixed
     * @throws Exception
     */
    public function create(mixed $studentId, mixed $termCode, mixed $applNo, mixed $decisionCode, $env = null): mixed
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

            $apiFullUrl = $this->apiUrl . 'create-decision' . ($env !== null ? '?env=' . $env : '');

            return $this->sendRequest('POST', $apiFullUrl, $requestHeaders, $requestBody);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param $method
     * @param $url
     * @param $headers
     * @param $jsonBody
     * @return AdmissionsDecisionResponse
     * @throws Exception
     */
    private function sendRequest($method, $url, $headers, $jsonBody): AdmissionsDecisionResponse
    {
        try {
            $client = new Client();
            $request = new Request($method, $url, $headers, $jsonBody);
            $response = $client->send($request);

            return new AdmissionsDecisionResponse($response);
        } catch (ClientException $e) {
            if ($e->hasResponse()) {
                $json = json_decode($e->getResponse()->getBody());
                $error = json_last_error() === JSON_ERROR_NONE ? $json->errors[0]->message . ' ' . $json->errors[0]->description : 'An error as occurred';
            } else {
                $error = $e->getMessage();
            }

            throw new Exception($error);
        } catch (ServerException|BadResponseException|GuzzleException|Exception $e) {
            throw new Exception($e->getMessage());
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
