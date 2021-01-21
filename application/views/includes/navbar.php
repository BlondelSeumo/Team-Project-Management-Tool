<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <ul class="navbar-nav mr-auto">
      <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
    </ul>
  </form>
  <ul class="navbar-nav navbar-right">
    <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
      <?php if(isset($current_user->profile) && !empty($current_user->profile)){ ?>
          <img alt="image" src="<?=base_url(UPLOAD_PROFILE.''.htmlspecialchars($current_user->profile))?>" class="rounded-circle mr-1">
      <?php }else{ ?>
          <figure class="avatar mr-2 avatar-sm bg-danger text-white" data-initial="<?=mb_substr(htmlspecialchars($current_user->first_name), 0, 1, "utf-8").''.mb_substr(htmlspecialchars($current_user->last_name), 0, 1, "utf-8")?>"></figure>
      <?php } ?>
      <div class="d-sm-none d-lg-inline-block"><?=htmlspecialchars($current_user->first_name)?> <?=htmlspecialchars($current_user->last_name)?></div></a>
      <div class="dropdown-menu dropdown-menu-right">
        <a href="<?=base_url('users/profile')?>" class="dropdown-item has-icon">
          <i class="far fa-user"></i> Profile
        </a>
        <div class="dropdown-divider"></div>
        <a href="<?=base_url('auth/logout')?>" class="dropdown-item has-icon text-danger">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </div>
    </li>
  </ul>
</nav>
<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="<?=base_url()?>"><img class="navbar-logos" alt="Logo" src="<?=base_url('assets/uploads/logos/'.full_logo())?>"></a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="<?=base_url()?>"><img class="navbar-logos" alt="Logo Half" src="<?=base_url('assets/uploads/logos/'.half_logo())?>"></a>
    </div>
    <ul class="sidebar-menu">
      <li <?= (current_url() == base_url('/'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url()?>"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
      
      <?php if (($this->ion_auth->is_admin() || permissions('project_view')) && !$this->ion_auth->in_group(3)){ ?>  
        <li <?= (current_url() == base_url('projects') || $this->uri->segment(2) == 'detail')?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('projects')?>"><i class="fas fa-cubes"></i> <span>My Projects</span></a></li>
      <?php } ?>

      <?php if (($this->ion_auth->is_admin() || permissions('task_view')) && !$this->ion_auth->in_group(3)){ ?>  
        <li <?= (current_url() == base_url('projects/tasks')  || $this->uri->segment(2) == 'tasks')?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('projects/tasks')?>"><i class="fas fa-layer-group"></i> <span>My Tasks</span></a></li>
      <?php } ?>

      <?php if (($this->ion_auth->is_admin() || permissions('client_view')) && !$this->ion_auth->in_group(3)){ ?>  
        <li <?= (current_url() == base_url('users/client'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('users/client')?>"><i class="fas fa-handshake"></i> <span>Clients</span></a></li>
      <?php } ?>

      <?php if (($this->ion_auth->is_admin() || permissions('todo_view')) && !$this->ion_auth->in_group(3)){ ?>  
        <li <?= (current_url() == base_url('todo'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('todo')?>"><i class="fas fa-tasks"></i> <span>My ToDo</span></a></li>
      <?php } ?>

      <?php if (($this->ion_auth->is_admin() || permissions('notes_view')) && !$this->ion_auth->in_group(3)){ ?>  
        <li <?= (current_url() == base_url('notes'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('notes')?>"><i class="fas fa-sticky-note"></i> <span>My Notes</span></a></li>
      <?php } ?>

      <?php if (($this->ion_auth->is_admin() || permissions('chat_view')) && !$this->ion_auth->in_group(3)){ ?>  
        <li <?= (current_url() == base_url('chat'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('chat')?>"><i class="fas fa-comment-alt"></i> <span>Chat</span></a></li>
      <?php } ?>

      <?php if (($this->ion_auth->is_admin() || $this->ion_auth->in_group(3))){ ?>  
        <li <?= (current_url() == base_url('plans'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('plans')?>"><i class="fas fa-dollar-sign"></i> <span>
          <?php
            if($this->ion_auth->in_group(3)){
              echo 'Subscription Plans';
            }else{
              echo 'Subscription & Billing';
            }
          ?>
        </span></a></li>
      <?php } ?>
      
      <?php if ($this->ion_auth->in_group(3)){ ?>  
        <li <?= (current_url() == base_url('plans/orders'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('plans/orders')?>"><i class="fas fa-shopping-cart"></i> <span>Subscription Orders</span></a></li>
      <?php } ?>

      <?php if ($this->ion_auth->in_group(3)){ ?>  
        <li <?= (current_url() == base_url('plans/transactions'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('plans/transactions')?>"><i class="fas fa-money-bill-alt"></i> <span>Transactions</span></a></li>
      <?php } ?>

      <?php if ($this->ion_auth->in_group(3)){ ?>  
        <li <?= (current_url() == base_url('users/saas'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('users/saas')?>"><i class="fas fa-users-cog"></i> <span>Users and Plan</span></a></li>
      <?php } ?>

      <?php if ($this->ion_auth->in_group(3)){ ?>  
        <li <?= (current_url() == base_url('users'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('users')?>"><i class="fas fa-user-tie"></i> <span>SaaS Admins</span></a></li>
      <?php } ?>

      <?php if (($this->ion_auth->is_admin() || permissions('user_view')) && !$this->ion_auth->in_group(3)){ ?>  
        <li <?= (current_url() == base_url('users'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('users')?>"><i class="fas fa-user-friends"></i> <span>Users</span></a></li>
      <?php } ?>
      
      <?php if ($this->ion_auth->is_admin() || permissions('setting_view') || $this->ion_auth->in_group(3)){ ?>           
        <li class="dropdown <?= (current_url() == base_url('settings') || current_url() == base_url('settings/payment') || current_url() == base_url('settings/email') || current_url() == base_url('settings/user-permissions') || current_url() == base_url('settings/update'))?'active':''; ?>">
        <a class="nav-link has-dropdown" href="#"><i class="fas fa-pencil-ruler"></i> 
        <span>Settings</span></a>
        <ul class="dropdown-menu">
            <li <?= (current_url() == base_url('settings'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('settings')?>">General</a></li>

            <?php if ($this->ion_auth->in_group(3)){ ?> 
              <li <?= (current_url() == base_url('settings/email'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('settings/email')?>">Email</a></li>
              <li <?= (current_url() == base_url('settings/payment'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('settings/payment')?>">Payment</a></li> 
              <li <?= (current_url() == base_url('settings/update'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('settings/update')?>">Update</a></li>
              <?php }else{ ?>
              <li <?= (current_url() == base_url('settings/user-permissions'))?'class="active"':''; ?>><a class="nav-link" href="<?=base_url('settings/user-permissions')?>">User Permissions</a></li>
              <?php } ?>
          </ul>
        </li>
      <?php } ?>
      
    </ul>
  </aside>
</div>