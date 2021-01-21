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
            <h1>Dashboard</h1>
          </div>

          <?php if($this->ion_auth->is_admin()){ 
            $my_plan = get_current_plan();
            if($my_plan && ($my_plan['expired'] == 0 || $my_plan['end_date'] <= date('Y-m-d',date(strtotime("+2 day", strtotime(date('Y-m-d'))))))){ 
          ?>
          <div class="row mb-4">
            <div class="col-md-12">
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
                  <div class="mt-4">
                    <a href="<?=base_url('plans')?>" class="btn btn-outline-white btn-lg btn-icon icon-left"><i class="fas fa-arrow-right"></i> Renew Plan</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php } } ?>

          <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
              <div class="card card-statistic-2">
                <div class="card-stats">
                  <div class="card-stats-title">Project Statistics - 
                    <div class="dropdown d-inline">
                      <a href="<?=base_url('projects')?>">View</a>
                    </div>
                  </div>
                  <div class="card-stats-items mb-3">
                    <div class="card-stats-item text-danger">
                      <div class="card-stats-item-count">
                      <?php
                        if($this->ion_auth->is_admin()){
                          $pendingP = get_count('id','projects','(status=1 OR status=2) AND saas_id='.htmlspecialchars($this->session->userdata('saas_id')));
                        }elseif($this->ion_auth->in_group(4)){
                          $pendingP =  get_count('id','projects','(status=1 OR status=2) AND client_id='.htmlspecialchars($this->session->userdata('user_id')));
                        }else{
                          $pendingP = get_count('p.id','projects p LEFT JOIN project_users pu ON p.id=pu.project_id','(status=1 OR status=2) AND pu.user_id='.htmlspecialchars($this->session->userdata('user_id')));
                        }
                        echo htmlspecialchars($pendingP);
                      ?>
                      </div>
                      <div class="card-stats-item-label">Pending</div>
                    </div>
                    <div class="card-stats-item text-success">
                      <div class="card-stats-item-count">
                      <?php
                        if($this->ion_auth->is_admin()){
                          $completedP = get_count('id','projects','status=3 AND saas_id='.htmlspecialchars($this->session->userdata('saas_id')));
                        }elseif($this->ion_auth->in_group(4)){
                          $completedP =  get_count('id','projects','status=3 AND client_id='.htmlspecialchars($this->session->userdata('user_id')));
                        }else{
                          $completedP = get_count('p.id','projects p LEFT JOIN project_users pu ON p.id=pu.project_id','status=3 AND pu.user_id='.htmlspecialchars($this->session->userdata('user_id')));
                        }
                        echo htmlspecialchars($completedP);
                      ?>
                      </div>
                      <div class="card-stats-item-label">Completed</div>
                    </div>
                    <div class="card-stats-item">
                      <div class="card-stats-item-count text-primary">
                      <?=htmlspecialchars($pendingP)+htmlspecialchars($completedP)?>
                      </div>
                      <div class="card-stats-item-label">Total</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-lg-4 col-md-4 col-sm-12">
              <div class="card card-statistic-2">
                <div class="card-stats">
                  <div class="card-stats-title">Tasks Statistics - 
                    <div class="dropdown d-inline">
                      <a href="<?=base_url('projects/tasks')?>">View</a>
                      
                    </div>
                  </div>
                  <div class="card-stats-items mb-3">
                    <div class="card-stats-item text-danger">
                      <div class="card-stats-item-count">
                      <?php
                          if($this->ion_auth->is_admin()){
                            $pendingT =  get_count('id','tasks','(status=1 OR status=2 OR status=3) AND saas_id='.htmlspecialchars($this->session->userdata('saas_id')));
                          }elseif($this->ion_auth->in_group(4)){
                            $pendingT = get_count('t.id','tasks t LEFT JOIN projects p on t.project_id = p.id','(t.status=1 OR t.status=2 OR t.status=3) AND p.client_id = '.htmlspecialchars($this->session->userdata('user_id')));
                          }else{
                            $pendingT =  get_count('t.id','tasks t LEFT JOIN task_users tu ON t.id=tu.task_id','(status=1 OR status=2 OR status=3) AND tu.user_id='.htmlspecialchars($this->session->userdata('user_id')));
                          }
                          echo htmlspecialchars($pendingT);
                      ?>
                      </div>
                      <div class="card-stats-item-label">Pending</div>
                    </div>
                    <div class="card-stats-item text-success">
                      <div class="card-stats-item-count">
                      <?php
                          if($this->ion_auth->is_admin()){
                            $completedT =  get_count('id','tasks','status=4 AND saas_id='.$this->session->userdata('saas_id'));
                          }elseif($this->ion_auth->in_group(4)){
                            $completedT = get_count('t.id','tasks t LEFT JOIN projects p on t.project_id = p.id','t.status=4 AND p.client_id = '.htmlspecialchars($this->session->userdata('user_id')));
                          }else{
                            $completedT =  get_count('t.id','tasks t LEFT JOIN task_users tu ON t.id=tu.task_id','status=4 AND tu.user_id='.$this->session->userdata('user_id'));
                          }
                          echo htmlspecialchars($completedT);
                      ?>
                      </div>
                      <div class="card-stats-item-label">Completed</div>
                    </div>
                    <div class="card-stats-item text-primary">
                      <div class="card-stats-item-count">
                      <?=htmlspecialchars($pendingT)+htmlspecialchars($completedT)?>
                      </div>
                      <div class="card-stats-item-label">Total</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12">
              <div class="card card-statistic-2">
                <div class="card-stats">
                  <div class="card-stats-title">User Statistics 

                  </div>
                  <div class="card-stats-items mb-3">
                    <div class="card-stats-item text-danger">
                      <div class="card-stats-item-count"><?=count($this->ion_auth->where('users.active', 0)->where('users.saas_id = '.$this->session->userdata('saas_id'))->users(array(1,2))->result())?></div>
                      <div class="card-stats-item-label">Deactive</div>
                    </div>
                    <div class="card-stats-item text-success">
                      <div class="card-stats-item-count"><?=count($this->ion_auth->where('users.active', 1)->where('users.saas_id = '.$this->session->userdata('saas_id'))->users(array(1,2))->result())?></div>
                      <div class="card-stats-item-label">Active</div>
                    </div>
                    <div class="card-stats-item text-primary">
                      <div class="card-stats-item-count"><?=count($this->ion_auth->where('users.saas_id = '.$this->session->userdata('saas_id'))->users(array(1,2))->result())?></div>
                      <div class="card-stats-item-label">Total</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
  
          </div>
          
          <div class="row">
            <div class="col-lg-6 col-md-12 col-12 col-sm-12">
              <div class="card">
                <div class="card-header">
                  <h4>Project Status</h4>
                </div>
                <div class="card-body">
                  <canvas id="project_chart" height="auto"></canvas>
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-md-12 col-12 col-sm-12">
              <div class="card">
                <div class="card-header">
                  <h4>Task Status</h4>
                </div>
                <div class="card-body">
                  <canvas id="task_chart" height="auto"></canvas>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    
    <?php $this->load->view('includes/footer'); ?>
    </div>
  </div>

