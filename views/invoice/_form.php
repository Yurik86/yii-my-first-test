<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Invoice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invoice-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <?= $form->field($model, 'executor_id')->textInput() ?>

    <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'purchase_order')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'warehouse_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'h_s_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'net_weight')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gross_weight')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'paletts_info')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_item')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shipment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'delivery_terms')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_pcs')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_summ')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'freight')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'freight_unit')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'freight_amount')->textInput() ?>


    //тут начинаем виджет

    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.product-item',
        'limit' => 10,
        'min' => 1,
        'insertButton' => '.add-product',
        'deleteButton' => '.remove-product',
        'model' => $modelsProductsInvoice[0],
        'formId' => 'dynamic-form',
        'formFields' => [
            'quantity',
            'unit',
            'unit_price_manual',
            'total_price',
        ],
    ]); ?>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Product</th>
            <th style="width: 450px;">Productn item</th>
            <th class="text-center" style="width: 90px;">
                <button type="button" class="add-product btn btn-success btn-xs"><span class="glyphicon glyphicon-plus"></span></button>
            </th>
        </tr>
        </thead>


        <tbody class="container-items">
        <?php foreach ($modelsProductsInvoice as $indexProductInv => $modelProductIvoice): ?>

            <tr class="product-item">
                <td class="vcenter">
                    <?php
                    echo $form->field($modelsProducts, 'products_id')->widget(Select2::classname(), [
                        'data' =>\yii\helpers\ArrayHelper::map( \app\models\Products::find()->all(), 'products_id', 'description_en'),
                        'language' => 'ru',
                        'options' => ['placeholder' => 'Выбрать товар',],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                    <?php
                    // necessary for update action.
                    if (! $modelProductIvoice->isNewRecord) {
                        echo Html::activeHiddenInput($modelProductIvoice, "[{$indexProductInv}]id");
                    }
                    ?>

                    <?= $form->field($modelProductIvoice, "[{$indexProductInv}]quantity")->label('Amount')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($modelProductIvoice, "[{$indexProductInv}]unit")->label('Unit')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($modelProductIvoice, "[{$indexProductInv}]unit_price_manual")->label('unit_price_manual')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($modelProductIvoice, "[{$indexProductInv}]total_price",[ 'template' => "{label}\n<div class='total-sum'>{input}</div>\n{hint}\n{error}",])->textInput(['maxlength' => true]) ?>
                </td>
                <td>
                    //вкладываем еще один и все заебца
                    //ключи ебать их в сраку
                    <?= $this->render('_form_ext', [
                        'form' => $form,
                        'indexProduct' => $indexProductInv,
                        'modelsExt' => $modelsExt[$indexProductInv],
                    ]) ?>
                </td>
                <td class="text-center vcenter" style="width: 90px; verti">
                    <button type="button" class="remove-product btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus"></span></button>
                </td>
            </tr>
            <div class="row">

            </div>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php DynamicFormWidget::end(); ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
