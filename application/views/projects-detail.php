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
              <a href="<?=base_url('projects')?>" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>
              Projects Detail
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>">Dashboard</a></div>
              <div class="breadcrumb-item active"><a href="<?=base_url('projects')?>">Projects</a></div>
              <div class="breadcrumb-item">Detail</div>
            </div>
          </div>
          <div class="section-body">
            <?php 
              if(isset($project[0]) && !empty($project[0])){
                $project = $project[0];
            ?>

            <?php if ($this->ion_auth->is_admin() || permissions('task_view')){ ?>
              <a href="<?=base_url("projects/tasks/".htmlspecialchars($project['id']))?>" class="btn btn-icon icon-left btn-primary"><i class="fas fa-layer-group"></i> Tasks</a>
            <?php } ?>
            
            <?php if ($this->ion_auth->is_admin() || permissions('project_edit')){ ?>
              <a href="#" data-edit="<?=htmlspecialchars($project['id'])?>" class="btn btn-icon icon-left btn-primary modal-edit-project"><i class="fas fa-edit"></i> Edit</a>
            <?php } ?>
            
            <?php if ($this->ion_auth->is_admin() || permissions('project_delete')){ ?>
              <a href="#" class="btn btn-icon icon-left btn-danger delete_project" data-id="<?=htmlspecialchars($project['id'])?>"><i class="fas fa-times"></i> Delete</a>
            <?php } ?>

              <div class="row mt-3">
              <div class="col-md-7">
                <div class="card author-box card-primary">
                  <div class="card-body">
                    <div class="author-box-name">
                      <a><?=htmlspecialchars($project['title'])?></a>
                    </div>
                    <div class="author-box-job text-<?=htmlspecialchars($project['project_class'])?>"><?=htmlspecialchars($project['project_status'])?></div>
                    <div class="author-box-description">
                      <p><?=htmlspecialchars($project['description'])?></p>
                    </div>
                    <?php if ($this->ion_auth->is_admin() || permissions('task_view')){ ?>
                      <div class="w-100 d-sm-none"></div>
                      <div class="float-right mt-sm-0 mt-3">
                        <a href="<?=base_url("projects/tasks/".htmlspecialchars($project['id']))?>" class="btn">View Tasks <i class="fas fa-chevron-right"></i></a>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              
              <?php if(!empty($project['project_client'])){ ?>
              
              <div class="col-md-5">
                <div class="card card-primary">
                  <div class="card-header">
                    <h4>Client Detail</h4>
                  </div>
                  <div class="card-body pb-0">
                    <div class="profile-widget mt-0">
                    <div class="profile-widget-header">
                      <div class="profile-widget-items">
                        <div class="profile-widget-item">
                          <div class="profile-widget-item-label">Name</div>
                          <div class="profile-widget-item-value"><?=htmlspecialchars($project['project_client']->first_name)?> <?=htmlspecialchars($project['project_client']->last_name)?></div>
                        </div>
                        <div class="profile-widget-item">
                          <div class="profile-widget-item-label">Comapany</div>
                          <div class="profile-widget-item-value"><?=htmlspecialchars($project['project_client']->company)?></div>
                        </div>
                      </div>
                    </div>
                    <div class="profile-widget-header">
                      <div class="profile-widget-items">
                        <div class="profile-widget-item">
                          <div class="profile-widget-item-label">Email</div>
                          <div class="profile-widget-item-value"><?=htmlspecialchars($project['project_client']->email)?></div>
                        </div>
                        <div class="profile-widget-item">
                          <div class="profile-widget-item-label">Mobile</div>
                          <div class="profile-widget-item-value"><?=$project['project_client']->phone?htmlspecialchars($project['project_client']->phone):'No Number'?></div>
                        </div>
                      </div>
                    </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php } ?>


              <div class="col-md-<?=!empty($project['project_client'])?12:5?>">
                <div class="card <?=!empty($project['project_client'])?'':'card-primary'?>">
                  <div class="card-header">
                    <h4>Task Overview</h4>
                  </div>
                  <div class="card-body pb-0">
                    <div class="profile-widget mt-0">
                    <div class="profile-widget-header">
                      <div class="profile-widget-items">
                        <div class="profile-widget-item">
                          <div class="profile-widget-item-label">Days <?=htmlspecialchars($project['days_status'])?></div>
                          <div class="profile-widget-item-value"><?=htmlspecialchars($project['days_count'])?></div>
                        </div>
                        <div class="profile-widget-item">
                          <div class="profile-widget-item-label">Starting Date</div>
                          <div class="profile-widget-item-value"><?=htmlspecialchars($project['starting_date'])?></div>
                        </div>
                        <div class="profile-widget-item">
                          <div class="profile-widget-item-label">Ending Date</div>
                          <div class="profile-widget-item-value"><?=htmlspecialchars($project['ending_date'])?></div>
                        </div>
                      </div>
                    </div>
                    <div class="profile-widget-header">
                      <div class="profile-widget-items">
                        <div class="profile-widget-item">
                          <div class="profile-widget-item-label">Total Tasks</div>
                          <div class="profile-widget-item-value"><?=htmlspecialchars($project['total_tasks'])?></div>
                        </div>
                        <div class="profile-widget-item">
                          <div class="profile-widget-item-label">Completed Tasks</div>
                          <div class="profile-widget-item-value"><?=htmlspecialchars($project['completed_tasks'])?></div>
                        </div>
                        <div class="profile-widget-item">
                          <div class="profile-widget-item-label">Pending Tasks</div>
                          <div class="profile-widget-item-value"><?=htmlspecialchars($project['total_tasks'])-htmlspecialchars($project['completed_tasks'])?></div>
                        </div>
                      </div>
                    </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card">
                  <div class="card-header">
                    <h4>Task Status</h4>
                  </div>
                  <div class="card-body">
                    <canvas id="project_statistics" height="auto"></canvas>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card">
                  <div class="card-header">
                    <h4>Project Users</h4>
                  </div>
                  <div class="card-body"> 
                    <table class='table-striped' id='users_list'
                      data-toggle="table"
                      data-url="<?=base_url('projects/get_project_users/'.$project['id'])?>"
                      data-click-to-select="true"
                      data-side-pagination="server"
                      data-pagination="false"
                      data-page-list="[5, 10, 20, 50, 100, 200]"
                      data-search="false" data-show-columns="false"
                      data-show-refresh="false" data-trim-on-search="false"
                      data-sort-name="first_name" data-sort-order="asc"
                      data-mobile-responsive="true"
                      data-toolbar="" data-show-export="false"
                      data-maintain-selected="true"
                      data-export-types='["txt","excel"]'
                      data-export-options='{
                        "fileName": "users-list",
                        "ignoreColumn": ["state"] 
                      }'
                      data-query-params="queryParams">
                      <thead>
                        <tr>
                          <th data-field="full_name" data-sortable="true">Name</th>
                          <th data-field="email" data-sortable="true">Email</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card">
                  <div class="card-header">
                    <h4>Upload Project Files</h4>
                  </div>
                  <div class="card-body">
                    <form action="<?=base_url('projects/upload-files/'.htmlspecialchars($project['id']))?>" class="dropzone" id="mydropzone">
                      <div class="fallback">
                        <input name="file" type="file" multiple />
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card">
                  <div class="card-header">
                    <h4>Project Files</h4>
                  </div>
                  <div class="card-body"> 
                    <table class='table-striped' id='file_list'
                      data-toggle="table"
                      data-url="<?=base_url('projects/get_project_files/'.htmlspecialchars($project['id']))?>"
                      data-click-to-select="true"
                      data-side-pagination="server"
                      data-pagination="false"
                      data-page-list="[5, 10, 20, 50, 100, 200]"
                      data-search="false" data-show-columns="false"
                      data-show-refresh="false" data-trim-on-search="false"
                      data-sort-name="first_name" data-sort-order="asc"
                      data-mobile-responsive="true"
                      data-toolbar="" data-show-export="false"
                      data-maintain-selected="true"
                      data-export-types='["txt","excel"]'
                      data-export-options='{
                        "fileName": "users-list",
                        "ignoreColumn": ["state"] 
                      }'
                      data-query-params="queryParams">
                      <thead>
                        <tr>
                          <th data-field="file_name" data-sortable="true">File</th>
                          <th data-field="file_size" data-sortable="true">Size</th>
                          <th data-field="action" data-sortable="false">Action</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
              
              <?php } ?>


            </div>   
          </div>
        </section>
      </div>
    
    <?php $this->load->view('includes/footer'); ?>
    </div>
  </div>

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
    <label>Project Users <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="Only this users are able to see this project."></i></label>
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

