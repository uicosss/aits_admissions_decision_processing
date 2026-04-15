# University of Illinois
## AITS - Admissions Decision Processing

PHP library for using the AITS Admissions Decision Processing API. Contact AITS for additional implementation details.

## Usage
To use the library, you need to:

### Composer 
```
composer require uicosss/aits_admissions_decision_processing
require_once 'vendor/autoload.php';
```

### Instantiate an object of the class
```
$apiUrl = 'apiurl.com/without/trailing/slash'; // Contact AITS for this
$subscriptionKey = 'YOUR_SUBSCRIPTION_KEY'; // Contact AITS for this
$admissionsDecision = new Uicosss\AITS\AdmissionsDecisionProcessing($apiUrl, $subscriptionKey);
```

### POST application decision
Use the `create` method to send a decision to Banner with the following required parameters: 
 - `$studentId` (UIN) 
 - `$termCode`
 - `$applicationNumber`
 - `$decisionCode`
 - `$bannerEnvironment` (optional, corresponds to a Banner environment)

This will return a `AdmissionsDecisionResponse` object containing the API response.
```
try {
    $admissionsDecision = new Uicosss\AITS\AdmissionsDecisionProcessing($apiUrl, $subscriptionKey);
    $admissionsDecisionResponse = $admissionsDecision->create($studentId, $termCode, $applNo, $decisionCode);
    
    if ($admissionsDecisionResponse->isSuccess()) {
        echo 'Success' . PHP_EOL;
    } else {
        echo 'Error' . PHP_EOL;
    }
} catch (GuzzleException|Exception $e) {
    print_r($e->getMessage());
}
```

## Examples:
You can use the attached scripts in `examples/` file from the command line to test functionality.

`php create-test.php apiurl.com/without/trailing/slash YOUR_SUBSCRIPTION_KEY studentId termCode applNo apdcDate apdcCode`