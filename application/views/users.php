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
              Users 
              <?php if(my_plan_features('users')){ if ($this->ion_auth->is_admin()){ ?> 
                <a href="#" id="modal-add-user" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> Create</a>
              <?php } } ?> 
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>">Dashboard</a></div>
              <div class="breadcrumb-item">Users</div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">
              <?php
                if(isset($system_users) && !empty($system_users)){
                foreach ($system_users as $system_user) {
              ?>
              <div class="col-md-6">
                <div class="card profile-widget">
                  <div class="profile-widget-header mb-0">  
                    <span class="avatar-item mb-0"> 
                    <?php
                      if(isset($system_user['profile']) && !empty($system_user['profile'])){
                    ?>       
                      <img alt="image" src="<?=htmlspecialchars($system_user['profile'])?>" class="rounded-circle profile-widget-picture">
                    <?php }else{ ?>
                      <figure class="user-avatar avatar avatar-xl rounded-circle profile-widget-picture" data-initial="<?=htmlspecialchars($system_user['short_name'])?>"></figure>
                    <?php } ?>
                    <?php if ($this->ion_auth->is_admin() || $this->ion_auth->in_group(3)){ ?>
                      <a href="#" data-edit="<?=htmlspecialchars($system_user['id'])?>" class="avatar-badge modal-edit-user text-white" title="Edit" data-toggle="tooltip"><i class="fas fa-pencil-alt"></i></a>
                    <?php } ?>
                    </span> 
                    <div class="profile-widget-items">
                      <div class="profile-widget-item">
                        <div class="profile-widget-item-label">Projects</div>
                        <div class="profile-widget-item-value"><span class="badge badge-secondary"><?=htmlspecialchars($system_user['projects_count'])?></span></div>
                      </div>
                      <div class="profile-widget-item">
                        <div class="profile-widget-item-label">Tasks</div>
                        <div class="profile-widget-item-value"><span class="badge badge-secondary"><?=htmlspecialchars($system_user['tasks_count'])?></span></div>
                      </div>
                      <div class="profile-widget-item">
                        <div class="profile-widget-item-label">Status</div>
                        <div class="profile-widget-item-value"><?=htmlspecialchars($system_user['active'])==1?'<span class="badge badge-success">Active</span>':'<span class="badge badge-danger">Deactive</span>'?></div>
                      </div>
                    </div>
                  </div>
                  <div class="profile-widget mt-0">
                    <div class="profile-widget-header mb-0">
                      <div class="profile-widget-items">
                        <div class="profile-widget-item">
                          <div class="profile-widget-item-label">Name</div>
                          <div class="profile-widget-item-value mt-1">
                            <?=htmlspecialchars($system_user['first_name'])?> <?=htmlspecialchars($system_user['last_name'])?>
                          </div>
                        </div>
                        <div class="profile-widget-item">
                          <div class="profile-widget-item-label">Email</div>
                          <div class="profile-widget-item-value"><?=htmlspecialchars($system_user['email'])?></div>
                        </div>
                      </div>
                      <div class="profile-widget-items">
                        <div class="profile-widget-item">
                          <div class="profile-widget-item-label">Mobile</div>
                          <div class="profile-widget-item-value"><?=htmlspecialchars($system_user['phone'])?></div>
                        </div>
                        <div class="profile-widget-item">
                          <div class="profile-widget-item-label">Role</div>
                          <div class="profile-widget-item-value"><?=htmlspecialchars($system_user['role'])?></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php
                } }
              ?>

            </div>    
          </div>
        </section>
      </div>
    
    <?php $this->load->view('includes/footer'); ?>
    </div>
  </div>

<form action="<?=base_url('auth/create-user')?>" method="POST" class="modal-part" id="modal-add-user-part">
  <div class="row">
    <div class="form-group col-md-6">
      <label>First Name<span class="text-danger">*</span></label>
      <input type="text" name="first_name" class="form-control" required="">
    </div>
    <div class="form-group col-md-6">
      <label>Last Name<span class="text-danger">*</span></label>
      <input type="text" name="last_name" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label>Email<span class="text-danger">*</span> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="This email will not be updated latter."></i></label>
      <input type="email" name="email"  class="form-control">
    </div>

    <div class="form-group col-md-6">
      <label>Mobile</label>
      <input type="text" name="phone"  class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label>Password<span class="text-danger">*</span></label>
      <input type="text" name="password"  class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label>Confirm Password<span class="text-danger">*</span></label>
      <input type="text" name="password_confirm"  class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label>User Role<span class="text-danger">*</span> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="Select user role like admin or team member."></i></label>
      <select name="groups" class="form-control select2">
        <?php foreach ($user_groups as $user_group) { ?>
          <option value="<?=htmlspecialchars($user_group->id)?>"><?=ucfirst(htmlspecialchars($user_group->name))?></option>
        <?php } ?>
      </select>
    </div>
  </div>
</form>

<form action="<?=base_url('auth/edit-user')?>" method="POST" class="modal-part" id="modal-edit-user-part">
  <input type="hidden" name="update_id" id="update_id" value="">
  <input type="hidden" name="old_profile_pic" id="old_profile_pic" value="">
  <div class="row">
    <div class="form-group col-md-6">
      <label>First Name<span class="text-danger">*</span></label>
      <input type="text" id="first_name" name="first_name" class="form-control" required="">
    </div>
    <div class="form-group col-md-6">
      <label>Last Name<span class="text-danger">*</span></label>
      <input type="text" id="last_name" name="last_name" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label>Mobile</label>
      <input type="text" id="phone" name="phone" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label>Password <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="Leave Password and Confirm Password empty for no change in Password."></i></label>
      <input type="text" name="password"  class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label>Confirm Password <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="Leave Password and Confirm Password empty for no change in Password."></i></label>
      <input type="text" name="password_confirm"  class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label>User Role<span class="text-danger">*</span> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="Select user role like admin or team member."></i></label>
      <select name="groups" id="groups" class="form-control select2">
        <?php foreach ($user_groups as $user_group) { ?>
          <option value="<?=htmlspecialchars($user_group->id)?>"><?=ucfirst(htmlspecialchars($user_group->name))?></option>
        <?php } ?>
      </select>
    </div>
  </div>
</form>
<div id="modal-edit-user"></div>
<?php $this->load->view('includes/js'); ?>
</body>
</html>
