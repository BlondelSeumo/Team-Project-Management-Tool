<form action="<?=base_url('settings/save-email-setting')?>" method="POST" id="setting-form">
    <div class="card-body row">
      <div class="form-group col-md-6">
        <label>SMTP Host</label>
        <input type="text" name="smtp_host" value="<?=htmlspecialchars($smtp_host)?>" class="form-control" required="">
      </div>
      <div class="form-group col-md-6">
        <label>SMTP Port</label>
        <input type="text" name="smtp_port" value="<?=htmlspecialchars($smtp_port)?>" class="form-control">
      </div>
      <div class="form-group col-md-6">
        <label>Username/Email</label>
        <input type="text" name="smtp_username" value="<?=htmlspecialchars($smtp_username)?>" class="form-control">
      </div>
      <div class="form-group col-md-6">
        <label>Password</label>
        <input type="text" name="smtp_password" value="<?=htmlspecialchars($smtp_password)?>" class="form-control">
      </div>
      <div class="form-group col-md-6">
        <label>Encryption</label>
        <select name="smtp_encryption" id="smtp_encryption" class="form-control select2">
          <option value="none" <?=(isset($smtp_encryption) && $smtp_encryption == '')?'selected':'';?> >None</option>
          <option value="ssl" <?=(isset($smtp_encryption) && $smtp_encryption == 'ssl')?'selected':'';?> >SSL</option>
          <option value="tls" <?=(isset($smtp_encryption) && $smtp_encryption == 'tls')?'selected':'';?> >TLS</option>
        </select>
      </div>
      
      <div class="form-group col-md-6">
        <label>Send test mail to</label>
        <input type="text" name="email" value="" class="form-control">
      </div>
    </div>
    <?php if ($this->ion_auth->is_admin() || permissions('setting_update') || $this->ion_auth->in_group(3)){ ?>
      <div class="card-footer bg-whitesmoke text-md-right">
        <button class="btn btn-primary savebtn">Save Changes</button>
      </div>
    <?php } ?>
    <div class="result"></div>
  </form>