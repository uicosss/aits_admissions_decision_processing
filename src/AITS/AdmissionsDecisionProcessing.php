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
     * @var Saradap|null
     */
    public ?Saradap $saradap = null;

    /**
     * @var Sarappd|null
     */
    public ?Sarappd $sarappd = null;

    /**
     * @var Sovlcur|null
     */
    public ?Sovlcur $sovlcur = null;

    /**
     * @var Sovlfos|null
     */
    public ?Sovlfos $sovlfos = null;
    
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

            $this->saradap = new Saradap();
            $this->saradap->setReqDocInd($this->json[0]->SARADAP[0]->reqDocInd);
            $this->saradap->setAdmtCode($this->json[0]->SARADAP[0]->admtCode);
            $this->saradap->setApplDate($this->json[0]->SARADAP[0]->applDate);
            $this->saradap->setApplNo($this->json[0]->SARADAP[0]->applNo);
            $this->saradap->setApstCode($this->json[0]->SARADAP[0]->apstCode);
            $this->saradap->setResdCode($this->json[0]->SARADAP[0]->resdCode);
            $this->saradap->setStypCode($this->json[0]->SARADAP[0]->stypCode);
            $this->saradap->setTermCodeEntry($this->json[0]->SARADAP[0]->termCodeEntry);
            $this->saradap->setSarappdApdcCode($this->json[0]->SARADAP[0]->sarappdApdcCode);
            $this->saradap->setStvadmtDesc($this->json[0]->SARADAP[0]->stvadmtDesc);
            $this->saradap->setStvapdcDesc($this->json[0]->SARADAP[0]->stvapdcDesc);
            $this->saradap->setStvapstDesc($this->json[0]->SARADAP[0]->stvapstDesc);
            $this->saradap->setStvresdDesc($this->json[0]->SARADAP[0]->stvresdDesc);
            $this->saradap->setStvstypDesc($this->json[0]->SARADAP[0]->stvstypDesc);

            $this->sarappd = new Sarappd();
            $this->sarappd->setMaintDesc($this->json[0]->SARAPPD[0]->maintDesc);
            $this->sarappd->setApdcCode($this->json[0]->SARAPPD[0]->apdcCode);
            $this->sarappd->setApdcDate($this->json[0]->SARAPPD[0]->apdcDate);
            $this->sarappd->setMaintInd($this->json[0]->SARAPPD[0]->maintInd);
            $this->sarappd->setUser($this->json[0]->SARAPPD[0]->user);
            $this->sarappd->setStvapdcApplInact($this->json[0]->SARAPPD[0]->stvapdcApplInact);
            $this->sarappd->setStvapdcDesc($this->json[0]->SARAPPD[0]->stvapdcDesc);
            $this->sarappd->setStvapdcRejectInd($this->json[0]->SARAPPD[0]->stvapdcRejectInd);
            $this->sarappd->setStvapdcSignfInd($this->json[0]->SARAPPD[0]->stvapdcSignfInd);

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
            if (!empty($apdcDate) || !$apdcDateObj instanceof Carbon) {
                throw new Exception('Decision date must be a valid datetime string');
            }

            if (empty($apdcCode) || !is_numeric($apdcCode)) {
                throw new Exception('Decision code cannot be empty or non numeric');
            }

            $requestHeaders = [
                'Cache-Control' => 'no-cache',
                'Ocp-Apim-Subscription-Key' => $this->subscriptionKey
            ];

            $requestBody = [
                'json' => [
                    'id' => $studentId,
                    'keyblocTermCode' => $termCode,
                    'applNo' => $applNo,
                    'apdcDate' => $apdcDateObj->format('Y-m-d'),
                    'apdcCode' => $apdcCode,
                ]
            ];

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

            $this->saradap = Saradap::buildFromJson($this->json[0]->SARADAP[0]);
            $this->sarappd = Sarappd::buildFromJson($this->json[0]->SARAPPD[0]);
            $this->sovlcur = Sovlcur::buildFromJson($this->json[0]->SOVLCUR[0]);
            $this->sovlfos = Sovlfos::buildFromJson($this->json[0]->SOVLFOS[0]);

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
     * @return Saradap|null
     */
    public function getSaradap(): ?Saradap
    {
        return $this->saradap;
    }

    /**
     * @return Sarappd|null
     */
    public function getSarappd(): ?Sarappd
    {
        return $this->sarappd;
    }

    /**
     * @return Sovlcur|null
     */
    public function getSovlcur(): ?Sovlcur
    {
        return $this->sovlcur;
    }

    /**
     * @return Sovlfos|null
     */
    public function getSovlfos(): ?Sovlfos
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
