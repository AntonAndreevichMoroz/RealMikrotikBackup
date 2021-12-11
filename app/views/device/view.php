<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Device */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Устройства', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="device-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить это устройство?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Сделать бекап устройства', ['backupone', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
        <?php
            if(Yii::$app->request->get('resultn8n') == "OK"){
               echo Html::tag('div', 'Запрос отправлен успешно');
            } elseif(Yii::$app->request->get('resultn8n') == "FAIL") {
               echo Html::tag('div', 'Ошибка отправки запроса');
            }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'ip_address',
            'sshport',
            'username',
//            'laststatus',
            [
                'attribute' => 'laststatus',
		'contentOptions' => ['style' => ($model->laststatus == "BAD") ? 'color: red' : 'color: green'],
            ],
            [
                'attribute' => 'active',
                'value' => ($model->active == "1") ? "ДА" : "НЕТ",
            ],
        ],
    ]) ?>

</div>
