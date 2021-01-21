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
              Chat 
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>">Dashboard</a></div>
              <div class="breadcrumb-item">Chat</div>
            </div>
          </div>
          <div class="section-body">
            <div class="row justify-content-center">
              <div class="col-md-4">
                <div class="card">
                  <div class="card-body">
                    <ul class="list-unstyled list-unstyled-border">
                    <?php
                      if(isset($chat_users) && !empty($chat_users)){
                      foreach ($chat_users as $user) {
                      if($user['id'] != $this->session->userdata('user_id')){
                    ?>
                      <li class="media cursor-pointer user-selected-for-chat" data-id="<?=htmlspecialchars($user['id'])?>">

                        <?php
                          if(isset($user['profile']) && !empty($user['profile'])){
                        ?>     
                          <figure class="avatar avatar-sm">
                            <img src="<?=htmlspecialchars($user['profile'])?>" alt="<?=htmlspecialchars($user['first_name'])?> <?=htmlspecialchars($user['last_name'])?>">
                          </figure>

                        <?php }else{ ?>
                          <figure class="user-avatar avatar avatar-sm rounded-circle profile-widget-picture" data-initial="<?=htmlspecialchars($user['short_name'])?>"></figure>
                        <?php } ?>

                        <div class="media-body">
                          <div class="ml-2 font-weight-bold"><?=htmlspecialchars($user['first_name'])?> <?=htmlspecialchars($user['last_name'])?></div>
                        </div>
                      </li>
                    <?php } } } ?>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="card chat-box card-primary" id="mychatbox">
                  <div class="card-header">
                      <li class="media" id="current_chating_user">
                          <h3>Select Chat</h3>
                      </li>
                  </div>
                  <div class="card-body chat-content">
                  </div>
                  <div class="card-footer chat-form">
                    <form id="chat-form" class='d-none' action="<?=base_url('chat/create')?>" method="POST">
                      <input type="hidden" name="to_id" id="to_id" value="">
                      <input type="text" class="form-control" name="message" id="chat_input" placeholder="Type a message">
                      <button class="btn btn-primary">
                        <i class="far fa-paper-plane"></i>
                      </button>
                    </form>
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


<?php $this->load->view('includes/js'); ?>
<script src="<?=base_url('assets/js/page/chat.js');?>"></script>
</body>
</html>