<!DOCTYPE html>
<html lang="en">
<head>
<?php $this->load->view('includes/head'); ?>
</head>
<body>
  <div id="app">
    <div class="main-wrapper">
      <?php $this->load->view('includes/navbar'); ?>
        <div class="main-content">
          <section class="section">
            <div class="section-header">
              <div class="section-header-back">
                <a href="<?=base_url()?>" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
              </div>
              <h1>Settings</h1>
              <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?=base_url()?>">Dashboard</a></div>
                <div class="breadcrumb-item">Settings</div>
              </div>
            </div>

            <div class="section-body">
              <div class="row">
                <div class="col-md-3">
                  <div class="card">
                    <div class="card-body">
                      <ul class="nav nav-pills flex-column">
                        <li class="nav-item"><a href="<?=base_url('settings')?>" class="nav-link <?=($main_page == 'general')?'active':''?>"><i class="fas fa-cogs"></i> General</a></li>
                        <?php if ($this->ion_auth->in_group(3)){ ?> 
                          <li class="nav-item"><a href="<?=base_url('settings/email')?>" class="nav-link <?=($main_page == 'email')?'active':''?>"><i class="fas fa-at"></i> Email</a></li>
                          <li class="nav-item"><a href="<?=base_url('settings/payment')?>" class="nav-link <?=($main_page == 'payment')?'active':''?>"><i class="fab fa-paypal"></i> Payment Gateway</a></li>
                          <li class="nav-item"><a href="<?=base_url('settings/update')?>" class="nav-link <?=($main_page == 'update')?'active':''?>"><i class="fas fa-hand-holding-heart"></i> Update</a></li>
                        <?php }else{ ?>
                          <li class="nav-item"><a href="<?=base_url('settings/user-permissions')?>" class="nav-link <?=($main_page == 'permissions')?'active':''?>"><i class="fas fa-user-cog"></i> User Permissions</a></li>
                        <?php } ?>
                        
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="col-md-9">
                  <div class="card" id="settings-card">
                    <?php $this->load->view('setting-forms/'.htmlspecialchars($main_page)); ?>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      <?php $this->load->view('includes/footer'); ?>
    </div>
  </div>

<?php $this->load->view('includes/js'); ?>
</body>
</html>
