<?php

use app\core\form\Form;

$form = new Form();
?>

<h1>Register</h1>

<?php $form = Form::begin('', 'post') ?>
    <div class="row">
        <div class="col">
            <?php echo $form->field($model, 'name') ?>
        </div>
    </div>
    <?php echo $form->field($model, 'email') ?>
    <?php echo $form->field($model, 'password')->passwordField() ?>
    <?php echo $form->field($model, 'passwordConfirm')->passwordField() ?>
    <?php echo $form->selector($model, 'role_id', [
        ['value' => 1, 'title' => 'Manager'],
        ['value' => 2, 'title' => 'Delivery User'],
    ]
    ) ?>
    <button class="btn btn-primary btn-lg w-100">Submit</button>
<?php Form::end() ?>