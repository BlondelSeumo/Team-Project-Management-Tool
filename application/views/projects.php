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
              Projects 
              <?php if(my_plan_features('projects')){  if ($this->ion_auth->is_admin() || permissions('project_create')){ ?>  
                <a href="#" id="modal-add-project" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> Create</a>
              <?php } } ?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>">Dashboard</a></div>
              <div class="breadcrumb-item">Projects</div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">
              <div class="form-group col-md-3">
                <select class="form-control select2 project_filter">
                  <option value="<?=base_url("projects")?>">Select Status</option>
                  <?php foreach($project_status as $status){ ?>
                  <option value="<?=base_url("projects?status=".htmlspecialchars($status['id']))?>" <?=(isset($_GET['status']) && !empty($_GET['status']) && is_numeric($_GET['status']) && $_GET['status'] == $status['id'])?"selected":""?>><?=htmlspecialchars($status['title'])?></option>
                  <?php } ?>
                </select>
              </div>
              
              <?php if(!$this->ion_auth->in_group(4)){ ?>
              <div class="form-group col-md-3">
                <select class="form-control select2 project_filter">
                  <option value="<?=base_url("projects")?>">Select Users</option>
                  <?php foreach($system_users as $system_user){ ?>
                  <option value="<?=base_url("projects?user=".htmlspecialchars($system_user->id))?>" <?=(isset($_GET['user']) && !empty($_GET['user']) && is_numeric($_GET['user']) && $_GET['user'] == $system_user->id)?"selected":""?>><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
                  <?php } ?>
                </select>
              </div>
              
              <div class="form-group col-md-3">
                <select class="form-control select2 project_filter">
                  <option value="<?=base_url("projects")?>">Select Clients</option>
                  <?php foreach($system_clients as $system_client){ ?>
                  <option value="<?=base_url("projects?client=".htmlspecialchars($system_client->id))?>" <?=(isset($_GET['client']) && !empty($_GET['client']) && is_numeric($_GET['client']) && $_GET['client'] == $system_client->id)?"selected":""?>><?=htmlspecialchars($system_client->first_name)?> <?=htmlspecialchars($system_client->last_name)?></option>
                  <?php } ?>
                </select>
              </div>
              <?php } ?>

              <div class="form-group col-md-3">
                <select class="form-control select2 project_filter">
                  <option value="<?=base_url("projects")?>">Sort By</option>
                  <option value="<?=base_url("projects?sortby=latest")?>" <?=(isset($_GET['sortby']) && !empty($_GET['sortby']) && $_GET['sortby'] == 'latest')?"selected":""?>>Latest</option>
                  <option value="<?=base_url("projects?sortby=old")?>" <?=(isset($_GET['sortby']) && !empty($_GET['sortby']) && $_GET['sortby'] == 'old')?"selected":""?>>Old</option>
                  <option value="<?=base_url("projects?sortby=name")?>" <?=(isset($_GET['sortby']) && !empty($_GET['sortby']) && $_GET['sortby'] == 'name')?"selected":""?>>Name</option>
                </select>
              </div>
            </div>
            <div class="row">

              <?php
              if(isset($projects) && !empty($projects)){
              foreach($projects as $project){
              ?>
              <div class="col-md-6">
                <div class="card card-primary">
                  <div class="card-body">
                    <ul class="list-unstyled list-unstyled-border list-unstyled-noborder mb-0">
                      <li class="media">
                        <div class="media-body">
                          <div class="media-right"><div class="text-<?=htmlspecialchars($project['project_class'])?>"><?=htmlspecialchars($project['project_status'])?></div></div>
                          <div class="media-title mb-1"><a href="<?=base_url('projects/detail/'.htmlspecialchars($project['id']))?>"><?=htmlspecialchars($project['title'])?></a></div>
                          <div class="author-box-job mb-2">
                          
                            <?php if(!empty($project['project_client'])){ ?>
                              <i class="fas fa-user"></i> <?=htmlspecialchars($project['project_client']->first_name)?> <?=htmlspecialchars($project['project_client']->last_name)?>
                              <span class="mr-2"></span>
                            <?php } ?>

                            <i class="fas fa-calendar-alt"></i> <?=htmlspecialchars($project['days_count'])?> Days <?=htmlspecialchars($project['days_status'])?>
                            <span class="mr-2"></span>
                            <i class="fas fa-layer-group"></i> <?=htmlspecialchars($project['completed_tasks'])?>/<?=htmlspecialchars($project['total_tasks'])?> Task Completed
                          </div>
                          <div class="media-description text-muted"><?=mb_substr(htmlspecialchars($project['description']), 0, 100, "utf-8").'...'?></div>
                          
                            <?php if(!empty($project['project_users'])){ ?>
                              <div class="mt-2 mb-2">
                                <?php foreach($project['project_users'] as $project_user){ 
                                  if(!empty($project_user['profile'])){
                                ?>
                                  <figure class="avatar avatar-sm">
                                    <img src="<?=base_url(UPLOAD_PROFILE.''.htmlspecialchars($project_user['profile']))?>" alt="<?=htmlspecialchars($project_user['first_name'])?> <?=htmlspecialchars($project_user['last_name'])?>" data-toggle="tooltip" data-placement="top" title="<?=htmlspecialchars($project_user['first_name'])?> <?=htmlspecialchars($project_user['last_name'])?>">
                                  </figure>
                                <?php }else{ ?>
                                  <figure class="avatar avatar-sm bg-primary text-white" data-initial="<?=ucfirst(mb_substr(htmlspecialchars($project_user['first_name']), 0, 1, 'utf-8')).''.ucfirst(mb_substr(htmlspecialchars($project_user['last_name']), 0, 1, 'utf-8'))?>" data-toggle="tooltip" data-placement="top" title="<?=htmlspecialchars($project_user['first_name'])?> <?=htmlspecialchars($project_user['last_name'])?>">
                                  </figure>
                              <?php } } ?>
                              </div>
                            <?php } ?>
                            
                          <div class="media-links mt-2">
                            <a href="<?=base_url("projects/detail/".htmlspecialchars($project['id']))?>">Details</a>

                            <?php if ($this->ion_auth->is_admin() || permissions('project_edit')){ ?>  
                              <div class="bullet"></div>
                              <a href="#" data-edit="<?=htmlspecialchars($project['id'])?>" class="modal-edit-project">Edit</a>
                            <?php } ?>
                            
                            <?php if ($this->ion_auth->is_admin() || permissions('task_view')){ ?>
                              <div class="bullet"></div>
                              <a href="<?=base_url("projects/tasks/".htmlspecialchars($project['id']))?>">Tasks</a>
                            <?php } ?>
                            
                            <?php if ($this->ion_auth->is_admin() || permissions('project_delete')){ ?>
                              <div class="bullet"></div>
                              <a href="#" class="text-danger delete_project" data-id="<?=htmlspecialchars($project['id'])?>">Trash</a>
                            <?php } ?>

                          </div>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <?php } } ?>

            </div>
            <div class="row">
              <div class="col-md-12">
              <!-- Pagination links with HTML -->
              <?php echo $links; ?>
              
              </div>
            </div>    
          </div>
        </section>
      </div>
    
    <?php $this->load->view('includes/footer'); ?>
    </div>
  </div>

