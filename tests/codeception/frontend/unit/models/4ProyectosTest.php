<?php

namespace tests\codeception\frontend\unit\models;

use tests\codeception\frontend\unit\DbTestCase;
use tests\codeception\frontend\unit\fixtures\ProyectosFixture;
use Codeception\Specify;
use app\models\Proyectos;

class ProyectosTest extends DbTestCase {

    use Specify;

    public function testCorrectProyectos() {
        $model = new Proyectos([
            'id_programa' => 1,
            'nombre' => 'Unit fixture',
            'descripcion' => 'Fixture_1 unit test correct',
            'responsables' => '2,4,6',
            'fecha_inicio' => '2017-01-31',
            'fecha_fin' => '2017-12-31',
            'presupuesto' => 4100,
        ]);

        $proyecto = $model->saveProyecto();

        $this->assertInstanceOf('app\models\Proyectos', $proyecto, 'proyecto should be valid');

        expect('id_programa debe ser correcta', $proyecto->id_programa)->equals(1);
        expect('nombre debe ser correcta', $proyecto->nombre)->equals('Unit fixture');
        expect('descripcion debe ser correcta', $proyecto->descripcion)->equals('Fixture_1 unit test correct');
        expect('responsables debe ser correcta', $proyecto->responsables)->equals('2,4,6');
        expect('fecha inicio debe ser correcta', $proyecto->fecha_inicio)->equals('2017-01-31');
        expect('fecha fin debe ser correcta', $proyecto->fecha_fin)->equals('2017-12-31');
        expect('presupuesto fin debe ser correcta', $proyecto->presupuesto)->equals(4100);
    }

    public function testNotCorrectProyectos() {
        $model = new Proyectos([
            'id_programa' => 1,
            'nombre' => 'Unit fixture incorrect',
            'descripcion' => 'Fixture_1 unit test incorrect',
            'responsables' => '2,4,6',
            'fecha_inicio' => '2014-01-31',
            'fecha_fin' => '2017-12-31',
            'presupuesto' => 4100,
        ]);

        expect('No puedes crear un proyecto con fecha antigua, cree uno nuevo', $model->saveProyecto())->null();
    }

    public function fixtures() {
        return [
            'proyectos' => [
                'class' => ProyectosFixture::className(),
                'dataFile' => '@tests/codeception/frontend/unit/fixtures/data/models/proyectos.php',
            ],
        ];
    }

}
