<?php

/**
 *
 * Template Name: Painel
 *
 */

// Get custom fields
// $ctr = new CTR_Class();
// $cf = $ctr->get_content();

if (!defined('WPINC')) {
  header('Location: /');
  exit;
}

// if ( !is_user_logged_in()) {
//   wp_redirect( wp_login_url() );  
//   exit;
// }

get_header(); ?>

<?php
$token = get_field('blaze')? get_field('blaze')['token'] : null;
$user_meta = get_userdata( get_current_user_id() );
?>

<header class="py-2">
  <div class="container-fluid">
    <div class="row">
      <div class="col">
        Olá <?= $user_meta->first_name ?>
      </div>
    </div>
  </div>
</header>

<section>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-7">
        <form class="row g-3" action="<?= admin_url('admin-ajax.php') ?>" id="form-connect-blaze" method="post">
          <input type="hidden" name="action" value="connect_blaze">
          <div class="col">
            <label for="email_blaze" class="visually-hidden">Email</label>
            <input type="email" class="form-control" name="email_blaze" id="email_blaze" placeholder="Email">
            <!-- vinilouz@gmail.com -->
          </div>
          <div class="col">
            <label for="password_blaze" class="visually-hidden">Password</label>
            <input type="password" class="form-control" name="password_blaze" id="password_blaze" placeholder="Password">
            <!-- DgoM3sD522BE! -->
          </div>
          <div class="col">
            <button type="submit" class="btn btn-primary mb-3">Verificar credenciais</button>
          </div>
          <?php if($token) : ?>
            <p class="label-login -success">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 460 460" xmlns:v="https://vecta.io/nano"><path d="M230 0C102.975 0 0 102.975 0 230s102.975 230 230 230 230-102.974 230-230S357.025 0 230 0zm38.333 377.36c0 8.676-7.034 15.71-15.71 15.71h-43.101c-8.676 0-15.71-7.034-15.71-15.71V202.477c0-8.676 7.033-15.71 15.71-15.71h43.101c8.676 0 15.71 7.033 15.71 15.71V377.36zM230 157c-21.539 0-39-17.461-39-39s17.461-39 39-39 39 17.461 39 39-17.461 39-39 39z"/></svg>
              Credenciais corretas.
            </p>
          <?php else: ?>
            <p class="label-login -error">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 460 460" xmlns:v="https://vecta.io/nano"><path d="M230 0C102.975 0 0 102.975 0 230s102.975 230 230 230 230-102.974 230-230S357.025 0 230 0zm38.333 377.36c0 8.676-7.034 15.71-15.71 15.71h-43.101c-8.676 0-15.71-7.034-15.71-15.71V202.477c0-8.676 7.033-15.71 15.71-15.71h43.101c8.676 0 15.71 7.033 15.71 15.71V377.36zM230 157c-21.539 0-39-17.461-39-39s17.461-39 39-39 39 17.461 39 39-17.461 39-39 39z"/></svg>
              Credenciais incorretas.
            </p>
          <?php endif ?>
        </form>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row justify-content-end">
      <div class="col-auto">
        <a href="<?= wp_logout_url() ?>" class="btn btn-secondary">Sair</a>
      </div>
    </div>
  </div>
</section>
<?php get_footer(); ?>