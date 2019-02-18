<?php

class Companies {

  private $mysql;

  // подключение к БД
  function __construct($dbName, $dbPassword = '', $dbUser = 'root', $host = 'localhost') {

    $this->mysql = new mysqli($host, $dbUser, $dbPassword, $dbName);

    if ($this->mysql->connect_errno) {
      return "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }

  }

  // метод для получения компаний с curl
  // принимает адресс json с информацией и кастомные опции curl
  // возвращает массив std объектов с компаниями
  public function getCompaniesWithCurl($url, $customOptions = []) {

      $options = array(
                  CURLOPT_URL            => $url,
                  CURLOPT_HEADER         => false,
                  CURLOPT_RETURNTRANSFER => true,
                );

      if (!empty($customOptions)) {
        $options = $options + $customOptions;
      }

      $ch = curl_init();
      curl_setopt_array($ch, $options);
      $result = curl_exec($ch);
      curl_close($ch);

      $result = json_decode($result);

      return $result;

  }

  // принимает массив std объектов с компаниями
  // возвращает массив компаний с отсортированными данными
  public function samplingCompaniesData($companiesInfo) {

    $companiesSamplingResult = array();

    foreach ($companiesInfo as $company => $info) {
      $companiesSamplingResult[$company]['companyName'] = $info->companyName;
      $companiesSamplingResult[$company]['latestPrice'] = $info->latestPrice;
    }

    return $companiesSamplingResult;

  }

  // принимает массив компаний и добавляет их в БД
  public function insertCompaniesInDb($companies) {
    $this->mysql->query("TRUNCATE TABLE `companies`");

    foreach ($companies as $companyIndex => $companyInfo) {

      $companyName  = $companyInfo['companyName'];
      $companyPrice = $companyInfo['latestPrice'];

      $companyName  = $this->mysql->real_escape_string($companyName);
      $companyPrice = $this->mysql->real_escape_string($companyPrice);

      if (!$this->mysql->query("INSERT INTO `companies` (`company_name`, `company_price`) VALUES ('$companyName', '$companyPrice')")) {
          return "Не удалось занести данные в таблицу (" . $this->mysql->errno . ") " . $this->mysql->error;
      }

    }

    return true;
  }

  // выбирает все данные из БД
  public function selectCompaniesFromDb() {
    $query     = "SELECT * FROM `companies`";
    $companies = array();

    if (!$selectResult = $this->mysql->query($query)) {
      return "Не удалось выбрать данные из таблицы (" . $this->mysql->errno . ") " . $this->mysql->error;
    }

    while ($row = $selectResult->fetch_assoc()) {
      $companies[] = $row;
    }

    return $companies;
  }


}


?>
