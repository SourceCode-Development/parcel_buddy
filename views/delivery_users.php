<?php

$this->title = 'Delivery Users';
?>

<style>
    .custom-flex{
        display: flex;
        justify-content: flex-end;
        margin-bottom: 10px;
    }

    .custom-flex a{
      margin-right: 60px;
    }
</style>

<h1>Delivery Users</h1>

<div class="custom-flex">
    <a href="/add_delivery_user">
        <button class="btn btn-primary btn-lg w-100" type="button">Create Delivery user</button>
    </a>
</div>

<table class="table">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Name</th>
      <th scope="col">Email</th>
      <th scope="col">Status</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php if(!empty($riders)): ?>
    <?php foreach($riders as $rider){
      echo '<tr>';
      echo "<td>" . $rider['id'] .  "</td>";
      echo "<td>" . $rider['name'] .  "</td>";
      echo "<td>" . $rider['email'] .  "</td>";
      echo "<td>" . ( empty($rider['status']) ? 'Inactive' : 'Active' ) .  "</td>";
      echo '<td><div class="dropdown show"> <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</a>  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink"> <a class="dropdown-item" href="/edit_delivery_user/' . $rider['id'] . '">Update</a> </div> </div></td>';
      echo '</tr>';
    }
    ?>

    <?php endif; ?>
  </tbody>
</table>