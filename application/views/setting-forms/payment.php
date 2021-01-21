<form action="<?=base_url('settings/save-payment-setting')?>" method="POST" id="setting-form">
    <div class="card-body row">
      <div class="form-group col-md-12">
        <label>Paypal Client ID</label>
        <input type="text" name="paypal_client_id" value="<?=htmlspecialchars($paypal_client_id)?>" class="form-control" required="">
      </div>
    </div>
    <?php if ($this->ion_auth->is_admin() || permissions('setting_update') || $this->ion_auth->in_group(3)){ ?>
      <div class="card-footer bg-whitesmoke text-md-right">
        <button class="btn btn-primary savebtn">Save Changes</button>
      </div>
    <?php } ?>
    <div class="result"></div>
</form>