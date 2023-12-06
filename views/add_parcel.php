<?php

/** @var $model \app\models\LoginForm */

use app\core\form\Form;

?>

<h1>Create new parcel</h1>

<form action="/create_parcel" method="post">
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
    <button class="btn btn-primary btn-lg w-100">Submit</button>
</form>