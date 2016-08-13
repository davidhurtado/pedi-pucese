<?php

use tests\codeception\frontend\AcceptanceTester;
use tests\codeception\frontend\_pages\EstrategiasPage;
use tests\codeception\frontend\_pages\LoginPage;

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

$I->wantTo('ensure that estrategias works');
if (method_exists($I, 'wait')) {
    $I->wait(2); // only for selenium
}
$I->click('/html/body/div/nav/div/div[2]/ul/li[2]/a'); // click en ADMINISTRACION
if (method_exists($I, 'wait')) {
    $I->wait(1); // only for selenium
}
$I->click('/html/body/div/nav/div/div[2]/ul/li[2]/ul/li[1]/a'); // click en OBJETIVOS
if (method_exists($I, 'wait')) {
    $I->wait(1); // only for selenium
}
/* ------------- CLICK EN 1 OBJETIVO --------------- */
$I->click('/html/body/div[1]/div/div/div[2]/div[3]/table/tbody/tr[1]/td[6]/a[1]/span');
//$I->click('/html/body/div[1]/div/div/div[2]/div[3]/table/tbody/tr[3]/td[6]/a[1]/span');
if (method_exists($I, 'wait')) {
    $I->wait(1); // only for selenium
}
/* ------------- ABRIR MODAL CREAR ESTRATEGIA --------------- */
$I->click('button[id="agregar_estrategias"]');

/* -------------CREAR EESTRATEGIAS --------------- */
if (method_exists($I, 'wait')) {
    $I->wait(1); // only for selenium
}
$estrategiasData=['Acceptacion ESTRATEGIA 1', '2016-09-01', '2019-07-03', 5000];
$I->fillField('input[name="Estrategias[descripcion]"]', $estrategiasData[0]);
$I->click('ul[class="select2-selection__rendered"]');
$I->click('/html/body/span/span/span[1]/span[1]'); //selecciona todo
$I->fillField('input[name="Estrategias[fecha_inicio]"]', $estrategiasData[1]);
$I->fillField('input[name="Estrategias[fecha_fin]"]', $estrategiasData[2]);
$I->fillField('input[name="Estrategias[presupuesto]"]', $estrategiasData[3]);
$I->click('/html/body/div[1]/div/div/div[3]/div/div/div/div/div/form/div[5]/button'); //click en crear

if (method_exists($I, 'wait')) {
    $I->wait(3); // only for selenium
}

$I->wantTo('ensure that estrategias works');

$estrategiasPage = EstrategiasPage::openBy($I);

$I->see('Estrategias', 'h1');

if (method_exists($I, 'wait')) {
    $I->wait(4); // only for selenium
}
