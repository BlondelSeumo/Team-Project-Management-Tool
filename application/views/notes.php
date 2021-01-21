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
              Notes 
              <a href="#" id="modal-add-notes" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> Create</a>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>">Dashboard</a></div>
              <div class="breadcrumb-item">Notes</div>
            </div>
          </div>
          <div class="section-body">
            <div class="row">
            <?php if ($notes && !empty($notes)){ 
              foreach($notes as $note){ ?> 
              <div class="col-md-6">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title"><?=htmlspecialchars($note['description'])?></h4>
                  </div>
                  <div class="card-body">
                    <a href="#" class="card-link text-muted modal-edit-notes" data-edit="<?=htmlspecialchars($note['id'])?>">Edit</a>
                    <a href="#" class="card-link text-danger delete_notes" data-id="<?=htmlspecialchars($note['id'])?>">Delete</a>
                  </div>
                </div>
              </div>
            <?php } } ?> 
            </div>    
          </div>
        </section>
      </div>
    
    <?php $this->load->view('includes/footer'); ?>
    </div>
  </div>

<form action="<?=base_url('notes/create')?>" method="POST" class="modal-part" id="modal-add-notes-part">
  <div class="row">
    <div class="form-group col-md-12">
      <label>Note<span class="text-danger">*</span></label>
      <textarea type="text" name="description" class="form-control"></textarea>
    </div>
  </div>
</form>

<form action="<?=base_url('notes/edit')?>" method="POST" class="modal-part" id="modal-edit-notes-part">
  <input type="hidden" name="update_id" id="update_id" value="">
  <div class="row">
    <div class="form-group col-md-12">
      <label>Note<span class="text-danger">*</span></label>
      <textarea type="text" name="description" id="description" class="form-control"></textarea>
    </div>
  </div>
</form>

<div id="modal-edit-notes"></div>

<?php $this->load->view('includes/js'); ?>
</body>
</html>
