(function() {
'use strict';

var btnParse          = document.querySelector('.btn--parse'),
    btnShow           = document.querySelector('.btn--show'),
    resultCont        = document.querySelector('.result-container'),
    tableBody         = document.querySelector('.companies-body'),
    companiesFragment = document.createDocumentFragment(), // фрагмент для формирования и вставки узлов в tbody
    actionTypeParse   = '/php/parseAndShowCompanies.php?actionType=parse',
    actionTypeShow    = '/php/parseAndShowCompanies.php?actionType=show',
    companies         = []; // массив принимающий ответ запроса actionTypeShow
 
btnParse.addEventListener('click', companiesOnClickHandlerParse);
btnShow.addEventListener('click', companiesOnClickHandlerShow);

// Обработчики событий
function companiesOnClickHandlerParse(evt) {
  var xhrParse = new XMLHttpRequest();

  xhrParse.open('GET', actionTypeParse, true);
  xhrParse.send();
}

function companiesOnClickHandlerShow(clickEvt) {
  var xhrShow = new XMLHttpRequest();

  xhrShow.addEventListener('load', dataLoadHandler);
  xhrShow.open('GET', actionTypeShow, true);
  xhrShow.responseType = 'json';
  xhrShow.send();

  function dataLoadHandler(loadEvt) {
    companies = xhrShow.response;
    showCompanies(companies);
  }
}

// создание, формироваие и вставка элементов таблицы
function showCompanies(companies) {
  tableBody.innerHTML = '';

  companies.forEach(function(company, companyIndex) {

    var row      = document.createElement('tr'),
        cellName = document.createElement('td'),
        cellId   = document.createElement('td'),
        cellPrie = document.createElement('td');

    cellId.classList.add('companies-body__id');
    cellName.classList.add('companies-body__name');
    cellPrie.classList.add('companies-body__price');

    cellId.textContent = company.company_id;
    cellName.textContent   = company.company_name;
    cellPrie.textContent = company.company_price;

    row.appendChild(cellId);
    row.appendChild(cellName);
    row.appendChild(cellPrie);

    companiesFragment.appendChild(row);

    tableBody.appendChild(companiesFragment);

  });
}

})();
