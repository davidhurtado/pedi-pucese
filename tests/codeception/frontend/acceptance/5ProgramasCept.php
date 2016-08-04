<?php

use tests\codeception\frontend\AcceptanceTester;
use tests\codeception\frontend\_pages\LoginPage;
use tests\codeception\frontend\_pages\ProgramasPage;

/* @var $scenario Codeception\Scenario */

$I = new AcceptanceTester($scenario);

/* ------------- LOGIN --------------- */
$I->wantTo('ensure login page works');
$I->amOnPage(Yii::$app->homeUrl);

$I->see('PEDI');
$I->seeLink('Login');
$I->seeLink('Signup');
if (method_exists($I, 'wait')) {
    $I->wait(1); // only for selenium
}
$loginPage = LoginPage::openBy($I);
$I->amGoingTo('submit login form with no data');

$loginPage->login('erau', 'password_0');
if (method_exists($I, 'wait')) {
    $I->wait(0.5); // only for selenium
}
$I->expectTo('see that user is logged');

/* ------------- OBJETIVOS --------------- */

$I->wantTo('ensure that objetivos works');
if (method_exists($I, 'wait')) {
    $I->wait(2); // only for selenium
}
$I->click('/html/body/div/nav/div/div[2]/ul/li[2]/a'); // click en ADMINISTRACION
if (method_exists($I, 'wait')) {
    $I->wait(1); // only for selenium
}
$I->click('/html/body/div/nav/div/div[2]/ul/li[2]/ul/li[1]/a'); // click en OBJETIVOS
if (method_exists($I, 'wait')) {
    $I->wait(3); // only for selenium
}
/* ------------- CLICK EN 1 OBJETIVO --------------- */
$I->click('/html/body/div[1]/div/div/div[2]/div[3]/table/tbody/tr[1]/td[6]/a[1]/span');
//$I->click('/html/body/div[1]/div/div/div[2]/div[3]/table/tbody/tr[3]/td[6]/a[1]/span');
if (method_exists($I, 'wait')) {
    $I->wait(3); // only for selenium
}
/* ------------- ABRIR UNA ESTRATEGIA --------------- */
$I->click('/html/body/div/div/div/div[2]/table/tbody/tr/td[3]/a[1]/span');
if (method_exists($I, 'wait')) {
    $I->wait(3); // only for selenium
}
/* ------------- ABRIR MODAL CREAR PROGRAMAS --------------- */
$I->click('button[id="crear_programa_modal"]');
if (method_exists($I, 'wait')) {
    $I->wait(1); // only for selenium
}
$programasData = ['Acceptacion PROGRAMA 1', '2016-09-01', '2019-07-03', 5000];
$I->fillField('input[name="Programas[descripcion]"]', $programasData[0]);
$I->click('ul[class="select2-selection__rendered"]');
$I->click('/html/body/span/span/span[1]/span[1]'); //selecciona todo
//$I->fillField('input[name="Programas[fecha_inicio]"]', $programasData[1]);
$I->click('//*[@id="programas-fecha_inicio"]'); //
$I->click('/html/body/div[3]/div[5]/table/tbody/tr/td/span[8]');
$I->click('/html/body/div[3]/div[4]/table/tbody/tr/td/span[8]');
$I->click('/html/body/div[3]/div[3]/table/tbody/tr[3]/td[7]');
$I->fillField('input[name="Programas[fecha_fin]"]', $programasData[2]);
$I->fillField('input[name="Programas[presupuesto]"]', $programasData[3]);

$I->click('button[id="crear_programa"]'); //click en crear

if (method_exists($I, 'wait')) {
    $I->wait(3); // only for selenium
}
/* -------------VER PROGRAMAS --------------- */
$I->wantTo('ensure that programas works');
$programasPage = ProgramasPage::openBy($I);

$I->see('Programas', 'h1');


if (method_exists($I, 'wait')) {
    $I->wait(5); // only for selenium
}
