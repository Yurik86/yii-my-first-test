<?php

use yii\helpers\Html;
use wbraganca\dynamicform\DynamicFormWidget;

?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_inner',
    'widgetBody' => '.container-ext',
    'widgetItem' => '.ext-item',
    'limit' => 4,
    'min' => 1,
    'insertButton' => '.add-ext',
    'deleteButton' => '.remove-ext',
    'model' => $modelsExt[0],
    'formId' => 'dynamic-form',
    'formFields' => [
        'inv_id',
        'prod_t_inv_id',
        'external_lot_number_en',
        'external_lot_number_ua',
        'alloc_quantity',
        'location',
    ],
]); ?>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>Ext</th>
        <th class="text-center">
            <button type="button" class="add-ext btn btn-success btn-xs"><span class="glyphicon glyphicon-plus"></span></button>
        </th>
    </tr>
    </thead>
    <tbody class="container-ext">

    <?php foreach ($modelsExt as $indexExt => $modelExt): ?>
        <tr class="ext-item">
            <td class="vcenter">
                <?php
                // necessary for update action.
                if (! $modelExt->isNewRecord) {
                    echo Html::activeHiddenInput($modelExt, "[{$indexProduct}][{$indexExt}]id");
                }
                ?>
                <?= $form->field($modelExt, "[{$indexProduct}][{$indexExt}]external_lot_number_en")->label('external_lot_number_en')->textInput(['maxlength' => true]) ?>
                <?= $form->field($modelExt, "[{$indexProduct}][{$indexExt}]external_lot_number_ua")->label('external_lot_number_ua')->textInput(['maxlength' => true]) ?>
                <?= $form->field($modelExt, "[{$indexProduct}][{$indexExt}]alloc_quantity")->label('alloc_quantity')->textInput(['maxlength' => true]) ?>
                <?= $form->field($modelExt, "[{$indexProduct}][{$indexExt}]location")->label('location')->textInput(['maxlength' => true]) ?>
            </td>
            <td class="text-center vcenter" style="width: 90px;">
                <button type="button" class="remove-ext btn btn-danger btn-xs"><span class="glyphicon glyphicon-minus"></span></button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php DynamicFormWidget::end(); ?>


</div>
