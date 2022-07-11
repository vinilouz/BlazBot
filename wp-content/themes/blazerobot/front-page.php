<?php
if (!defined('WPINC')) {
  header('Location: /');
  exit;
}

// Get custom fields
// $cf = CTR_Home::get_content();


get_header() ?>

<main class="main-home">
  <section class="s-hero-home">
    <?php
    $users = get_users([
      'meta_key' => 'status',
      'meta_value' => 1
    ]);
    $list = get_field('signals_list', 'option');
    $last_row = end($list);
    pre($last_row);
    ?>
  </section>


  <section class="s-hero-home">
    <div class="row">
      <div class="wrapper">
        <?php
        $list = get_field('signals_list', 'option');

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
        $list = get_field('signals_list', 'option');

        $sg = 0;
        $g1 = 0;
        $g2 = 0;
        $bsg = 0;
        $bg1 = 0;
        $bg2 = 0;
        $loss = 0;
        $active = false;
        foreach ($list as $k => $signal) {
          if ($active) {
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

          if ($signal['result']  == "LOSS") {
            $active = true;
          } else {
            $active = false;
          }
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
</main>

<?php get_footer() ?>