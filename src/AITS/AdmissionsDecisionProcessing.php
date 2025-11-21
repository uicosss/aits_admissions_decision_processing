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
     * @var Saradap[]
     */
    public array $saradap = [];

    /**
     * @var Sarappd[]
     */
    public array $sarappd = [];

    /**
     * @var Sovlcur[]
     */
    public array $sovlcur = [];

    /**
     * @var Sovlfos[]
     */
    public array $sovlfos = [];
    
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
    private ?string $raw;

    /**
     * @var mixed
     */
    private mixed $json;

    /**
     * @var int
     */
    private int $httpCode = 500;

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
     * @param array $options
     * @return bool
     * @throws Exception
     */
    public function get(mixed $studentId, mixed $termCode, array $options = []): bool
    {
        try {
            if (empty($studentId) || !is_numeric($studentId)) {
                throw new Exception('ID cannot be empty or non numeric');
            }

            if (empty($termCode) || !is_numeric($termCode)) {
                throw new Exception('Term code cannot be empty or non numeric');
            }

            $apiFullUrl = $this->apiUrl . 'query?id=' . $studentId . '&keyblocTermCode=' . $termCode;

            $allowedOptions = [
                'limit',
                'offset',
                'criteria',
                'apdcCode',
                'termCodeEntry',
                'sarappdApdcCode',
                'apstCode',
                'applDate',
                'applNo',
                'admtCode',
                'sessCode',
                'reqDocInd',
                'applPreference',
                'stypCode',
                'resdCode',
                'fullPartInd',
                'env',
            ];

            foreach ($options as $key => $value) {
                if (in_array($key, $allowedOptions)) {
                    $apiFullUrl .= sprintf('&%s=%s', $key, $value);
                }
            }

            $requestHeaders = [
                'Cache-Control' => 'no-cache',
                'Ocp-Apim-Subscription-Key' => $this->subscriptionKey
            ];

            $client = new Client();
            $request = new Request('GET', $apiFullUrl, $requestHeaders);
            $response = $client->send($request);

            $this->httpCode = $response->getStatusCode();
            $this->raw = $response->getBody();
            $this->json = json_decode($response->getBody());

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('AITS API response was not valid JSON');
            }

            foreach ($this->json[0]->SARADAP as $saradap) {
                $this->saradap[] = Saradap::buildFromJson($saradap);
            }

            foreach ($this->json[0]->SARAPPD as $sarappd) {
                $this->sarappd[] = Sarappd::buildFromJson($sarappd);
            }

            return $this->httpCode === 200;

        } catch (ClientException $e) {
            $this->httpCode = $e->getCode();
            $json = json_decode($e->getResponse()->getBody());
            $error = $json->errors[0]->message . ' ' . $json->errors[0]->description;
            throw new Exception(json_last_error() == JSON_ERROR_NONE ? $error : 'Error');
        } catch (ServerException|BadResponseException|GuzzleException|Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param mixed $studentId
     * @param mixed $termCode
     * @param mixed $applNo
     * @param mixed $apdcDate
     * @param mixed $apdcCode
     * @param $env
     * @return bool
     * @throws Exception
     */
    public function post(mixed $studentId, mixed $termCode, mixed $applNo, mixed $apdcDate, mixed $apdcCode, $env = null): bool
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

            $apdcDateObj = Carbon::parse($apdcDate);
            if (empty($apdcDate) || !$apdcDateObj instanceof Carbon) {
                throw new Exception('Decision date must be a valid datetime string');
            }

            if (empty($apdcCode) || !is_numeric($apdcCode)) {
                throw new Exception('Decision code cannot be empty or non numeric');
            }

            $requestHeaders = [
                'Cache-Control' => 'no-cache',
                'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                'Content-Type' => 'application/json',
            ];

            $requestBody = json_encode([
                'id' => $studentId,
                'keyblocTermCode' => $termCode,
                'applNo' => $applNo,
                'apdcDate' => $apdcDateObj->format('Y-m-d'),
                'apdcCode' => $apdcCode,
            ]);

            $apiFullUrl = $this->apiUrl . 'create';

            if ($env !== null) {
                $apiFullUrl .= '?env=' . $env;
            }

            $client = new Client();
            $request = new Request('POST', $apiFullUrl, $requestHeaders, $requestBody);
            $response = $client->send($request);

            $this->httpCode = $response->getStatusCode();
            $this->raw = $response->getBody();
            $this->json = json_decode($response->getBody());

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('AITS API response was not valid JSON');
            }

            foreach ($this->json[0]->SARADAP as $saradap) {
                $this->saradap[] = Saradap::buildFromJson($saradap);
            }

            foreach ($this->json[0]->SARAPPD as $sarappd) {
                $this->sarappd[] = Sarappd::buildFromJson($sarappd);
            }

            foreach ($this->json[0]->SOVLCUR as $sovlcur) {
                $this->sovlcur[] = Sovlcur::buildFromJson($sovlcur);
            }

            foreach ($this->json[0]->SOVLFOS as $sovlfos) {
                $this->sovlfos[] = Sovlfos::buildFromJson($sovlfos);
            }

            return $this->httpCode === 200;

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
     * @return Saradap[]
     */
    public function getSaradap(): array
    {
        return $this->saradap;
    }

    /**
     * @return Sarappd[]
     */
    public function getSarappd(): array
    {
        return $this->sarappd;
    }

    /**
     * @return Sovlcur[]
     */
    public function getSovlcur(): array
    {
        return $this->sovlcur;
    }

    /**
     * @return Sovlfos[]
     */
    public function getSovlfos(): array
    {
        return $this->sovlfos;
    }

    /**
     * @param bool $raw Boolean flag for whether to return raw JSON string or decoded JSON array
     * @return mixed Will return the JSON string or decoded JSON array
     */
    public function getResponse(bool $raw = false): mixed
    {
        return ($raw) ? $this->raw : $this->json;
    }

    /**
     * @return int
     */
    public function getHttpResponseCode(): int
    {
        return $this->httpCode;
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
