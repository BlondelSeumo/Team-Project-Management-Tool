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
            <?php
              if($this->ion_auth->in_group(3)){
                echo 'Subscription Plans <a href="#" id="modal-add-plan" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> Create</a>';
              }else{
                echo 'My Subscription and Billing';
              }
            ?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>">Dashboard</a></div>
              <div class="breadcrumb-item">
              Subscription Plans
              </div>
            </div>
          </div>
          <div class="section-body">
            
            <div class="row align-items-center justify-content-center">

              <?php if($this->ion_auth->in_group(3)){ ?>
                
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-body"> 
                        <table class='table-striped' id='plans_list'
                          data-toggle="table"
                          data-url="<?=base_url('plans/get_plans')?>"
                          data-click-to-select="true"
                          data-side-pagination="server"
                          data-pagination="false"
                          data-page-list="[5, 10, 20, 50, 100, 200]"
                          data-search="false" data-show-columns="false"
                          data-show-refresh="false" data-trim-on-search="false"
                          data-sort-name="id" data-sort-order="asc"
                          data-mobile-responsive="true"
                          data-toolbar="" data-show-export="false"
                          data-maintain-selected="true"
                          data-export-types='["txt","excel"]'
                          data-export-options='{
                            "fileName": "plans-list",
                            "ignoreColumn": ["state"] 
                          }'
                          data-query-params="queryParams">
                          <thead>
                            <tr>
                              <th data-field="title" data-sortable="true">Title</th>
                              <th data-field="price" data-sortable="true">Price (USD)</th>
                              <th data-field="billing_type" data-sortable="true">Billing Type</th>
                              <th data-field="features" data-sortable="true">Features</th>
                              <th data-field="action" data-sortable="false">Action</th>
                            </tr>
                          </thead>
                        </table>
                      </div>
                    </div>
                  </div>
              <?php }else{ 
                $my_plan = get_current_plan();
                if($this->ion_auth->is_admin()){ 
                if($my_plan && ($my_plan['expired'] == 0 || $my_plan['end_date'] <= date('Y-m-d',date(strtotime("+2 day", strtotime(date('Y-m-d'))))))){ 
              ?>
                  <div class="col-md-12 mb-4">
                    <div class="hero text-white bg-danger">
                      <div class="hero-inner">
                        <h2>Alert...</h2>
                        <?php 
                          if($my_plan['expired'] == 0){ 
                        ?>
                          <p class="lead">Your subscription plan has been expired on <?=htmlspecialchars(format_date($my_plan["end_date"],system_date_format()))?>. Renew it now.</p>
                        <?php }elseif($my_plan['end_date'] <= date('Y-m-d',date(strtotime("+2 day", strtotime(date('Y-m-d')))))){ ?>
                          <p class="lead">Your current subscription plan is expiring on <?=htmlspecialchars(format_date($my_plan["end_date"],system_date_format()))?>.</p>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
              <?php } } 
                foreach($plans as $plan){
              ?>
                  <div class="col-md-4">
                    <div class="pricing card <?=$my_plan['plan_id'] == $plan['id']?'pricing-highlight':''?>">
                      <div class="pricing-title">
                        <?=htmlspecialchars($plan['title'])?> 

                        <?php if($my_plan['plan_id'] == $plan['id']){ ?>
                          <i class="fas fa-question-circle text-success" data-toggle="tooltip" data-placement="right" title="Your current active plan is <?=htmlspecialchars($plan["title"])?> and Expiring on <?=htmlspecialchars(format_date($my_plan["end_date"],system_date_format()))?>."></i>
                        <?php } ?>

                      </div>
                      <div class="pricing-padding">
                        <div class="pricing-price">
                          <div>$ <?=htmlspecialchars($plan['price'])?></div>
                          <div><?=htmlspecialchars($plan['billing_type'])?></div>
                        </div>
                        <div class="pricing-details">
                          <div class="pricing-item">
                            <div class="pricing-item-label mr-1 font-weight-bold">Projects</div>
                            <div class="badge badge-primary"><?=$plan['projects']<0?'Unlimited':htmlspecialchars($plan['projects'])?></div>
                          </div>
                          <div class="pricing-item">
                            <div class="pricing-item-label mr-1 font-weight-bold">Tasks</div>
                            <div class="badge badge-primary"><?=$plan['tasks']<0?'Unlimited':htmlspecialchars($plan['tasks'])?></div>
                          </div>
                          <div class="pricing-item">
                            <div class="pricing-item-label mr-1 font-weight-bold">Users <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="Including Admins, Clients and Users."></i></div>
                            <div class="badge badge-primary"><?=$plan['users']<0?'Unlimited':htmlspecialchars($plan['users'])?></div>
                          </div>
                        </div>
                      </div>
                      <div class="pricing-cta">
                        <a href="#" class="paypal-button" data-amount="<?=htmlspecialchars($plan['price'])?>" data-id="<?=htmlspecialchars($plan['id'])?>"><?=$my_plan['plan_id'] == $plan['id']?'Renew Plan':'Subscribe'?> <i class="fas fa-arrow-right"></i></a>
                      </div>
                    </div>
                  </div>
              <?php } } ?>
              
              <div id="paypal-button" class="col-md-8"></div>

          </div>
        </section>
      </div>
    
    <?php $this->load->view('includes/footer'); ?>
    </div>
  </div>


<form action="<?=base_url('plans/create')?>" method="POST" class="modal-part" id="modal-add-plan-part">
  <div class="row">
    <div class="form-group col-md-12">
      <label>Title<span class="text-danger">*</span></label>
      <input type="text" name="title" class="form-control" required="">
    </div>
    <div class="form-group col-md-6">
      <label>Price (USD)<span class="text-danger">*</span></label>
      <input type="number" name="price" class="form-control">
    </div>
    
    <div class="form-group col-md-6">
      <label>Billing Type<span class="text-danger">*</span></label>
      <select name="billing_type" class="form-control select2">
        <option value="Monthly">Monthly</option>
        <option value="Yearly">Yearly</option>
      </select>
    </div>

    <div class="form-group col-md-6">
      <label>Projects<span class="text-danger">*</span></label>
      <input type="number" name="projects"  class="form-control">
    </div>

    <div class="form-group col-md-6">
      <label>Tasks<span class="text-danger">*</span></label>
      <input type="number" name="tasks"  class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label>Users<span class="text-danger">*</span></label>
      <input type="number" name="users"  class="form-control">
    </div>
    <div class="form-group col-md-12">
      <small class="form-text text-muted">
        Set value in minus (-1) to make it Unlimited.
      </small>
    </div>
  </div>
</form>

<div id="modal-edit-plan"></div>
<form action="<?=base_url('plans/edit')?>" method="POST" class="modal-part" id="modal-edit-plan-part">
  <div class="row">
    <div class="form-group col-md-12">
      <label>Title<span class="text-danger">*</span></label>
      <input type="hidden" name="update_id" id="update_id">
      <input type="text" name="title" id="title" class="form-control" required="">
    </div>
    <div class="form-group col-md-6">
      <label>Price (USD)<span class="text-danger">*</span></label>
      <input type="number" name="price" id="price" class="form-control">
    </div>
    
    <div class="form-group col-md-6">
      <label>Billing Type<span class="text-danger">*</span></label>
      <select name="billing_type" id="billing_type" class="form-control select2">
        <option value="Monthly">Monthly</option>
        <option value="Yearly">Yearly</option>
      </select>
    </div>

    <div class="form-group col-md-6">
      <label>Projects<span class="text-danger">*</span></label>
      <input type="number" name="projects" id="projects" class="form-control">
    </div>

    <div class="form-group col-md-6">
      <label>Tasks<span class="text-danger">*</span></label>
      <input type="number" name="tasks" id="tasks" class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label>Users<span class="text-danger">*</span></label>
      <input type="number" name="users" id="users" class="form-control">
    </div>
    <div class="form-group col-md-12">
      <small class="form-text text-muted">
        Set value in minus (-1) to make it Unlimited.
      </small>
    </div>
  </div>
</form>

<?php $this->load->view('includes/js'); ?>

<script>
paypal_client_id = "<?=get_payment_paypal()?>";
</script>

<?php if(get_payment_paypal()){ ?>
<script src="https://www.paypal.com/sdk/js?client-id=<?=get_payment_paypal()?>"></script>
<?php } ?>

<script src="<?=base_url('assets/js/page/payment.js');?>"></script>
</body>
</html>
