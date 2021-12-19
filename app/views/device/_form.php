<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Device */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="device-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ip_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sshport')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sshuse')->dropDownList(array('0' => 'Нет', '1' => 'Да')) ?>

    <?php
        if (empty($_ENV["DATA_ENCRYPT_PASSWORD"])) {
            $password=$model->password;
        } else {
            $password=\Yii::$app->getSecurity()->decryptByKey(base64_decode($model->password), $_ENV["DATA_ENCRYPT_PASSWORD"]);
        }
    ?>
    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'value' => $password])?>

    <?= $form->field($model, 'sshkey')->textarea(['rows' => 4]) ?>

    <?= $form->field($model, 'active')->dropDownList(array('1' => 'Да', '0' => 'Нет')) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
