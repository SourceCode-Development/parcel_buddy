<?php

use app\core\form\Form;

?>
<?php
if($is_admin){
  echo '<h1>Edit parcel</h1>';
}

else{
  echo '<h1>Update parcel status</h1>';
}
?>

<!-- <form action="/update_parcel" method="post">
    <div class="form-group">
        <label>Recipient Name</label>
        <input type="text" class="form-control" name="recipient_name" value="" required autocomplete="off">
        <div class="invalid-feedback">

        </div>
    </div>
    <div class="form-group">
        <label>Full Address</label>
        <input type="text" class="form-control" name="delivery_address" value="" required autocomplete="off">
        <div class="invalid-feedback">
            
        </div>
    </div>
    <div class="form-group">
        <label>Longitude</label>
        <input type="number" class="form-control" name="longitude" step="any" value="" required autocomplete="off">
        <div class="invalid-feedback">
            
        </div>
    </div>
    <div class="form-group">
        <label>Latitude</label>
        <input type="number" class="form-control" name="latitude" step="any" value="" required autocomplete="off">
        <div class="invalid-feedback">
            
        </div>
    </div>

    <div class="form-group">
        <label>Postal Code</label>
        <input type="text" class="form-control" name="postcode" required autocomplete="off">
        <div class="invalid-feedback">
            
        </div>
    </div>
    <div class="form-group">
        <label>Assign To</label>
        <select class="form-control" name="assigned_to" required>
            <?php if(!empty($riders)): ?>
                <?php
                foreach($riders as $rider){
                    echo "<option value='" . $rider['id'] . "'>" . $rider['name'] . "</option>";
                }
                ?>
            <?php endif; ?>
        </select>
        <div class="invalid-feedback">
            
        </div>
    </div>
    <button class="btn btn-success">Submit</button>
</form> -->

<?php $form = Form::begin('/update-parcel-status', 'post') ?>
    <?php echo $form->hiddenField($model, 'id')->hiddenField() ?>
    <?php 
      if($is_admin):
    ?>
    <div class="row">
        <div class="col">
            <?php echo $form->field($model, 'recipient_name') ?>
        </div>
    </div>
    
    <?php echo $form->field($model, 'delivery_address') ?>
    <?php echo $form->field($model, 'longitude') ?>
    <?php echo $form->field($model, 'latitude') ?>
    <?php echo $form->field($model, 'postcode') ?>
    <?php echo $form->selector($model, 'assigned_to', $riders) ?>

    <?php
      endif;
    ?>

    <?php 
    if($is_admin){
      echo $form->selector($model, 'status', [
        ['value' => 1, 'title' => 'In Process'],
        ['value' => 2, 'title' => 'Delivered'],
        ['value' => 3, 'title' => 'Canceled'],
      ]);
    }

    else{
      echo $form->selector($model, 'status', [
        ['value' => 2, 'title' => 'Delivered'],
        ['value' => 3, 'title' => 'Canceled'],
      ]);
    }
     ?>

    <button class="btn btn-primary btn-lg w-100">Submit</button>
<?php Form::end() ?>