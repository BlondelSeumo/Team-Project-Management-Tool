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
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand">
              <img src="<?=base_url('assets/uploads/logos/'.full_logo());?>" alt="logo" width="100%">
            </div>

            <div class="card card-primary">
              <div class="card-header"><h4>Reset Password</h4></div>

              <div class="card-body">
                <form method="POST" action="" class="needs-validation" novalidate="">
                  <div class="form-group">
                    <label for="new">New Password (at least 8 characters long)</label>
                    <input type="password" class="form-control" name="new" pattern="^.{8}.*$" tabindex="1" required autofocus>
                  </div>

                  <div class="form-group">
                    <label for="new_confirm" class="control-label">Confirm New Password</label>
                    <input type="password" name="new_confirm" pattern="^.{8}.*$" class="form-control" tabindex="2" required>
                  </div>
                  
	                <?php 
    	                echo form_hidden($user_id);
    	                echo form_hidden($csrf); 
	                    if(isset($message) && !empty($message)){ ?>
	                        <div class="form-group alert alert-danger">
                                <?php echo htmlspecialchars($message);?>
                             </div>
	                <?php } ?>
	                
	                
                  <div class="form-group">
                    <button type="submit" class="savebtn btn btn-primary btn-lg btn-block" tabindex="4">
                      Submit
                    </button>
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
