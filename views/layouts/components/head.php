<?php
use app\core\Application;
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title><?php echo empty($title) ? 'Parcel Buddy' : $title; ?></title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="../../../assets/images/favicon.png" />
    <link
      rel="stylesheet"
      href="../../../assets/vendor/owl-carousel/css/owl.carousel.min.css"
    />
    <link
      rel="stylesheet"
      href="../../../assets/vendor/owl-carousel/css/owl.theme.default.min.css"
    />
    <link href="../../../assets/vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet" />
    <link href="../../../assets/css/style.css" rel="stylesheet" />
    <link href="../../../assets/css/custom-style.css" rel="stylesheet" />
  </head>

  <body>
    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">
      <!--**********************************
            Nav header start
        ***********************************-->
      <div class="nav-header">
        <a href="/" class="brand-logo">
          <img class="logo-abbr" src="../../../assets/images/logo.png" alt="">
          <img class="logo-compact" src="../../../assets/images/parcel-logo.png" alt="" />
          <img class="brand-title" src="../../../assets/images/parcel-logo.png" alt="" />
        </a>

        <div class="nav-control">
          <div class="hamburger">
            <span class="line"></span><span class="line"></span
            ><span class="line"></span>
          </div>
        </div>
      </div>
      <!--**********************************
            Nav header end
        ***********************************-->

      <!--**********************************
            Header start
        ***********************************-->
      <div class="header">
        <div class="header-content">
          <nav class="navbar navbar-expand">
            <div class="collapse navbar-collapse justify-content-between">
              <div class="header-left">
                <div class="search_bar dropdown">
                  <!-- <span
                    class="search_icon p-3 c-pointer"
                    data-toggle="dropdown"
                  >
                    <i class="mdi mdi-magnify"></i>
                  </span>
                  <div class="dropdown-menu p-0 m-0">
                    <form>
                      <input
                        class="form-control"
                        type="search"
                        placeholder="Search"
                        aria-label="Search"
                      />
                    </form>
                  </div> -->
                </div>
              </div>

              <ul class="navbar-nav header-right">
                <li class="nav-item dropdown notification_dropdown">
                    <?php echo empty(Application::$app->user->name) ? 'User' : Application::$app->user->name; ?>
                </li>
                <li class="nav-item dropdown header-profile">
                  <a
                    class="nav-link"
                    href="#"
                    role="button"
                    data-toggle="dropdown"
                  >
                <img src="../../../assets/images/account-img.png"/>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right">
                    <!-- <a href="/profile" class="dropdown-item">
                      <i class="icon-user"></i>
                      <span class="ml-2">Profile </span>
                    </a> -->
                    <a href="/logout" class="dropdown-item">
                      <i class="icon-key"></i>
                      <span class="ml-2">Logout </span>
                    </a>
                  </div>
                </li>
              </ul>
            </div>
          </nav>
        </div>
      </div>