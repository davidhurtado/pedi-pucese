<?php

namespace tests\codeception\frontend\unit\models;

use tests\codeception\frontend\unit\DbTestCase;
use tests\codeception\frontend\unit\fixtures\ProgramasFixture;
use Codeception\Specify;
use app\models\Programas;

class ProgramasTest extends DbTestCase {

    use Specify;

    public function testCorrectProgramas() {
        $model = new Programas([
            'id_estrategia' => 1,
            'descripcion' => 'Fixture 1 Programas',
            'responsables' => '7,4,9',
            'fecha_inicio' => '2018-03-31',
            'fecha_fin' => '2019-07-31',
            'presupuesto' => 1400,
        ]);

        $programa = $model->savePrograma();

        $this->assertInstanceOf('app\models\Programas', $programa, 'El programa debe ser valido');

        expect('id_estrategia debe ser correcta', $programa->id_estrategia)->equals(1);
        expect('descripcion debe ser correcta', $programa->descripcion)->equals('Fixture 1 Programas');
        expect('responsables debe ser correcta', $programa->responsables)->equals('7,4,9');
        expect('fecha inicio debe ser correcta', $programa->fecha_inicio)->equals('2018-03-31');
        expect('fecha fin debe ser correcta', $programa->fecha_fin)->equals('2019-07-31');
        expect('presupuesto debe ser correcta', $programa->presupuesto)->equals(1400);
    }

    public function testNotCorrectProgramas() {
        $model = new Programas([
            'id_estrategia' => 1,
            'descripcion' => 'Fixture 1 incorrect Programas',
            'responsables' => '7,4,9',
            'fecha_inicio' => '2015-03-31',
            'fecha_fin' => '2019-07-31',
            'presupuesto' => 1400,
        ]);

        expect('No puedes crear un programa con fecha antigua, cree uno nuevo', $model->savePrograma())->null();
    }

    public function fixtures() {
        return [
            'programas' => [
                'class' => ProgramasFixture::className(),
                'dataFile' => '@tests/codeception/frontend/unit/fixtures/data/models/programas.php',
            ],
        ];
    }

}
