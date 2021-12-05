<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Devices */

$this->title = 'Создание устройства';
$this->params['breadcrumbs'][] = ['label' => 'Устройства', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devices-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
