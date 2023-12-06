<?php

use app\core\form\Form;

$form = new Form();
?>

<h1>Edit User</h1>

<?php $form = Form::begin('/update_delivery_user', 'post') ?>
    <?php echo $form->hiddenField($model, 'id')->hiddenField() ?>
    <div class="row">
        <div class="col">
            <?php echo $form->field($model, 'name') ?>
        </div>
    </div>
    <?php echo $form->field($model, 'email') ?>
    <?php echo $form->field($model, 'password')->passwordField() ?>
    <?php echo $form->field($model, 'passwordConfirm')->passwordField() ?>
    <?php echo $form->selector($model, 'role_id', [
        ['value' => 2, 'title' => 'Delivery User'],
    ]
    ) ?>
    <?php echo $form->selector($model, 'status', [
        ['value' => 1, 'title' => 'Active'],
        ['value' => 0, 'title' => 'Inactive'],
    ]
    ) ?>
    <button class="btn btn-primary btn-lg w-100">Submit</button>
<?php Form::end() ?>