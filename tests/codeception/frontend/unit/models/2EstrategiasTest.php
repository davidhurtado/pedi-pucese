<?php

namespace tests\codeception\frontend\unit\models;

use tests\codeception\frontend\unit\DbTestCase;
use tests\codeception\frontend\unit\fixtures\EstrategiasFixture;
use Codeception\Specify;
use app\models\Estrategias;

class EstrategiasTest extends DbTestCase {

    use Specify;

    public function testCorrectEstrategias() {
        $model = new Estrategias([
            'id_objetivo' => 1,
            'descripcion' => 'Fixture_1',
            'responsables' => '6,7,8',
            'fecha_inicio' => '2017-12-31',
            'fecha_fin' => '2020-12-31',
            'presupuesto' => 3000,
        ]);

        $estrategia = $model->saveEstrategia();

        $this->assertInstanceOf('app\models\Estrategias', $estrategia, 'Objetivo debe ser valido');

        expect('id_objetivo debe ser correcta', $estrategia->id_objetivo)->equals(1);
        expect('descripcion debe ser correcta', $estrategia->descripcion)->equals('Fixture_1');
        expect('responsables debe ser correcta', $estrategia->responsables)->equals('6,7,8');
        expect('fecha_inicio debe ser correcta', $estrategia->fecha_inicio)->equals('2017-12-31');
        expect('fecha_fin debe ser correcta', $estrategia->fecha_fin)->equals('2020-12-31');
        expect('presupuesto debe ser correcta', $estrategia->presupuesto)->equals(3000);
    }

    public function testNotCorrectEstrategias() {
        $model = new Estrategias([
            'id_objetivo' => 1,
            'descripcion' => 'Fixture_1 incorrect estrategia',
            'responsables' => '6,7,8',
            'fecha_inicio' => '2014-12-31',
            'fecha_fin' => '2020-12-31',
            'presupuesto' => 3000,
        ]);

        expect('esta estrategia no puede iniciar con una fecha antigua', $model->saveEstrategia())->null();
    }

    public function fixtures() {
        return [
            'estrategias' => [
                'class' => EstrategiasFixture::className(),
                'dataFile' => '@tests/codeception/frontend/unit/fixtures/data/models/estrategias.php',
            ],
        ];
    }

}
