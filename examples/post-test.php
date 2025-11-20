<?php

/**
 * POST example
 *
 * @author Dan Paz-Horta, Jeremy Jones
 */

require_once __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Exception\GuzzleException;
use Uicosss\AITS\AdmissionsDecisionProcessing;

try {
    print_r($argv);
    echo PHP_EOL;

    // ID
    if (empty($argv[3])) {
        throw new Exception("Error: Specify UIN as the 3rd argument.");
    }

    // Term Code
    if (empty($argv[4])) {
        throw new Exception("Error: Specify Term Code as the 4th argument.");
    }

    // Application Number
    if (empty($argv[5])) {
        throw new Exception("Error: Specify Application Number as the 5th argument.");
    }

    // Decision Date
    if (empty($argv[5])) {
        throw new Exception("Error: Specify Decision Date as the 6th argument.");
    }

    // Decision Code
    if (empty($argv[6])) {
        throw new Exception("Error: Specify Decision Code as the 7th argument.");
    }

    // API URL
    if (empty($argv[1])) {
        throw new Exception("Error: Specify API URL as the 1st argument.");
    }

    // Subscription Key from Azure Gateway API
    if (empty($argv[2])) {
        throw new Exception("Error: Specify Subscription Key from AITS Azure API as the 2nd argument.");
    }

    $apiUrl = trim($argv[1]);
    $subscriptionKey = trim($argv[2]);

    $studentId = trim($argv[3]);
    $termCode = trim($argv[4]);
    $applNo = trim($argv[5]);
    $decisionDate = trim($argv[6]);
    $decisionCode = trim($argv[7]);

    $admissionsDecision = new AdmissionsDecisionProcessing($apiUrl, $subscriptionKey);

    // Get the results of a call
    if ($admissionsDecision->post($studentId, $termCode, $applNo, $decisionDate, $decisionCode)) {
        echo 'Success' . PHP_EOL;
    } else {
        echo 'Error' . PHP_EOL;
    }

    echo "HTTP Code: [" . $admissionsDecision->getHttpResponseCode() . "]" . PHP_EOL;

    echo PHP_EOL;

    // Get the raw response
    echo $admissionsDecision->getResponse(true) . PHP_EOL;

    echo PHP_EOL;

} catch (GuzzleException|Exception $e) {
    echo 'Exception: ';
    print_r($e->getMessage());
    echo PHP_EOL;
    echo PHP_EOL;

}