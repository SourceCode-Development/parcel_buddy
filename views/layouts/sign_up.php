<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Parcel Buddy | Register</title>
    <link href="../../assets/images/favicon.ico" rel="icon" />
    <link href="../../assets/css/style.css" rel="stylesheet" />
    <link href="../../assets/css/custom-style.css" rel="stylesheet" />
    <link href="../../assets/css/responsive.css" rel="stylesheet" />
  </head>

  <body>
    <section class="register-hero">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-4">
            <div class="login-form">
              <div class="login-logo">
                <img src="../../assets/images/logo-parcel.png" />
              </div>
              
              <div class="log-frm">
                <div class="container">
                  <?php
                  use app\core\Application;
                  if (Application::$app->session->getFlash('success')): ?>
                      <div class="alert alert-success">
                          <p><?php echo Application::$app->session->getFlash('success') ?></p>
                      </div>
                  <?php endif; ?>
                  {{content}}
                </div>
                <div class="last-log">
                  <span
                    >Already have an account?
                    <a href="/login">Sign In</a></span
                  >
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-8"></div>
        </div>
      </div>
    </section>
  </body>
</html>
