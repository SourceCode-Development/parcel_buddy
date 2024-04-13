<?php

$this->title = 'Parcels';   
?>

<style>
    .custom-flex{
        display: flex;
        justify-content: flex-end;
        margin-bottom: 10px;
    }

    .center-flex{
      display: flex;
      justify-content: center;
      margin: 10px;
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

<div id="map" class="custom-flex" style="height:300px">
    
</div>

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
    <?php $count = 0; ?>
    <?php foreach($all_parcels as $parcel){
      if($count >= 5){
        break;
      }
      echo '<tr>';
      echo "<td>" . $parcel['id'] .  "</td>";
      echo "<td>" . $parcel['recipient_name'] .  "</td>";
      echo "<td>" . $parcel['delivery_address'] .  "</td>";
      echo "<td>" . '{' . $parcel['latitude'] . ' ,' . $parcel['longitude'] . '}' . "</td>";
      echo "<td>" . $parcel['name'] .  "</td>";
      echo "<td>" . $parcel['status_title'] .  "</td>";
      if($is_admin){
        echo '<td><div class="dropdown show">  <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</a>  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink"> <a class="dropdown-item" href="/edit_parcel/' . $parcel['id'] . '">Update</a> <a class="dropdown-item" href="#">Cancel</a> </div> </div></td>';
      }
      else{
        echo '<td><div class="dropdown show">  <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</a>  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink"> <a class="dropdown-item" href="/change-status/' . $parcel['id'] . '">Change status</a> </div> </div></td>';
      }
      echo '</tr>';
      $count++;
    }
    ?>

    <?php endif; ?>
  </tbody>
</table>


<div class="center-flex">
    <button class="btn btn-primary btn-lg w-20" onClick="open_paginated()">
      Load More
    </button>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCElxolerfeQGbl4wigcDTEEZeg9H6skMI&libraries=places&callback=initMap" async defer></script>

<script>
  let page_count = 1;
  let all_parcels = <?php echo \json_encode($all_parcels); ?>;
  const is_admin = <?php echo \json_encode($is_admin); ?>;
  let center_lat;
  let center_long;

  window.addEventListener('load', function(){
    navigator.geolocation.getCurrentPosition((position) => {
      center_lat = position.coords.latitude;
      center_long = position.coords.longitude;

      initMap({'latitude': center_lat, 'longitude': center_long});
    });
  });

  async function initMap(initial_coordinates) {
    // Request needed libraries.
    try{
      if(initial_coordinates == null || initial_coordinates == undefined){
        return;
      }

      if(google == undefined){
        return;
      }

      const { Map, InfoWindow } = await google.maps.importLibrary("maps");
      const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary(
        "marker",
      );

      const map = new Map(document.getElementById("map"), {
        zoom: 12,
        center: { lat: initial_coordinates.latitude, lng: initial_coordinates.longitude },
        mapId: "4504f8b37365c3d0",
      });
      // Set LatLng and title text for the markers. The first marker (Boynton Pass)
      // receives the initial focus when tab is pressed. Use arrow keys to
      // move between markers; press tab again to cycle through the map controls.

      // Create an info window to share between markers.
      const infoWindow = new InfoWindow();

      // Create the markers.
      for(let i=0; i<all_parcels.length; i++){
        const pin = new PinElement({
          glyph: `${i + 1}`,
        });

        let title_dynamic = `${all_parcels[i]['recipient_name']}` + `{${all_parcels[i]['latitude']}, ${all_parcels[i]['longitude']}}`;

        const marker = new AdvancedMarkerElement({
          position: {'lat': all_parcels[i]['latitude'], 'lng': all_parcels[i]['longitude']},
          map,
          title: title_dynamic,
          content: pin.element,
        });

        // Add a click listener for each marker, and set up the info window.
        marker.addListener("click", ({ domEvent, latLng }) => {
          const { target } = domEvent;

          infoWindow.close();
          infoWindow.setContent(marker.title);
          infoWindow.open(marker.map, marker);
        });
      }
    }
    catch(error){
      console.warn(error.message);
    }
  }

async function open_paginated(){
  page_count = page_count + 1;
  let button = event.target;
  let url = location.origin + '/parcels-paginated/' + page_count;

  let url_obj = new URL(url);
  //url_obj.searchParams.set('page', page_count);

  let options = {};

  button.setAttribute('disabled', '');
  button.textContent = 'Loading...';

  let promise = await fetch(url_obj.href, options);
  let response = await promise.json();

  button.removeAttribute('disabled');
  button.textContent = 'Load More';

  if(response.all_parcels == undefined || response.all_parcels.length < 1 ){
    button.parentElement.removeChild(button);
  }

  render_rows(response.all_parcels);

  all_parcels.push(... response.all_parcels);
  initMap({'latitude': center_lat, 'longitude': center_long});
}

function render_rows(data){
  let table = document.querySelector('.table');
  let tbody = table.querySelector('tbody');

  let template = ``;

  for(let row of data){
      template = template + '<tr>';
      template = template + "<td>" + row['id'] +  "</td>";
      template = template + "<td>" + row['recipient_name'] +  "</td>";
      template = template + "<td>" + row['delivery_address'] +  "</td>";
      template = template + "<td>" +'{' + row['latitude'] + ' ,' + row['longitude'] + '}' + "</td>";
      template = template + "<td>" + row['name'] +  "</td>";
      template = template + "<td>" + row['status_title'] +  "</td>";
      
      if(is_admin){
        template = template + '<td><div class="dropdown show">  <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</a>  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink"> <a class="dropdown-item" href="/edit_parcel/' + row['id'] + '">Update</a> <a class="dropdown-item" href="#">Cancel</a> </div> </div></td>';
      }

      else{
        template = template + '<td><div class="dropdown show">  <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</a>  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink"> <a class="dropdown-item" href="/change-status/' + row['id'] + '">Change Status</a> </div> </div></td>';
      }

      template = template + '</tr>';
  }

  tbody.insertAdjacentHTML('beforeend', template);
}

</script>