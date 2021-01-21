<form action="<?=base_url('settings/save-update-setting')?>" method="POST" id="setting-update-form" enctype="multipart/form-data">
  <div class="card-header">
    <h4>Current System Version: <?=htmlspecialchars(get_system_version())?></h4>
  </div>
  <div class="card-body row">
    <div class="jumbotron text-center">
      <h2>Update Guide</h2>
      <p class="lead text-muted mt-3">Select the update zip file and hit install update button.</p>
      <p class="lead text-danger">Please note you have to update your application in a sequence like version 1 to version 2, not version 1 to direct version 3. Please take a backup before going further.<p>
    </div>
    <div class="custom-file">
      <input type="file" name="update" class="custom-file-input" id="update">
      <label class="custom-file-label" for="update">Choose file</label>
    </div>
  </div>
  <?php if ($this->ion_auth->is_admin() || permissions('setting_update') || $this->ion_auth->in_group(3)){ ?>
    <div class="card-footer bg-whitesmoke text-md-right">
      <button class="btn btn-primary savebtn">Install Update</button>
    </div>
  <?php } ?>
  <div class="result"></div>
</form>