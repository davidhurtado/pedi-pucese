<?php
use tests\codeception\frontend\FunctionalTester;
use tests\codeception\frontend\_pages\LoginPage;
use tests\codeception\frontend\_pages\ObjetivosPage;
use tests\codeception\frontend\_pages\EstrategiasPage;
use tests\codeception\frontend\_pages\ProgramasPage;

/* @var $scenario Codeception\Scenario */

$I = new FunctionalTester($scenario);
/* ------------- LOGIN --------------- */
$I->wantTo('ensure login page works');
$I->amOnPage(Yii::$app->homeUrl);

$I->see('PEDI');
$I->seeLink('Login');
$I->seeLink('Signup');
$loginPage = LoginPage::openBy($I);
$I->amGoingTo('submit login form with no data');
$loginPage->login('erau', 'password_0');
$I->expectTo('see that user is logged');
/* ------------- OBJETIVOS --------------- */
$objetivos = ObjetivosPage::openBy($I);
$I->wantTo('ensure that objetivos works');
$I->see('Objetivos', 'h1');
$objetivos->open_create();
/* -------------ESTRATEGIA --------------- */
$estrategias = EstrategiasPage::openBy($I);
$I->wantTo('ensure that estrategias works');
$I->see('Estrategias', 'h1');

/* -------------PROGRAMAS --------------- */
$programas = ProgramasPage::openBy($I);
$I->wantTo('ensure that programas works');
$I->see('Programas', 'h1');