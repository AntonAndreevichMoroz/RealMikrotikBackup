<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Устройства';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devices-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать устройство', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Запустить бекап вручную', ['backupall'], ['class' => 'btn btn-warning']) ?>
        <?php
            if(Yii::$app->request->get('resultn8n') == "OK"){
               echo Html::tag('div', 'Запрос отправлен успешно');
            } elseif(Yii::$app->request->get('resultn8n') == "FAIL") {
               echo Html::tag('div', 'Ошибка отправки запроса');
            }
        ?>
    </p>

    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'ip_address',
            'sshport',
            'username',
            [
                'attribute' => 'laststatus',
                'contentOptions'=>function ($data) {
                    return $data->laststatus == "BAD" ? array('style'=>'color: red') : array('style'=>'color: green');
                },
                'filter' => array("OK"=>"OK","BAD"=>"BAD"),
            ],
            [
                'attribute' => 'active',
                'value' => function ($data) {
                    return $data->active == "1" ? "ДА" : "Нет";
                },
                'filter' => array("1"=>"ДА","0"=>"НЕТ"),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
