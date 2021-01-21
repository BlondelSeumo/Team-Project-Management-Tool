<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('includes/head'); ?>
</head>
<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
            <div class="login-brand">
              <img src="<?=base_url('assets/uploads/logos/'.full_logo());?>" alt="logo" width="100%">
            </div>

            <div class="card card-primary">
              <div class="card-header"><h4>Register</h4></div>

              <div class="card-body">
                <form id="login" method="POST" action="<?=base_url('auth/create_user')?>" class="needs-validation" novalidate="">
                  <div class="row">
                    <div class="form-group col-6">
                      <label for="frist_name">First Name</label>
                      <input type="hidden" name="groups" value="1">
                      <input type="hidden" name="new_register" value="1">
                      <input id="frist_name" type="text" class="form-control" name="first_name" autofocus>
                    </div>
                    <div class="form-group col-6">
                      <label for="last_name">Last Name</label>
                      <input id="last_name" type="text" class="form-control" name="last_name">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control" name="email" tabindex="1" required autofocus>
                    <div class="invalid-feedback">
                      Please fill in your email
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="form-group col-6">
                      <label for="password" class="d-block">Password</label>
                      <input id="password" type="password" class="form-control pwstrength" data-indicator="pwindicator" name="password">
                      <div id="pwindicator" class="pwindicator">
                        <div class="bar"></div>
                        <div class="label"></div>
                      </div>
                    </div>
                    <div class="form-group col-6">
                      <label for="password2" class="d-block">Password Confirmation</label>
                      <input id="password2" type="password" class="form-control" name="password_confirm">
                    </div>
                  </div>

                  <div class="form-group">
                    <button type="submit" class="savebtn btn btn-primary btn-lg btn-block" tabindex="4">
                    Register
                    </button>
                  </div>

                  <div class="text-muted text-center">
                    Already have an account? <a href="<?=base_url('auth');?>">Login Here</a>
                  </div>

                  <div class="form-group">
                    <div class="result"><?=isset($message)?htmlspecialchars($message):'';?></div>
                  </div>

                </form>
              </div>
            </div>
            <div class="simple-footer">
              <?=htmlspecialchars(footer_text())?>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

<?php $this->load->view('includes/js'); ?>

</body>
</html>
