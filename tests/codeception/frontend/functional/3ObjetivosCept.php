<?php

use tests\codeception\frontend\FunctionalTester;
use tests\codeception\frontend\_pages\LoginPage;
use tests\codeception\frontend\_pages\ObjetivosPage;

/* @var $scenario Codeception\Scenario */

/*------------- LOGIN ---------------*/
$I = new FunctionalTester($scenario);
$I->wantTo('ensure login page works');
$I->amOnPage(Yii::$app->homeUrl);

$I->see('PEDI');
$I->seeLink('Login');
$I->seeLink('Signup');
$loginPage = LoginPage::openBy($I);

$loginPage->login('erau', 'password_0');
$I->expectTo('see that user is logged');


/*------------- Objetivos ---------------*/

$I->wantTo('ensure that objetivos works');

$objetivosPage = ObjetivosPage::openBy($I);

$I->see('Objetivos', 'h1');
$objetivosPage->open_create();
$I->amGoingTo('submit objetivos form with no data');
$I->click('button[id="crear_objetivo_modal"]');
//sleep(3);
//$I->click('/html/body/div[1]/div/div/div[3]/div/div/div/div/div/form/div[4]/button');
//$I->expectTo('see validations errors');
//$I->see('Crear Objetivos', 'h3');
//$I->see('Descripcion cannot be blank.', '.help-block');
//$I->see('Fecha Inicio cannot be blank.', '.help-block');
//$I->see('Fecha Fin cannot be blank.', '.help-block');
//
//$I->amGoingTo('submit objetivos form with correct data');
//$objetivosPage->insert([
//    'descripcion' => 'Functional Objetivo test 1',
//    'fecha_inicio' => '2017-01-01',
//    'fecha_fin' => '2020-12-31',
//]);
