<?php

namespace tests\codeception\frontend\_pages;

use \yii\codeception\BasePage;

/**
 * Represents signup page
 * @property \codeception_frontend\AcceptanceTester|\codeception_frontend\FunctionalTester $actor
 */
class ProyectosPage extends BasePage
{

    public $route = 'proyectos/create';

    /**
     * @param array $proyectosData
     */
    public function submit(array $proyectosData)
    {
        foreach ($proyectosData as $field => $value) {
            $inputType = $field === 'body' ? 'textarea' : 'input';
            $this->actor->fillField($inputType . '[name="Proyectos[' . $field . ']"]', $value);
        }
        $this->actor->click('proyectos-button');
    }
}
