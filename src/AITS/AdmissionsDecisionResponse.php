<?php

/**
 * University of Illinois - AITS Admissions Decision Processing
 * API Wrapper
 *
 * Response class for any API calls made to the Banner API endpoints.
 *
 * @author Jeremy Jones
 * @license MIT
 */

namespace Uicosss\AITS;

use Psr\Http\Message\ResponseInterface;

class AdmissionsDecisionResponse
{
    /**
     * @var bool
     */
    private bool $success = false;

    /**
     * @var string
     */
    private string $rawBody;

    /**
     * @var mixed
     */
    private mixed $jsonBody = null;

    /**
     * @var int
     */
    private int $responseCode = 500;

    /**
     * @var array
     */
    private array $errors = [];

    /**
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->setResponseCode($response->getStatusCode());
        $this->setBody((string) $response->getBody());

        $json = json_decode($this->rawBody);

        if (json_last_error() === JSON_ERROR_NONE) {
            $this->setJson($json);
        } else {
            $this->setError(json_last_error_msg());

            if (!empty($json->errors)) {
                foreach ($json->errors as $error) {
                    $this->setError($error);
                }
            }
        }

        // Successful calls determined by correct status code and absence of errors
        if (($this->responseCode === 200 || $this->responseCode === 201) && empty($this->errors)) {
            $this->success = true;
        }
    }

    /**
     * @param bool $raw Boolean flag to return the raw response body, or the JSON encoded body
     * @return mixed
     */
    public function getResponseBody(bool $raw = false): mixed
    {
        return ($raw) ? $this->rawBody : $this->jsonBody;
    }

    /**
     * @return int
     */
    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    /**
     * @return array
     */
    public function getResponseErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @param string $body
     * @return void
     */
    private function setBody(string $body): void
    {
        $this->rawBody = $body;
    }

    /**
     * @param $json
     * @return void
     */
    private function setJson($json): void
    {
        $this->jsonBody = $json;
    }

    /**
     * @param string $error
     * @return void
     */
    private function setError(string $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @param int $responseCode
     * @return void
     */
    private function setResponseCode(int $responseCode): void
    {
        $this->responseCode = $responseCode;
    }
}