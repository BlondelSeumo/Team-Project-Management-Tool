<form action="<?=base_url('settings/save-general-setting')?>" method="POST" id="setting-form">
    <div class="card-body row">

      <?php if ($this->ion_auth->in_group(3)){ ?>
      <div class="form-group col-md-6">
        <label>Company Name<span class="text-danger">*</span></label>
        <input type="text" name="company_name" value="<?=htmlspecialchars($company_name)?>" class="form-control" required="">
      </div>
      <div class="form-group col-md-6">
        <label>Footer Text<span class="text-danger">*</span></label>
        <input type="text" name="footer_text" value="<?=htmlspecialchars($footer_text)?>" class="form-control">
      </div>
      <div class="form-group col-md-6">
        <label>Google Analytics</label>
        <input type="text" name="google_analytics" value="<?=htmlspecialchars($google_analytics)?>" class="form-control">
      </div>
      <?php } ?>

      <div class="form-group col-md-6">
        <label>Timezone<span class="text-danger">*</span></label>
        <input type="hidden" id="mysql_timezone" name="mysql_timezone" value="<?=htmlspecialchars($mysql_timezone)?>">
        <select name="php_timezone" id="php_timezone" class="form-control select2">
          <?php foreach($timezones as $option){ ?>
            <option value="<?=htmlspecialchars($option[2])?>" data-gmt="<?=htmlspecialchars($option['1']);?>" <?=(isset($php_timezone) && $php_timezone == $option[2])?'selected':'';?>><?=htmlspecialchars($option[2])?> - GMT <?=htmlspecialchars($option[1])?> - <?=htmlspecialchars($option[0])?></option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group col-md-6">
        <label>Date Format<span class="text-danger">*</span></label>
        <input type="hidden" id="date_format_js" name="date_format_js" value="<?=isset($date_format_js)?htmlspecialchars($date_format_js):''?>">
        <select name="date_format" id="date_format" class="form-control select2">
          <?php foreach($date_formats as $option){ ?>
            <option data-js_value="<?=htmlspecialchars($option['js_format'])?>" value="<?=htmlspecialchars($option['format'])?>" <?=(isset($date_format) && $date_format == $option['format'])?'selected':'';?>><?=htmlspecialchars($option['format'])?> (<?=date(htmlspecialchars($option['format']))?>)</option>
          <?php } ?>
        </select>
      </div>
      <div class="form-group col-md-6">
        <label>Time Format<span class="text-danger">*</span></label>
        <input type="hidden" id="time_format_js" name="time_format_js" value="<?=isset($time_format_js)?htmlspecialchars($time_format_js):''?>">
        <select name="time_format" id="time_format" class="form-control select2">
          <?php foreach($time_formats as $option){ ?>
            <option data-js_value="<?=htmlspecialchars($option['js_format'])?>" value="<?=htmlspecialchars($option['format'])?>" <?=(isset($time_format) && $time_format == $option['format'])?'selected':'';?>><?=htmlspecialchars($option['description'])?> (<?=date(htmlspecialchars($option['format']))?>)</option>
          <?php } ?>
        </select>
      </div>
      
      <div class="form-group col-md-12">
        <label>File Upload Format<span class="text-danger">*</span><i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="Only this type of files going to be allowed to upload in projects and tasks."></i></label>
        <input type="text" name="file_upload_format" value="<?=htmlspecialchars($file_upload_format)?>" class="form-control">
      </div>
      
      <?php if ($this->ion_auth->in_group(3)){ ?>
      <div class="form-group col-md-4">
        <img alt="Full Logo" id="full_logo-img" src="<?=base_url('assets/uploads/logos/'.htmlspecialchars($full_logo))?>" class="system-logos">
          <input type="hidden" name="full_logo_old" value="<?=htmlspecialchars($full_logo)?>">
        <div class="custom-file form-group mt-1">
          <input type="file" name="full_logo" class="custom-file-input" id="full_logo">
          <label class="custom-file-label" for="full_logo">Full Logo</label>
        </div>
      </div>
      <div class="form-group col-md-4">
        <img alt="Half Logo" id="half_logo-img" src="<?=base_url('assets/uploads/logos/'.htmlspecialchars($half_logo))?>" class="system-logos">
          <input type="hidden" name="half_logo_old" value="<?=htmlspecialchars($half_logo)?>">
        <div class="custom-file mt-1">
          <input type="file" name="half_logo" class="custom-file-input" id="half_logo">
          <label class="custom-file-label" for="half_logo">Half Logo</label>
        </div>
      </div>
      <div class="form-group col-md-4">
        <img alt="Favicon" id="favicon-img" src="<?=base_url('assets/uploads/logos/'.htmlspecialchars($favicon))?>" class="system-logos">
          <input type="hidden" name="favicon_old" value="<?=htmlspecialchars($favicon)?>">
        <div class="custom-file mt-1">
          <input type="file" name="favicon" class="custom-file-input" id="favicon">
          <label class="custom-file-label" for="favicon">Favicon</label>
        </div>
      </div>
      <?php } ?>

    </div>
    <?php if ($this->ion_auth->is_admin() || permissions('setting_update') || $this->ion_auth->in_group(3)){ ?>
      <div class="card-footer bg-whitesmoke text-md-right">
        <button class="btn btn-primary savebtn">Save Changes</button>
      </div>
    <?php } ?>
    <div class="result"></div>
  </form>