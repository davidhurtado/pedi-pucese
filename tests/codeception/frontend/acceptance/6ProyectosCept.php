<?php

use tests\codeception\frontend\AcceptanceTester;
use tests\codeception\frontend\_pages\LoginPage;
use tests\codeception\frontend\_pages\ProyectosPage;

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
/* ------------- ABRIR UN PROGRAMA --------------- */
$I->click('/html/body/div/div/div/div[2]/table/tbody/tr/td[3]/a[1]/span');
if (method_exists($I, 'wait')) {
    $I->wait(3); // only for selenium
}
/* ------------- ABRIR MODAL CREAR PROYECTOS --------------- */
$I->click('//*[@id="agregar_proyectos"]');
if (method_exists($I, 'wait')) {
    $I->wait(1); // only for selenium
}
$proyectosData = ['Acceptacion PROYECTO 1','Agregando datos a la prueba', '2016-09-01', '2021-07-03', 5000];
$I->fillField('input[name="Proyectos[nombre]"]', $proyectosData[0]);
$I->fillField('input[name="Proyectos[descripcion]"]', $proyectosData[1]);
$I->click('ul[class="select2-selection__rendered"]');
$I->click('/html/body/span/span/span[1]/span[1]'); //selecciona todo
//$I->fillField('input[name="Programas[fecha_inicio]"]', $proyectosData[1]);
$I->click('//*[@id="proyectos-fecha_inicio"]'); //
$I->click('/html/body/div[3]/div[5]/table/tbody/tr/td/span[8]');
$I->click('/html/body/div[3]/div[4]/table/tbody/tr/td/span[8]');
$I->click('/html/body/div[3]/div[3]/table/tbody/tr[3]/td[7]');
$I->fillField('input[name="Proyectos[fecha_fin]"]', $proyectosData[3]);
$I->fillField('input[name="Proyectos[presupuesto]"]', $proyectosData[4]);

$I->click('//*[@id="crear_proyecto_"]'); //click en crear

if (method_exists($I, 'wait')) {
    $I->wait(3); // only for selenium
}
/* -------------VER PROYECTOS --------------- */
$I->wantTo('ensure that proyectos works');
$proyectosPage = ProyectosPage::openBy($I);

$I->see('Proyectos', 'h1');


if (method_exists($I, 'wait')) {
    $I->wait(5); // only for selenium
}