<?php
  foreach($task_status as $task_title){
    $tmpT[] =  htmlspecialchars($task_title['title']);
    if($this->ion_auth->is_admin()){
      $tmpTV[] =  get_count('id','tasks','status='.htmlspecialchars($task_title['id']).' AND project_id='.htmlspecialchars($this->uri->segment(3)));
    }elseif($this->ion_auth->in_group(4)){
      $tmpTV[] =  get_count('t.id','tasks t LEFT JOIN projects p on t.project_id = p.id','p.client_id = '.htmlspecialchars($this->session->userdata('user_id')).' AND t.status = '.htmlspecialchars($task_title['id']).' AND t.project_id='.htmlspecialchars($this->uri->segment(3)));
    }else{
      $tmpTV[] =  get_count('t.id','tasks t LEFT JOIN task_users tu ON t.id=tu.task_id','status='.$task_title['id'].' AND tu.user_id='.htmlspecialchars($this->session->userdata('user_id')).' AND project_id='.htmlspecialchars($this->uri->segment(3)));
    }
  }

?>

<?php $this->load->view('includes/js'); ?>

<script>
  project_id = "<?=htmlspecialchars($project['id'])?>";
  
  task_status = '<?=json_encode($tmpT)?>';
  task_status_values = '<?=json_encode($tmpTV)?>';

</script>
<script src="<?=base_url('assets/js/page/projects-details.js')?>"></script>

</body>
</html>
