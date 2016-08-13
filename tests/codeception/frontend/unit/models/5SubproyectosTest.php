<?php

namespace tests\codeception\frontend\unit\models;

use tests\codeception\frontend\unit\DbTestCase;
use tests\codeception\frontend\unit\fixtures\SubproyectosFixture;
use Codeception\Specify;
use app\models\Subproyectos;

class SubproyectosTest extends DbTestCase {

    use Specify;

    public function testCorrectSubproyectos() {
        $model = new Subproyectos([
        'id_proyecto' => 1,
        'nombre' => 'Unit fixture',
        'descripcion' => 'Fixture_1 unit test correct',
        'evidencias' => 'test_subproyecto1.pdf;',
        'fecha_inicio' => '2017-10-31',
        'fecha_fin' => '2018-12-31',
        ]);

        $proyecto = $model->saveSubproyecto();

        $this->assertInstanceOf('app\models\Subproyectos', $proyecto, 'proyecto deberia ser valido');

        expect('id_proyecto debe ser correcta', $proyecto->id_proyecto)->equals(1);
        expect('nombre debe ser correcta', $proyecto->nombre)->equals('Unit fixture');
        expect('descripcion debe ser correcta', $proyecto->descripcion)->equals('Fixture_1 unit test correct');
        expect('evidencias debe ser correcta', $proyecto->evidencias)->equals('test_subproyecto1.pdf;');
        expect('fecha inicio debe ser correcta', $proyecto->fecha_inicio)->equals('2017-10-31');
        expect('fecha fin debe ser correcta', $proyecto->fecha_fin)->equals('2018-12-31');
    }

    public function testNotCorrectSubproyectos() {
        $model = new Subproyectos([
            'id_proyecto' => 1,
        'nombre' => 'Unit fixture incorrect subproyecto',
        'descripcion' => 'Fixture_1 unit test correct',
        'evidencias' => 'test_subproyecto1.pdf;',
        'fecha_inicio' => '2012-10-31',
        'fecha_fin' => '2018-12-31',
        ]);

        expect('No puedes crear un subproyecto con fecha antigua, cree uno nuevo', $model->saveSubproyecto())->null();
    }

    public function fixtures() {
        return [
            'subproyectos' => [
                'class' => SubproyectosFixture::className(),
                'dataFile' => '@tests/codeception/frontend/unit/fixtures/data/models/subproyectos.php',
            ],
        ];
    }

}
