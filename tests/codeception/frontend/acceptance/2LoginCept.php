<?php
use tests\codeception\frontend\AcceptanceTester;
use tests\codeception\frontend\_pages\LoginPage;

/* @var $scenario Codeception\Scenario */

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

$I->amGoingTo('submit login form with no data');
$loginPage->login('', '');
if (method_exists($I, 'wait')) {
    $I->wait(0.4); // only for selenium
}
$I->expectTo('see validations errors');
$I->see('Login cannot be blank.', '.help-block');
$I->see('Password cannot be blank.', '.help-block');

$I->amGoingTo('try to login with wrong credentials');
$I->expectTo('see validations errors');
$loginPage->login('admin', 'wrong');
if (method_exists($I, 'wait')) {
    $I->wait(0.4); // only for selenium
}
$I->expectTo('see validations errors');
$I->see('Invalid login or password', '.help-block');

$I->amGoingTo('try to login with correct credentials');
$loginPage->login('erau', 'password_0');
if (method_exists($I, 'wait')) {
    $I->wait(0.5); // only for selenium
}
$I->expectTo('see that user is logged');
// Uncomment if using WebDriver
  $I->click('/html/body/div/nav/div/div[2]/ul/li[3]/form/button');//logout
  
  $I->seeLink('Login');
 
if (method_exists($I, 'wait')) {
    $I->wait(3); // only for selenium
}