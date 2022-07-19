<?php
if (!defined('WPINC')) {
  header('Location: /');
  exit;
}



// Get custom fields
// $cf = CTR_Home::get_content();
if (!current_user_can('administrator')) {
  header("Location: https://blazerobot.vip/login");
  die();
}

get_header() ?>

<div id="custom-popup" class="popup">
  <div class="popup-wrapper">
    <span class="close">x</span>
      <p></p>
  </div>
</div>
<style>
  
.popup {
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  z-index: 99999;
  position: fixed;
  justify-content: center;
  align-items: center;
  opacity: 0;
  pointer-events: none;
  background: transparent;
  transition: all 0.3s;
}
.popup.active {
  opacity: 1;
  pointer-events: unset;
  background: rgba(0, 0, 0, .7);
}
.popup-wrapper {
  position: relative;
}
.popup-wrapper img {
  max-height: calc(100vh - 40px);
  max-width: calc(100vw - 30px);
}
.popup-wrapper .close {
  position: absolute;
  right: 0;
  width: 30px;
  height: 30px;
  font-size: 20px;
  line-height: 25px;
  text-align: center;
  background: #fff;
  border-radius: 50px;
  font-family: cursive;
  transform: translate(50%, -50%);
  box-shadow: rgba(0, 0, 0, .2) 0px 8px 24px;
  cursor: pointer;
}
</style>

<main class="main-home">
  <?php 
  $list = get_field('signals_list', 'option');
  if ($list) : ?>
    <section class="s-hero-home">
      <div class="row">
        <div class="wrapper">
          <?php
          $sg = 0;
          $g1 = 0;
          $g2 = 0;
          $bsg = 0;
          $bg1 = 0;
          $bg2 = 0;
          $loss = 0;
          foreach ($list as $k => $signal) {
            if ($signal['white']) {
              switch ($signal['result']) {
                case 'SG':
                  $bsg++;
                  break;
                case 'G1':
                  $bg1++;
                  break;
                case 'G2':
                  $bg2++;
                  break;
              }
            } else {
              switch ($signal['result']) {
                case 'SG':
                  $sg++;
                  break;
                case 'G1':
                  $g1++;
                  break;
                case 'G2':
                  $g2++;
                  break;
                case 'LOSS':
                  $loss++;
                  break;
              }
            }
          }
          ?>
          <h1>Placar geral:</h1>
          <ul>
            <li><?= "SG: " . $sg; ?></li>
            <li><?= "G1: " . $g1; ?></li>
            <li><?= "G2: " . $g2; ?></li>
            <li><?= "Branco SG: " . $bsg; ?></li>
            <li><?= "Branco G1: " . $bg1; ?></li>
            <li><?= "Branco G2: " . $bg2; ?></li>
            <li><?= "LOSS: " . $loss; ?></li>
          </ul>
        </div>

        <div class="wrapper">
          <?php
          $sg = 0;
          $g1 = 0;
          $g2 = 0;
          $bsg = 0;
          $bg1 = 0;
          $bg2 = 0;
          $loss = 0;
          $active = 0;
          foreach ($list as $k => $signal) {
            // pre($active);
            if ($active == 2) {
              if ($signal['white']) {
                switch ($signal['result']) {
                  case 'SG':
                    $bsg++;
                    break;
                  case 'G1':
                    $bg1++;
                    break;
                  case 'G2':
                    $bg2++;
                    break;
                }
              } else {
                switch ($signal['result']) {
                  case 'SG':
                    $sg++;
                    break;
                  case 'G1':
                    $g1++;
                    break;
                  case 'G2':
                    $g2++;
                    break;
                  case 'LOSS':
                    $loss++;
                    break;
                }
              }
            }

            $active = $signal['result']  == "LOSS" ? ++$active : 0;
          }
          ?>
          <h1>Estrategia pós 1 loss:</h1>

          <ul>
            <li><?= "SG: " . $sg; ?></li>
            <li><?= "G1: " . $g1; ?></li>
            <li><?= "G2: " . $g2; ?></li>
            <li><?= "Branco SG: " . $bsg; ?></li>
            <li><?= "Branco G1: " . $bg1; ?></li>
            <li><?= "Branco G2: " . $bg2; ?></li>
            <li><?= "LOSS: " . $loss; ?></li>
          </ul>
        </div>
      </div>
    </section>
  <?php endif ?>
</main>

<br><br><br>
<?php get_footer() ?>