<?php
  foreach($project_status as $project_title){
    $tmpP[] =  htmlspecialchars($project_title['title']);

    if($this->ion_auth->is_admin()){
      $tmpPV[] =  get_count('id','projects','status='.$project_title['id'].' AND saas_id='.htmlspecialchars($this->session->userdata('saas_id')));
    }elseif($this->ion_auth->in_group(4)){
      $tmpPV[] =  get_count('id','projects','client_id='.htmlspecialchars($this->session->userdata('user_id')).' AND status='.htmlspecialchars($project_title['id']));
    }else{
      $tmpPV[] =  get_count('p.id','projects p LEFT JOIN project_users pu ON p.id=pu.project_id','status='.$project_title['id'].' AND pu.user_id='.htmlspecialchars($this->session->userdata('user_id')));
    }
  }

  foreach($task_status as $task_title){
    $tmpT[] =  htmlspecialchars($task_title['title']);

    if($this->ion_auth->is_admin()){
      $tmpTV[] =  get_count('id','tasks','status='.htmlspecialchars($task_title['id']).' AND saas_id='.htmlspecialchars($this->session->userdata('saas_id')));
    }elseif($this->ion_auth->in_group(4)){
      $tmpTV[] =  get_count('t.id','tasks t LEFT JOIN projects p on t.project_id = p.id','p.client_id = '.htmlspecialchars($this->session->userdata('user_id')).' AND t.status = '.htmlspecialchars($task_title['id']));
    }else{
      $tmpTV[] =  get_count('t.id','tasks t LEFT JOIN task_users tu ON t.id=tu.task_id','status='.htmlspecialchars($task_title['id']).' AND tu.user_id='.htmlspecialchars($this->session->userdata('user_id')));
    }
  }

?>

<script>
  project_status = '<?=json_encode($tmpP)?>';
  project_status_values = '<?=json_encode($tmpPV)?>';
  task_status = '<?=json_encode($tmpT)?>';
  task_status_values = '<?=json_encode($tmpTV)?>';
</script>

<?php $this->load->view('includes/js'); ?>
<script src="<?=base_url('assets/js/page/home.js')?>"></script>
</body>
</html>
