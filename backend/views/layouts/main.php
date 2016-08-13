<?php
/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>

        <div class="wrap">
            <?php
            NavBar::begin([
                'brandLabel' => 'PEDI ADMINISTRACION',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => 'Login', 'url' => ['/user/security/login']];
            } else {
                $menuItems = [
                    ['label' => 'INICIO', 'url' => ['/site/index']],
                ];
                if (Yii::$app->user->can('admin') || Yii::$app->user->can('superadmin')) {
                    $menuItems[] = [
                        'label' => 'ADMINISTRACION',
                        'items' => [
                            ['label' => 'OBJETIVOS', 'url' => [ '/objetivos']],
                            '<li class="divider"></li>',
                            '<li class="dropdown-header">Consultas</li>',
                            ['label' => 'ESTRATEGIAS', 'url' => [ '/estrategias']],
                            ['label' => 'PROGRAMAS', 'url' => [ '/programas']],
                            ['label' => 'PROYECTOS', 'url' => [ '/proyectos']],
                            ['label' => 'SUBPROYECTOS', 'url' => [ '/subproyectos']],
                            '<li class="divider"></li>',
                            '<li class="dropdown-header">Reportes</li>',
                            ['label' => 'ACTIVIDADES', 'url' => [ '/actividades']],
                            //-- Mod - organigrama --//
                            '<li class="divider"></li>',
                            '<li class="dropdown-header">Organigrama</li>',
                            ['label' => 'Organigramas', 'url' => [ '/organigrama']],
                            ['label' => 'Niveles', 'url' => [ '/nivel']],
                        ],
                    ];
                    $menuItems[] = ['label' => 'Users RBAC', 'url' => ['/user/admin/index']];
                }
                $menuItems[] = '<li>'
                        . Html::beginForm(['/site/logout'], 'post')
                        . Html::submitButton(
                                'Logout (' . Yii::$app->user->identity->username . ')', ['class' => 'btn btn-link']
                        )
                        . Html::endForm()
                        . '</li>';
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
            ?>

            <div class="container">
                <?=
                Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ])
                ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <p class="pull-left">&copy; PEDI - PUCESE <?= date('Y') ?></p>

                <p class="pull-right"><?= Yii::powered() ?></p>
            </div>
        </footer>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
