<?php
require 'classes/Companies.php';

$dbName      = 'testappsDB';
$dbPassword  = 'mysql';
$dbUser      = 'mysql';
$actionType  = strip_tags(trim($_GET['actionType']));
$resourceApiUrl = 'https://iextrading.com/api/1.0/stock/market/collection/list?collectionName=in-focus';
 
$companies = new Companies($dbName, $dbPassword, $dbUser);

if ($actionType == 'parse') {
  $allCompaniesInfo = $companies->getCompaniesWithCurl($resourceApiUrl);
  $companiesInfo    = $companies->samplingCompaniesData($allCompaniesInfo);
  $companies->insertCompaniesInDb($companiesInfo);
} else if ($actionType == 'show') {
  echo json_encode($companies->selectCompaniesFromDb());
}

?>
