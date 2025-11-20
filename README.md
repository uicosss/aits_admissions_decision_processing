# University of Illinois
## AITS - Admissions Decision Processing

PHP library for using the AITS Admissions Decision Processing API. Contact AITS for additional implementation details.

## Usage
To use the library, you need to:

### Include library in your program
`require_once 'AitsPersonLookupConfidential.php';`

### or use composer 
```
composer require uicosss/aits_admissions_decision_processing
require_once 'vendor/autoload.php';
```

### Instantiate an object of the class
```
$apiUrl = 'apiurl.com/without/trailing/slash'; // Contact AITS for this
$subscriptionKey = 'YOUR_SUBSCRIPTION_KEY'; // Contact AITS for this
$personApi = new uicosss\AdmissionsDecisionProcessing($apiUrl, $subscriptionKey);
```

### GET student applications
Use the `get` method to pull data with the following required parameters: `$studentId` (UIN) and term `$termCode`. Can also pass an optional array of additional config option `$options` (see list below). Upon a successful response, data will be available in two properties `$saradap` and `$sarappd`, both are arrays of their respective data sets.
```
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
```
```
$admissionsDecision = new AdmissionsDecisionProcessing($apiUrl, $subscriptionKey);

if ($admissionsDecision->get($studentId, $termCode)) {
    echo 'Success' . PHP_EOL;
} else {
    echo 'Error' . PHP_EOL;
}

foreach ($admissionsDecision->saradap as $saradap) {}
foreach ($admissionsDecision->sarappd as $sarappd) {}
```

### POST application decision
Use the `post` method to send a decision to Banner with the following required parameters: `$studentId` (UIN), `$termCode`, `$applNo`, `$apdcDate`, `$apdcCode`. Can optionally set an `$env` parameter as well. Upon a successful response, data will be available in four properties `$saradap`, `$sarappd`, `$sovlcur`, and `$sovlfos`. All are arrays of their respective data sets.
```
$admissionsDecision = new AdmissionsDecisionProcessing($apiUrl, $subscriptionKey);

if ($admissionsDecision->post($studentId, $termCode, $applNo, $apdcDate, $apdcCode)) {
    echo 'Success' . PHP_EOL;
} else {
    echo 'Error' . PHP_EOL;
}

foreach ($admissionsDecision->saradap as $saradap) {}
foreach ($admissionsDecision->sarappd as $sarappd) {}
foreach ($admissionsDecision->sovlcur as $sovlcur) {}
foreach ($admissionsDecision->sovlfos as $sovlfos) {}
```


## Examples:
You can use the attached scripts in `examples/` file from the command line to test functionality.

`php get-test.php apiurl.com/without/trailing/slash YOUR_SUBSCRIPTION_KEY studentId termCode`

`php post-test.php apiurl.com/without/trailing/slash YOUR_SUBSCRIPTION_KEY studentId termCode applNo apdcDate apdcCode`