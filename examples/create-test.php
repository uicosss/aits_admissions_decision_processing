<?php

/**
 * POST example
 *
 * @author Dan Paz-Horta, Jeremy Jones
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Uicosss\AITS\AdmissionsDecisionProcessing;

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    // ID
    echo 'Student ID (UIN): ';
    $studentId = trim(fgets(STDIN));

    // Term Code
    echo 'Term Code: ';
    $termCode = trim(fgets(STDIN));

    // Application Number
    echo 'Application Number: ';
    $applNo = trim(fgets(STDIN));

    // Decision Code
    echo 'Decision Code: ';
    $decisionCode = trim(fgets(STDIN));

    $apiUrl = trim($_ENV['AITS_AZURE_ADMISSIONS_DECISION_PROCESSING_API_URL']);
    $subscriptionKey = trim($_ENV['AITS_SUBSCRIPTION_KEY']);

    $admissionsDecision = new AdmissionsDecisionProcessing($apiUrl, $subscriptionKey);
    $admissionsDecisionResponse = $admissionsDecision->create($studentId, $termCode, $applNo, $decisionCode);

    // Get the results of a call
    if ($admissionsDecisionResponse->isSuccess()) {
        echo 'Success' . PHP_EOL;
    } else {
        foreach ($admissionsDecisionResponse->getResponseErrors() as $error) {
            echo $error . PHP_EOL;
        }
    }

    // Get the raw response
    echo $admissionsDecisionResponse->getResponseBody(true) . PHP_EOL;

    echo PHP_EOL;

} catch (Exception $e) {
    echo 'Exception: ';
    print_r($e->getMessage());
    echo PHP_EOL;
    echo PHP_EOL;

}