<form action="<?=base_url('projects/create-project')?>" method="POST" class="modal-part" id="modal-add-project-part">
  <div class="form-group">
    <label>Project Title<span class="text-danger">*</span></label>
    <input type="text" name="title" class="form-control" required="">
  </div>
  <div class="form-group">
    <label>Description<span class="text-danger">*</span></label>
    <textarea type="text" name="description" class="form-control"></textarea>
  </div>
  <div class="form-group">
    <label>Starting Date<span class="text-danger">*</span></label>
    <input type="text" name="starting_date"  class="form-control datepicker">
  </div>

  <div class="form-group">
    <label>Ending Date<span class="text-danger">*</span></label>
    <input type="text" name="ending_date"  class="form-control datepicker">
  </div>

  <div class="form-group">
    <label>Status<span class="text-danger">*</span></label>
    <select name="status" class="form-control select2">
      <?php foreach($project_status as $status){ ?>
      <option value="<?=htmlspecialchars($status['id'])?>"><?=htmlspecialchars($status['title'])?></option>
      <?php } ?>
    </select>
  </div>

  <div class="form-group">
    <label>Project Users <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="Add users who will work on this project. Only this users are able to see this project."></i></label>
    <select name="users[]" class="form-control select2" multiple="">
      <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
      <option value="<?=htmlspecialchars($system_user->id)?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
      <?php } } ?>
    </select>
  </div>
  <div class="form-group">
    <label>Project Client</label>
    <select name="client" class="form-control select2">
      <option value="">Select Client</option>
      <?php foreach($system_clients as $system_client){ if($system_client->saas_id == $this->session->userdata('saas_id')){ ?>
      <option value="<?=htmlspecialchars($system_client->id)?>"><?=htmlspecialchars($system_client->first_name)?> <?=htmlspecialchars($system_client->last_name)?></option>
      <?php } } ?>
    </select>
  </div>
