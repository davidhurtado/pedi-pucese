<?php

namespace tests\codeception\frontend\unit\models;

use tests\codeception\frontend\unit\DbTestCase;
use tests\codeception\frontend\unit\fixtures\ObjetivosFixture;
use Codeception\Specify;
use app\models\Objetivos;

class ObjetivosTest extends DbTestCase {

    use Specify;

    public function testCorrectObjetivos() {
        $model = new Objetivos([
            'descripcion' => 'Fixture_1 test correct',
            'responsables' => '4,5,6',
            'fecha_inicio' => '2017-12-31',
            'fecha_fin' => '2020-12-31',
        ]);

        $objetivo = $model->saveObjetivo();

        $this->assertInstanceOf('app\models\Objetivos', $objetivo, 'Objetivo debe ser valido');

        expect('descripcion debe ser correcta', $objetivo->descripcion)->equals('Fixture_1 test correct');
        expect('responsables debe ser correcta', $objetivo->responsables)->equals('4,5,6');
        expect('fecha_inicio debe ser correcta', $objetivo->fecha_inicio)->equals('2017-12-31');
        expect('fecha_fin debe ser correcta', $objetivo->fecha_fin)->equals('2020-12-31');
    }

    public function testNotCorrectObjetivos() {
        $model = new Objetivos([
            'descripcion' => 'Fixture_2 test incorrect',
            'responsables' => '4,5,6',
            'fecha_inicio' => '2015-12-31',
            'fecha_fin' => '2020-12-31',
        ]);

        expect('No puedes crear un objetivo con fecha antigua, cree uno nuevo', $model->saveObjetivo())->null();
    }

    public function fixtures() {
        return [
            'objetivos' => [
                'class' => ObjetivosFixture::className(),
                'dataFile' => '@tests/codeception/frontend/unit/fixtures/data/models/objetivos.php',
            ],
        ];
    }

}
