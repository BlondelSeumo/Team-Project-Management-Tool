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
              <a href="<?=base_url('projects'.((isset($project_id) && !empty($project_id))?'/detail/'.htmlspecialchars($project_id):''))?>" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>
              Tasks 
              <?php
              if(my_plan_features('tasks')){ if ($this->ion_auth->is_admin() || permissions('task_create')){ ?>
                <a href="#" id="modal-add-task" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> Create</a>
              <?php } } ?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>">Dashboard</a></div>
              <div class="breadcrumb-item active"><a href="<?=base_url('projects')?>">Projects</a></div>
              <div class="breadcrumb-item">Tasks</div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">
            
            <?php if(isset($task_status) && count($task_status)>0){ 
              foreach($task_status as $key => $status){ 
                $temp[$key] = $status['id'];
              } ?>
              <div class="col-md-12">
                <div class="kanban" data-plugin="dragula" data-containers='<?=json_encode($temp)?>'>
                  <?php foreach($task_status as $status){ ?>
                    <div class="tasks animated" data-sr-id="<?=htmlspecialchars($status['id'])?>" >
                      <div class="mt-0 task-header"><?=htmlspecialchars($status['title'])?>(
                        <span class="count">
                          <?php 
                            if(isset($tasks) && !empty($tasks)){ 
                              if($this->ion_auth->is_admin()){ 
                                echo get_count('id','tasks',(htmlspecialchars($this->uri->segment(3))?'project_id = '.htmlspecialchars($this->uri->segment(3)).' AND status = '.htmlspecialchars($status['id']).' AND saas_id = '.htmlspecialchars($this->session->userdata('saas_id')):'status = '.htmlspecialchars($status['id']).' AND saas_id = '.htmlspecialchars($this->session->userdata('saas_id'))));
                              }elseif($this->ion_auth->in_group(4)){ 
                                echo get_count('t.id','tasks t LEFT JOIN projects p on t.project_id = p.id',(htmlspecialchars($this->uri->segment(3))?' p.client_id = '.htmlspecialchars($this->session->userdata('user_id')).' AND t.project_id = '.htmlspecialchars($this->uri->segment(3)).' AND t.status = '.htmlspecialchars($status['id']):' p.client_id = '.htmlspecialchars($this->session->userdata('user_id')).' AND t.status = '.htmlspecialchars($status['id'])));
                              }else{
                                echo get_count('t.id','task_users tu LEFT JOIN tasks t on tu.task_id = t.id',(htmlspecialchars($this->uri->segment(3))?'tu.user_id = '.htmlspecialchars($this->session->userdata('user_id')).' AND t.project_id = '.htmlspecialchars($this->uri->segment(3)).' AND t.status = '.htmlspecialchars($status['id']).' AND saas_id = '.htmlspecialchars($this->session->userdata('saas_id')):'tu.user_id = '.htmlspecialchars($this->session->userdata('user_id')).' AND t.status = '.htmlspecialchars($status['id']).' AND saas_id = '.htmlspecialchars($this->session->userdata('saas_id'))));
                              } 
                            }else{
                              echo 0;
                            } 
                          ?>
                        </span>
                        )
                      </div>
                      <div id="<?=htmlspecialchars($status['id'])?>" data-status="<?=htmlspecialchars($status['id'])?>" class="task-list-items">

                          <?php if(isset($tasks) && !empty($tasks)){ foreach($tasks as $task){ if($status['title'] == $task['task_status']){ ?>
                            <div class="card card-primary mt-1 mb-1" data-id="<?=htmlspecialchars($task['id'])?>">
                              <div class="card-body">
                                <ul class="list-unstyled list-unstyled-border list-unstyled-noborder mb-0">
                                  <li class="media">
                                    <div class="media-body">
                                      <div class="media-right"><div class="text-<?=htmlspecialchars($task['priority_class'])?>"><?=htmlspecialchars($task['task_priority'])?></div></div>
                                      <div class="media-title mb-1"><a href="#" data-edit="<?=htmlspecialchars($task['id'])?>" class="modal-task-detail"><?=htmlspecialchars($task['title'])?></a></div>
                                      <div class="author-box-job mb-2">
                                        <i class="fas fa-calendar-alt"></i> <?=htmlspecialchars($task['days_count'])?> Days <?=htmlspecialchars($task['days_status'])?> 
                                      </div>

                                      <?php  if(!empty($task['task_users'])){ ?>
                                        <div class="mt-2 mb-2">
                                          <?php foreach($task['task_users'] as $task_user){ 
                                            if(!empty($task_user['profile'])){
                                          ?>
                                            <figure class="avatar avatar-sm">
                                              <img src="<?=base_url(UPLOAD_PROFILE.''.htmlspecialchars($task_user['profile']))?>" alt="<?=htmlspecialchars($task_user['first_name'])?> <?=htmlspecialchars($task_user['last_name'])?>" data-toggle="tooltip" data-placement="top" title="<?=htmlspecialchars($task_user['first_name'])?> <?=htmlspecialchars($task_user['last_name'])?>">
                                            </figure>
                                          <?php }else{ ?>
                                            <figure class="avatar avatar-sm bg-primary text-white" data-initial="<?=ucfirst(mb_substr(htmlspecialchars($task_user['first_name']), 0, 1, 'utf-8')).''.ucfirst(mb_substr(htmlspecialchars($task_user['last_name']), 0, 1, 'utf-8'))?>" data-toggle="tooltip" data-placement="top" title="<?=htmlspecialchars($task_user['first_name'])?> <?=htmlspecialchars($task_user['last_name'])?>">
                                            </figure>
                                        <?php } } ?>
                                        </div>
                                      <?php } ?>

                                      <div class="media-links mt-2">

                                        <a href="#" data-edit="<?=htmlspecialchars($task['id'])?>" class="modal-task-detail">Details</a>

                                        <?php if ($this->ion_auth->is_admin() || permissions('task_edit')){ ?>
                                          <div class="bullet"></div>
                                          <a href="#" data-edit="<?=htmlspecialchars($task['id'])?>" class="modal-edit-task">Edit</a>
                                        <?php } ?>

                                        <?php if ($this->ion_auth->is_admin() || permissions('task_delete')){ ?>
                                          <div class="bullet"></div>
                                          <a href="#" class="text-danger delete_task" data-id="<?=htmlspecialchars($task['id'])?>">Trash</a>
                                        <?php } ?>

                                      </div>
                                    </div>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          <?php } } } ?>


                      </div>
                    </div>
                  <?php } ?>
                  
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

<form action="<?=base_url('projects/create-comment')?>" method="POST" class="modal-part" id="modal-task-detail-part">
  <div class="row">
    <div class="col-md-12">
      <div class="card author-box mb-0">
        <div class="card-body p-0">

          <ul class="list-unstyled list-unstyled-border list-unstyled-noborder mb-0">
            <li class="media mb-0">
              <div class="media-body">
                <div class="media-right"><div class="" id="task_priority">Priority</div></div>
                <div class="media-title mb-0"><h5 id="task_title">Task 1</h5></div>
                <div class="author-box-job mb-2">
                  <a target="_blank" href="#" id="task_project">Maim Project</a>
                </div>
                <div class="media-description" id="task_description">Duis aute irure dolor in reprehenderit in voluptate velit esse
                cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
               
              </div>
            </li>
          </ul>
          <div class="profile-widget mt-0">
            <div class="profile-widget-header">
              <div class="profile-widget-items">
                <div class="profile-widget-item">
                  <div class="profile-widget-item-label">Users</div>
                  <div class="profile-widget-item-value mt-1" id="task_users">
                    <figure class="avatar avatar-sm bg-primary text-white" data-initial="UM" data-toggle="tooltip" data-placement="top" title="Mithun Parmar"></figure>
                    <figure class="avatar avatar-sm bg-primary text-white" data-initial="UM" data-toggle="tooltip" data-placement="top" title="Mithun Parmar"></figure>
                  </div>
                </div>
                <div class="profile-widget-item">
                  <div class="profile-widget-item-label" id="task_days_status">Day Left</div>
                  <div class="profile-widget-item-value" id="task_days_count">10</div>
                </div>
                <div class="profile-widget-item">
                  <div class="profile-widget-item-label">Due Date</div>
                  <div class="profile-widget-item-value" id="task_due_date">31-12-2020</div>
                </div>
              </div>
            </div>
          </div>

          <ul class="nav nav-tabs mt-2" id="myTab2" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="comments-tab" data-toggle="tab" href="#comments" role="tab" aria-controls="comments" aria-selected="true">Comments</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="attachments-tab" data-toggle="tab" href="#attachments" role="tab" aria-controls="attachments" aria-selected="false">Attachments</a>
            </li>
          </ul>
          <div class="tab-content tab-bordered" id="myTab3Content">
            <div class="tab-pane fade show active" id="comments" role="tabpanel" aria-labelledby="comments-tab">
                <div class="p-0 d-flex">
                    <input type="hidden" name="comment_task_id" id="comment_task_id" value="">
                    <input type="hidden" name="is_comment" id="is_comment" value="true">
                    <input type="text" name="message" id="message" class="form-control" placeholder="Type a message" required>
                    <button type="submit" class="btn btn-primary savebtn">
                      <i class="far fa-paper-plane"></i>
                    </button>
                </div>
                <div id="comments_append">
                </div>
            </div>
            <div class="tab-pane fade" id="attachments" role="tabpanel" aria-labelledby="attachments-tab">
                <div class="p-0 d-flex">
                    <input type="hidden" name="is_attachment" id="is_attachment" value="false">
                    <input type="file" name="attachment" id="attachment" class="form-control">
                    <button type="submit" class="btn btn-primary savebtn">
                      <i class="far fa-paper-plane"></i>
                    </button>
                </div>
                <table class='table-striped' id='file_list'
                  data-toggle="table"
                  data-url="<?=base_url('projects/get_tasks_files/')?>"
                  data-click-to-select="true"
                  data-side-pagination="server"
                  data-pagination="false"
                  data-page-list="[5, 10, 20, 50, 100, 200]"
                  data-search="false" data-show-columns="false"
                  data-show-refresh="false" data-trim-on-search="false"
                  data-sort-name="id" data-sort-order="desc"
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
      </div>
    </div>
  </div>
</form>

<form action="<?=base_url('projects/create-task')?>" method="POST" class="modal-part" id="modal-add-task-part">

  <?php if(isset($project_id) && !empty($project_id)){ ?>
    <input type="hidden" name="project_id" value="<?=htmlspecialchars($project_id)?>">
  <?php }else{ ?>
    <div class="form-group">
      <label>Project<span class="text-danger">*</span></label>
      <select name="project_id" id="project_id" class="form-control select2">
        <option value="">Select Project</option>
        <?php foreach($projects as $project){ ?>
        <option value="<?=htmlspecialchars($project['id'])?>"><?=htmlspecialchars($project['title'])?></option>
        <?php } ?>
      </select>
    </div>
  <?php } ?>
  
  <div class="form-group">
    <label>Task Title<span class="text-danger">*</span></label>
    <input type="text" name="title" class="form-control" required>
  </div>
  <div class="form-group">
    <label>Description<span class="text-danger">*</span></label>
    <textarea type="text" name="description" class="form-control"></textarea>
  </div>
  <div class="form-group">
    <label>Due Date<span class="text-danger">*</span></label>
    <input type="text" name="due_date"  class="form-control datepicker" required>
  </div>

  <div class="form-group">
    <label>Priority<span class="text-danger">*</span></label>
    <select name="priority" class="form-control select2" required>
      <?php foreach($task_priorities as $priorities){ ?>
      <option value="<?=htmlspecialchars($priorities['id'])?>"><?=htmlspecialchars($priorities['title'])?></option>
      <?php } ?>
    </select>
  </div>

  <div class="form-group">
    <label>Status<span class="text-danger">*</span></label>
    <select name="status" class="form-control select2" required>
      <?php foreach($task_status as $status){ ?>
      <option value="<?=htmlspecialchars($status['id'])?>"><?=htmlspecialchars($status['title'])?></option>
      <?php } ?>
    </select>
  </div>

  <div class="form-group">
    <label>Assigned Users <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="Assign task to the users who will work on this task. Only this users are able to see this task."></i></label>
    <select name="users[]" id="users_append" class="form-control select2" multiple="">
      <?php if(!empty($project_id)){ foreach($projecr_users as $projecr_user){ ?>
      <option value="<?=htmlspecialchars($projecr_user['id'])?>"><?=htmlspecialchars($projecr_user['first_name'])?> <?=htmlspecialchars($projecr_user['last_name'])?></option>
      <?php } } ?>
    </select>
  </div>

</form>

<form action="<?=base_url('projects/edit-task')?>" method="POST" class="modal-part" id="modal-edit-task-part">
  <input type="hidden" name="update_id" id="update_id" value="">
  <div class="form-group">
    <label>Task Title<span class="text-danger">*</span></label>
    <input type="text" name="title" id="title" class="form-control" required>
  </div>
  <div class="form-group">
    <label>Description<span class="text-danger">*</span></label>
    <textarea type="text" name="description" id="description" class="form-control"></textarea>
  </div>
  <div class="form-group">
    <label>Due Date<span class="text-danger">*</span></label>
    <input type="text" name="due_date" id="due_date" class="form-control datepicker" required>
  </div>

  <div class="form-group">
    <label>Priority<span class="text-danger">*</span></label>
    <select name="priority" id="priority" class="form-control select2" required>
      <?php foreach($task_priorities as $priorities){ ?>
      <option value="<?=htmlspecialchars($priorities['id'])?>"><?=htmlspecialchars($priorities['title'])?></option>
      <?php } ?>
    </select>
  </div>

  <div class="form-group">
    <label>Status<span class="text-danger">*</span></label>
    <select name="status" id="status" class="form-control select2" required>
      <?php foreach($task_status as $status){ ?>
      <option value="<?=htmlspecialchars($status['id'])?>"><?=htmlspecialchars($status['title'])?></option>
      <?php } ?>
    </select>
  </div>
 
  <div class="form-group">
    <label>Assigned Users <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="Assign task to the users who will work on this task. Only this users are able to see this task."></i></label>
    <select name="users[]" id="users" class="form-control select2" multiple="">
      <?php foreach($projecr_users as $projecr_user){ ?>
      <option value="<?=htmlspecialchars($projecr_user['id'])?>"><?=htmlspecialchars($projecr_user['first_name'])?> <?=htmlspecialchars($projecr_user['last_name'])?></option>
      <?php } ?>
    </select>
  </div>


</form>

<div id="modal-edit-task"></div>
<div id="modal-task-detail"></div>

<?php $this->load->view('includes/js'); ?>

<script src="<?=base_url('assets/modules/dragula/dragula.min.js');?>"></script>
<script src="<?=base_url('assets/js/page/tasks.js');?>"></script>

</body>
</html>
