<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('includes/head'); ?>
</head>
<body>
  <div id="app">
    <div class="main-wrapper">
      <?php $this->load->view('includes/navbar'); ?>
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <div class="section-header-back">
              <a href="<?=base_url()?>" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>
            Profile
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>">Dashboard</a></div>
              <div class="breadcrumb-item">Profile</div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">

              <div class="col-md-12">
                <div class="card profile-widget" id="profile-card">
                  <div class="profile-widget-header mb-0">  
                    <span class="avatar-item mb-0"> 
                    <?php
                      if(isset($profile_user['profile']) && !empty($profile_user['profile'])){
                    ?>       
                      <img alt="image" src="<?=base_url(UPLOAD_PROFILE.''.htmlspecialchars($profile_user['profile']))?>" class="rounded-circle profile-widget-picture">
                    <?php }else{ ?>
                      <figure class="user-avatar avatar avatar-xl rounded-circle profile-widget-picture" data-initial="<?=htmlspecialchars($profile_user['short_name'])?>"></figure>
                    <?php } ?>
                    </span> 
                    <div class="profile-widget-items">
                      <div class="profile-widget-item">
                        <div class="profile-widget-item-label">Projects</div>
                        <div class="profile-widget-item-value"><span class="badge badge-secondary"><?=htmlspecialchars($profile_user['projects_count'])?></span></div>
                      </div>
                      <?php if(!$this->ion_auth->in_group(4)){ ?>  
                      <div class="profile-widget-item">
                        <div class="profile-widget-item-label">Tasks</div>
                        <div class="profile-widget-item-value"><span class="badge badge-secondary"><?=htmlspecialchars($profile_user['tasks_count'])?></span></div>
                      </div>
                      <?php } ?> 
                      <div class="profile-widget-item">
                        <div class="profile-widget-item-label">Status</div>
                        <div class="profile-widget-item-value"><?=htmlspecialchars($profile_user['active'])==1?'<span class="badge badge-success">Active</span>':'<span class="badge badge-danger">Deactive</span>'?></div>
                      </div>
                    </div>
                  </div>

                  <form action="<?=base_url('auth/edit-user')?>" id="profile-form" method="post" class="needs-validation" novalidate="">
                    <div class="card-body">
                        <div class="row">   
                          <?php if($this->ion_auth->in_group(4)){ ?>  
                            <div class="form-group col-md-12">
                              <label>Company</label>
                              <input type="text" name="company" class="form-control" value="<?=htmlspecialchars($profile_user['company'])?>">
                            </div>
                          <?php } ?>                             
                          <div class="form-group col-md-6 col-12">
                            <label>First Name<span class="text-danger">*</span></label>
                            <input type="hidden" name="update_id" value="<?=htmlspecialchars($profile_user['id'])?>">
                            <input type="hidden" name="old_profile_pic" value="<?=htmlspecialchars($profile_user['profile'])?>">
                            <input type="hidden" name="groups" value="<?=htmlspecialchars($profile_user['group_id'])?>">
                            <input type="text" name="first_name" class="form-control" value="<?=htmlspecialchars($profile_user['first_name'])?>" required="">
                            <div class="invalid-feedback">
                              Please fill in the first name
                            </div>
                          </div>
                          <div class="form-group col-md-6 col-12">
                            <label>Last Name<span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control" value="<?=htmlspecialchars($profile_user['last_name'])?>" required="">
                            <div class="invalid-feedback">
                              Please fill in the last name
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-md-6 col-12">
                            <label>Email<span class="text-danger">*</span><i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="Email can not be updated."></i></label>
                            <input type="email" class="form-control" value="<?=htmlspecialchars($profile_user['email'])?>" required=""  readonly disabled> 
                            <div class="invalid-feedback">
                              Please fill in the email
                            </div>
                          </div>
                          <div class="form-group col-md-6 col-12">
                            <label>Phone</label>
                            <input type="tel" name="phone" class="form-control" value="<?=htmlspecialchars($profile_user['phone'])?>">
                          </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                            <label>Password <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="Leave Password and Confirm Password empty for no change in Password."></i></label>
                            <input type="text" name="password"  class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                            <label>Confirm Password <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="Leave Password and Confirm Password empty for no change in Password."></i></label>
                            <input type="text" name="password_confirm"  class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                            <label>User Profile <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="Leave empty for no changes."></i></label>
                                <div class="custom-file mt-1">
                                    <input type="file" name="profile" class="custom-file-input" id="profile">
                                    <label class="custom-file-label" for="profile">Profile</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                      <button class="btn btn-primary savebtn">Save Changes</button>
                    </div>
                    <div class="result"></div>
                  </form>
                </div>
              </div>

            </div>    
          </div>
        </section>
      </div>
    
    <?php $this->load->view('includes/footer'); ?>
    </div>
  </div>

<?php $this->load->view('includes/js'); ?>
</body>
</html>
