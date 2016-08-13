<?php

namespace tests\codeception\frontend\_pages;

use yii\codeception\BasePage;

/**
 * Represents contact page
 * @property \codeception_frontend\AcceptanceTester|\codeception_frontend\FunctionalTester $actor
 */
class EstrategiasPage extends BasePage {

    public $route = 'estrategias/create';

    /**
     * @param array $estrategiasData
     */
    public function open_create() {
        //*[@id="agregar_estrategias"]
        $this->actor->click('button[id="agregar_estrategias"]');
        //$this->actor->click('button[id="crear_objetivo_modal"]');
    }

    public function insertar(array $estrategiasData) {
        $this->actor->fillField('textarea[name="Estrategias[descripcion]"]', $estrategiasData[0]);
        $this->actor->click('ul[class="select2-selection__rendered"]');
        $this->actor->click('/html/body/span/span/span[1]/span[1]'); //selecciona todo
        $this->actor->fillField('input[name="Estrategias[fecha_inicio]"]', $estrategiasData[1]);
        $this->actor->fillField('input[name="Estrategias[fecha_fin]"]', $estrategiasData[2]);
        $this->actor->click('/html/body/div[1]/div/div/div[3]/div/div/div/div/div/form/div[5]/button'); //click en crear
    }

}
