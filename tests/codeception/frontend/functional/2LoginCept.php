<?php
use tests\codeception\frontend\FunctionalTester;
use tests\codeception\common\_pages\LoginPage;

/* @var $scenario Codeception\Scenario */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure login page works');
$I->amOnPage(Yii::$app->homeUrl);
$I->see('PEDI');
$I->seeLink('Login');
$I->seeLink('Signup');
$loginPage = LoginPage::openBy($I);

$I->amGoingTo('submit login form with no data');
$loginPage->login('', '');
$I->expectTo('see validations errors');
$I->see('Login cannot be blank.', '.help-block');
$I->see('Password cannot be blank.', '.help-block');

$I->amGoingTo('try to login with wrong credentials');
$I->expectTo('see validations errors');
$loginPage->login('admin', 'wrong');

$I->expectTo('see validations errors');
$I->see('Invalid login or password', '.help-block');

$I->amGoingTo('try to login with correct credentials');
$loginPage->login('erau', 'password_0');

$I->expectTo('see that user is logged');
