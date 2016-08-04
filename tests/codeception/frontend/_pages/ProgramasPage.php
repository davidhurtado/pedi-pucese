<?php

namespace tests\codeception\frontend\_pages;

use \yii\codeception\BasePage;

/**
 * Represents signup page
 * @property \codeception_frontend\AcceptanceTester|\codeception_frontend\FunctionalTester $actor
 */
class ProgramasPage extends BasePage
{

    public $route = 'programas/create';

    /**
     * @param array $programasData
     */
    public function open_create() {
        //*[@id="agregar_estrategias"]
        $this->actor->click('button[id="crear_programa_modal"]');
        //$this->actor->click('button[id="crear_objetivo_modal"]');
    }
    public function insertar(array $programaData) {
        $this->actor->fillField('textarea[name="Programas[descripcion]"]', $programaData[0]);
        $this->actor->click('ul[class="select2-selection__rendered"]');
        $this->actor->click('/html/body/span/span/span[1]/span[1]'); //selecciona todo
        $this->actor->click('/html/body/div[1]/div/div/div[3]/div/div/div/div/div/form/div[3]/div[1]/div/div[1]/input'); //
        $this->actor->click('/html/body/div[4]/div[5]/table/tbody/tr/td/span[8]');
        $this->actor->click('/html/body/div[4]/div[4]/table/tbody/tr/td/span[8]');
        $this->actor->click('/html/body/div[4]/div[3]/table/tbody/tr[2]/td[6]');
        $this->actor->fillField('input[name="Programas[fecha_fin]"]', $programaData[2]);
        $this->actor->click('/html/body/div[1]/div/div/div[3]/div/div/div/div/div/form/div[4]/button'); //click en crear
    }
}
