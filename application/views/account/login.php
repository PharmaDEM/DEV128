<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php include 'includes/header.php' ?>
<style>
.blue-text {
  color: #213363;
}

.green-text {
  color: green;
}
.border-secondary {
  border-color: grey;
}
.vl {
  border-left: 1px solid #FAF0E4;
  height: 90%;
}
</style>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Lato:wght@900&display=swap');
</style>
<div class="container">
  <div class="row">
    <div class="col-sm-5">
    <img src="http://128.199.31.121/logoside.png" style="border-radius: 25px;" />
    
      <!-- Content for the first column -->
    </div>
    <div class="col-sm-1">
    <div class="vl"></div>

    
      <!-- Content for the middle column -->
    </div>
    <div class="col-sm-5">
    <div class="login-box">
  <div class="login-logo">
   <!-- /.<a href="<?php echo url('/') ?>"><?php echo setting('company_name') ?></a> -->
    <img src="http://128.199.31.121/assets/img/logo-new11.png" style="max-width:280px;" />

   <div style="font-family: 'Lato', sans-serif;"> <span class="blue-text">Solu</span><span class="green-text">DEM</span> </div>

  </div>
  <?php if(isset($message)): ?>
      <div class="alert alert-<?php echo $message_type ?>">
        <p><?php echo $message ?></p>
      </div>
    <?php endif; ?>

    <?php if(!empty($this->session->flashdata('message'))): ?>
      <div class="alert alert-<?php echo $this->session->flashdata('message_type'); ?>">
        <p><?php echo $this->session->flashdata('message') ?></p>
      </div>
    <?php endif; ?>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg"><?php echo lang('sign_in_session') ?></p>

      <?php echo form_open('/login/check', ['method' => 'POST', 'autocomplete' => 'off']); ?> 
      <div class="input-group mb-3">
          <input type="text" name="username" required class="form-control" placeholder="<?php echo lang('username_or_email') ?>" value="<?php echo post('username') ?>" autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
          <?php echo form_error('username', '<span style="display:block" class="error invalid-feedback">', '</span>'); ?>
        </div>

        <div class="input-group mb-3">
          <input type="password" name="password" required class="form-control" placeholder="<?php echo lang('user_password') ?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          <?php echo form_error('password', '<span style="display:block" class="error invalid-feedback">', '</span>'); ?>
        </div>

      <?php if (setting('google_recaptcha_enabled') == '1'): ?>
        
      <script src="https://www.google.com/recaptcha/api.js" async defer></script>
      
      <div class="form-group">
        <div class="g-recaptcha" data-sitekey="<?php echo setting('google_recaptcha_sitekey') ?>"></div>
        <?php echo form_error('g-recaptcha-response', '<span style="display:block" class="error invalid-feedback">', '</span>'); ?>
      </div>

      <?php endif ?>

      <div class="row">
        <div class="col-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox" <?php echo post('remember_me')?'checked':'' ?> name="remember_me" /> <?php echo lang('remember_me') ?>
            </label>
          </div>
        </div>
        <!-- /.col -->

        <div class="col-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat"><?php echo lang('signin') ?></button>
        </div>
        <!-- /.col -->
      </div>
    <?php echo form_close(); ?>


      <p class="mb-1">
        <a href="<?php echo url('login/forget?username='.post('username')) ?>"><?php echo lang('forget_password_?') ?></a><br>
      </p>
      <p class="mb-0">
        <!-- <a href="register.html" class="text-center">Register a new membership</a> -->
      </p>
    </div>
  
    <!-- /.login-card-body -->
  </div>
  <p style="text-align:center;">Version 2023.1</p>
</div>
<!-- /.login-box -->
    </div>
  </div>
</div>



<?php include 'includes/footer.php' ?>
