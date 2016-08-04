<?php
use tests\codeception\frontend\AcceptanceTester;
use tests\codeception\frontend\_pages\ObjetivosPage;
use tests\codeception\frontend\_pages\LoginPage;

/* @var $scenario Codeception\Scenario */
/*------------- LOGIN ---------------*/
$I = new AcceptanceTester($scenario);
$I->wantTo('ensure login page works');
$I->amOnPage(Yii::$app->homeUrl);

$I->see('PEDI');
$I->seeLink('Login');
$I->seeLink('Signup');
if (method_exists($I, 'wait')) {
    $I->wait(1); // only for selenium
}
$loginPage = LoginPage::openBy($I);

$loginPage->login('erau', 'password_0');
if (method_exists($I, 'wait')) {
    $I->wait(0.5); // only for selenium
}
$I->expectTo('see that user is logged');

/*------------- OBJETIVOS ---------------*/
$I->wantTo('ensure that objetivos works');
if (method_exists($I, 'wait')) {
    $I->wait(2); // only for selenium
}
$I->click('/html/body/div/nav/div/div[2]/ul/li[2]/a');// click en ADMINISTRACION
if (method_exists($I, 'wait')) {
    $I->wait(1); // only for selenium
}
$I->click('/html/body/div/nav/div/div[2]/ul/li[2]/ul/li[1]/a');// click en OBJETIVOS

$objetivosPage = ObjetivosPage::openBy($I);

$I->see('Objetivos', 'h1');
$objetivosPage->open_create();
if (method_exists($I, 'wait')) {
    $I->wait(3); // only for selenium
}
$objetivosPage->insert(['Acceptacion 1','2017-01-01','2018-07-03']);

if (method_exists($I, 'wait')) {
    $I->wait(4); // only for selenium
}