</form>

<form action="<?=base_url('projects/edit-project')?>" method="POST"  class="modal-part" id="modal-edit-project-part">
  <input type="hidden" name="update_id" id="update_id">
  <div class="form-group">
    <label>Project Title<span class="text-danger">*</span></label>
    <input type="text" name="title" id="title" class="form-control" required="">
  </div>
  <div class="form-group">
    <label>Description<span class="text-danger">*</span></label>
    <textarea type="text" name="description" id="description" class="form-control"></textarea>
  </div>
  <div class="form-group">
    <label>Starting Date<span class="text-danger">*</span></label>
    <input type="text" name="starting_date" id="starting_date" class="form-control datepicker">
  </div>

  <div class="form-group">
    <label>Ending Date<span class="text-danger">*</span></label>
    <input type="text" name="ending_date" id="ending_date" class="form-control datepicker">
  </div>

  <div class="form-group">
    <label>Status<span class="text-danger">*</span></label>
    <select name="status" id="status" class="form-control select2">
      <?php foreach($project_status as $status){ ?>
      <option value="<?=htmlspecialchars($status['id'])?>"><?=htmlspecialchars($status['title'])?></option>
      <?php } ?>
    </select>
  </div>

  <div class="form-group">
    <label>Project Users <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="Add users who will work on this project. Only this users are able to see this project."></i></label>
    <select name="users[]" id="users" class="form-control select2" multiple="">
      <?php foreach($system_users as $system_user){ if($system_user->saas_id == $this->session->userdata('saas_id')){ ?>
      <option value="<?=htmlspecialchars($system_user->id)?>"><?=htmlspecialchars($system_user->first_name)?> <?=htmlspecialchars($system_user->last_name)?></option>
      <?php } } ?>
    </select>
  </div>
  <div class="form-group">
    <label>Project Client</label>
    <select name="client" id="client" class="form-control select2">
      <option value="">Select Client</option>
      <?php foreach($system_clients as $system_client){ if($system_client->saas_id == $this->session->userdata('saas_id')){ ?>
      <option value="<?=htmlspecialchars($system_client->id)?>"><?=htmlspecialchars($system_client->first_name)?> <?=htmlspecialchars($system_client->last_name)?></option>
      <?php } } ?>
    </select>
  </div>
</form>

<div id="modal-edit-project"></div>
<?php $this->load->view('includes/js'); ?>
</body>
</html>
