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
              ToDo 
              <a href="#" id="modal-add-todo" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> Create</a>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>">Dashboard</a></div>
              <div class="breadcrumb-item">ToDo</div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">
                <div class="col-md-9">
                  <div class="card">
                    <div class="card-body">             
                      <ul class="list-unstyled list-unstyled-border">
                        
                      <?php if ($todo && !empty($todo)){ 
                        foreach($todo as $todos){ ?> 
                        <li class="media">
                          <div class="media-body">
                            <div class="media-right">
                              <div class="float-right dropdown">
                                <a href="#" data-toggle="dropdown"><i class="fas fa-ellipsis-h"></i></a>
                                <div class="dropdown-menu">
                                  <a href="#" class="dropdown-item modal-edit-todo" data-edit="<?=htmlspecialchars($todos['id'])?>"> Edit</a>
                                  <a href="#" class="dropdown-item text-danger delete_todo" data-id="<?=htmlspecialchars($todos['id'])?>"> Delete</a>
                                </div>
                              </div>
                            </div>
                            <div class="custom-control custom-checkbox">
                              <input type="checkbox" class="custom-control-input checkbox-todo" id="customCheck<?=htmlspecialchars($todos['id'])?>" <?=($todos['done'] == 1)?'checked':''?>>
                              <label data-id="<?=htmlspecialchars($todos['id'])?>" class="custom-control-label media-title check-todo <?=($todos['done'] == 1)?'checked':''?>" for="customCheck<?=htmlspecialchars($todos['id'])?>">
                                <?php if ($todos['done'] == 1){ ?> 
                                  <strong class="text-primary text-strike"><?=htmlspecialchars($todos['todo'])?></strong>
                                <?php }else{ ?> 
                                  <strong><?=htmlspecialchars($todos['todo'])?></strong>
                                <?php } ?> 
                              </label>
                              <div class="text-small
                                <?php if ($todos['done'] == 1){ 
                                  echo 'text-success';
                                }elseif ($todos['days_status'] == 'Overdue'){ 
                                  echo 'text-danger';
                                }else{
                                  echo 'text-muted';
                                } ?>
                                ">
                                <?=htmlspecialchars($todos['due_date'])?>
                                  
                                <div class="bullet"></div> 
                                <?php if ($todos['done'] == 1){ 
                                  echo 'Finished';
                                }else{
                                  echo htmlspecialchars($todos['days_count']).' Days '.htmlspecialchars($todos['days_status']);
                                } ?> 
                              </div>
                            </div>
                            
                          </div>
                        </li>
                      <?php } } ?> 
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="card">
                    <div class="card-body">
                      <ul class="nav nav-pills flex-column">
                        <li class="nav-item"><a href="<?=base_url('todo')?>" class="nav-link <?=($main_page == '')?'active':''?>"><i class="fas fa-calendar-alt"></i> All</a></li>
                        <li class="nav-item"><a href="<?=base_url('todo?filter=today')?>" class="nav-link <?=($main_page == 'today')?'active':''?>"><i class="fas fa-calendar-alt"></i> Today</a></li>
                        <li class="nav-item"><a href="<?=base_url('todo?filter=upcoming')?>" class="nav-link <?=($main_page == 'upcoming')?'active':''?>"><i class="fas fa-calendar-alt"></i> Upcoming</a></li>
                        <li class="nav-item"><a href="<?=base_url('todo?filter=finished')?>" class="nav-link <?=($main_page == 'finished')?'active':''?>"><i class="fas fa-calendar-alt"></i> Finished</a></li>
                        <li class="nav-item"><a href="<?=base_url('todo?filter=pending')?>" class="nav-link <?=($main_page == 'pending')?'active':''?>"><i class="fas fa-calendar-alt"></i> Pending</a></li>
                        <li class="nav-item"><a href="<?=base_url('todo?filter=overdue')?>" class="nav-link <?=($main_page == 'overdue')?'active':''?>"><i class="fas fa-calendar-alt"></i> Overdue</a></li>
                        
                      </ul>
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

<form action="<?=base_url('todo/create')?>" method="POST" class="modal-part" id="modal-add-todo-part">
  <div class="row">
    <div class="form-group col-md-12">
      <label>ToDo<span class="text-danger">*</span></label>
      <textarea type="text" name="todo" class="form-control"></textarea>
    </div>
    <div class="form-group col-md-12">
      <label>Due Date<span class="text-danger">*</span></label>
      <input type="text" name="due_date"  class="form-control datepicker">
    </div>
  </div>
</form>

<form action="<?=base_url('todo/edit')?>" method="POST" class="modal-part" id="modal-edit-todo-part">
  <input type="hidden" name="update_id" id="update_id" value="">
  <div class="row">
    <div class="form-group col-md-12">
      <label>ToDo<span class="text-danger">*</span></label>
      <textarea type="text" name="todo" id="todo" class="form-control"></textarea>
    </div>
    <div class="form-group col-md-12">
      <label>Due Date<span class="text-danger">*</span></label>
      <input type="text" name="due_date"  id="due_date" class="form-control datepicker">
    </div>
  </div>
</form>

<div id="modal-edit-todo"></div>

<?php $this->load->view('includes/js'); ?>
</body>
</html>
