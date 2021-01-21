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
              Users and Plan <a href="#" id="modal-add-user" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> Create</a>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>">Dashboard</a></div>
              <div class="breadcrumb-item">Users and Plan</div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">

                <div class="col-md-12">
                    <div class="card">
                      <div class="card-body"> 
                        <div id="tool">
                          <select id='filter' class="form-control" >
                            <option value="all">All</option>
                            <?php foreach($plans as $plan){ ?>
                              <option value="<?=htmlspecialchars($plan['id'])?>"><?=htmlspecialchars($plan['title'])?></option>
                            <?php } ?>
                          </select>
                        </div>
                        <table class='table-striped' id='users_list'
                          data-toggle="table"
                          data-url="<?=base_url('users/get_saas_users')?>"
                          data-click-to-select="true"
                          data-side-pagination="server"
                          data-pagination="true"
                          data-page-list="[5, 10, 20, 50, 100, 200]"
                          data-search="true" data-show-columns="true"
                          data-show-refresh="false" data-trim-on-search="false"
                          data-sort-name="id" data-sort-order="DESC"
                          data-mobile-responsive="true"
                          data-toolbar="#tool" data-show-export="true"
                          data-maintain-selected="true"
                          data-export-types='["txt","excel"]'
                          data-export-options='{
                            "fileName": "users-list",
                            "ignoreColumn": ["state"] 
                          }'
                          data-query-params="queryParams">
                          <thead>
                            <tr>
                              <th data-field="first_name" data-sortable="true">User</th>
                              <th data-field="plan" data-sortable="false">Plan</th>
                              <th data-field="features" data-sortable="false">Feature Usage</th>
                              <th data-field="status" data-sortable="false">Status</th>
                              <th data-field="role" data-sortable="false" data-visible="false">role</th>
                              <th data-field="phone" data-sortable="false" data-visible="false">phone</th>
                              <th data-field="action" data-sortable="false">Action</th>
                            </tr>
                          </thead>
                        </table>
                      </div>
                    </div>
                  </div>

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
    <input type="hidden" name="groups" value="1">
    
  </div>
</form>

<form action="<?=base_url('auth/edit-user')?>" method="POST" class="modal-part" id="modal-edit-user-part">
  <input type="hidden" name="update_id" id="update_id" value="">
  <input type="hidden" name="groups" value="1">
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
      <label>User Plan</label>
      <select name="plan_id" id="plan_id" class="form-control select2">
        <?php foreach ($plans as $plan) { ?>
          <option value="<?=htmlspecialchars($plan['id'])?>"><?=ucfirst(htmlspecialchars($plan['title']))?></option>
        <?php } ?>
      </select>
    </div>
    <div class="form-group col-md-6">
      <label>Plan Expiry Date<span class="text-danger">*</span></label>
      <input type="text" id="end_date" name="end_date" class="form-control datepicker">
    </div>
  </div>
</form>
<div id="modal-edit-user"></div>
<?php $this->load->view('includes/js'); ?>
<script src="<?=base_url('assets/js/page/saas-users.js')?>"></script>
</body>
</html>
