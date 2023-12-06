<?php
use app\core\Application;
?>

<div class="quixnav">
  <div class="quixnav-scroll">
    <ul class="metismenu" id="menu">
      <li>
        <a href="/" aria-expanded="false">
          <i class="icon icon-single-04"></i>
          <span class="nav-text">Dashboard</span>
        </a>
      </li>

      <?php if(Application::$app->user->role_id == 1): ?>
      <li>
        <a href="/parcels" aria-expanded="false">
          <i class="icon icon-app-store"></i>
          <span class="nav-text">Parcels</span>
        </a>
      </li>

      <?php elseif(Application::$app->user->role_id == 2): ?>
      <li>
        <a href="/parcels" aria-expanded="false">
          <i class="icon icon-app-store"></i>
          <span class="nav-text">My Parcels</span>
        </a>
      </li>

      <?php endif; ?>

      <?php if(Application::$app->user->role_id == 1): ?>
      <li>
        <a href="/delivery_users" aria-expanded="false">
          <i class="icon icon-app-store"></i>
          <span class="nav-text">Delivery Users</span>
        </a>
      </li>
      <?php endif; ?>
  
      <!-- <li>
        <a
          class="has-arrow"
          href="javascript:void()"
          aria-expanded="false"
          ><i class="icon icon-app-store"></i
          ><span class="nav-text">All Parcels</span></a
        >
        <ul aria-expanded="false">
          <li><a href="new-parcels.html">New Parcels</a></li>
          <li><a href="in-transit-parcels.html">IN transit Parcels</a></li>
          <li><a href="delivered-parcels.html">Delivered Parcels</a></li>
        </ul>
      </li> -->
    </ul>
  </div>
</div>