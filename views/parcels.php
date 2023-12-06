<?php

$this->title = 'Parcels';   
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

<h1>Parcels</h1>

<?php if($is_admin): ?>
<div class="custom-flex">
    <a href="/add_parcel">
        <button class="btn btn-primary btn-lg w-100" type="button">Add Parcel</button>
    </a>
</div>
<?php endif; ?>

<table class="table">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Recipient</th>
      <th scope="col">Address</th>
      <th scope="col">Coordinates</th>
      <th scope="col">Assigned To</th>
      <th scope="col">Status</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php if(!empty($all_parcels)): ?>
    <?php foreach($all_parcels as $parcel){
      echo '<tr>';
      echo "<td>" . $parcel['id'] .  "</td>";
      echo "<td>" . $parcel['recipient_name'] .  "</td>";
      echo "<td>" . $parcel['delivery_address'] .  "</td>";
      echo "<td>" . '{' . $parcel['latitude'] . ' ,' . $parcel['longitude'] . '}' . "</td>";
      echo "<td>" . $parcel['name'] .  "</td>";
      echo "<td>" . $parcel['status_title'] .  "</td>";
      if($is_admin){
        echo '<td><div class="dropdown show">       <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</a>  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink"> <a class="dropdown-item" href="/edit_parcel/' . $parcel['id'] . '">Update</a> <a class="dropdown-item" href="#">Cancel</a> </div> </div></td>';
      }
      else{
        echo '<td><div class="dropdown show">       <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</a></div></td>';
      }
      echo '</tr>';
    }
    ?>

    <?php endif; ?>
  </tbody>
</table>