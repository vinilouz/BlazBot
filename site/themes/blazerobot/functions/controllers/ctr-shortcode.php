<?php

class CTR_Shortcode
{
  /**
   * Construtor
   */
  function __construct()
  {
    add_shortcode('results', [$this, 'handler_results_no_gale']);
    // add_shortcode('strategy_1', [$this, 'handler_results_no_gale']);
    // add_shortcode('strategy_2', [$this, 'handler_strategy_l2']);
  }

  function handler_results_no_gale($atts, $content = null)
  {
    date_default_timezone_set('America/Sao_Paulo');
    $default = ['date' => 'today', 'strategy' => '1loss'];
    $filter = shortcode_atts($default, $atts);

    $green = $loss = 0;
    $list = get_field('signals_list', 'option');

    switch ($filter['date']) {
      case 'today':
        $dataIni = date('dmY');
        $dataFim = date('dmY');
        break;
      case 'yesterday':
        $dataIni = date('dmY', strtotime("-1 days"));
        $dataFim = date('dmY', strtotime("-1 days"));
        break;
      case 'week':
        $dataIni = date("dmY", strtotime('monday this week'));
        $dataFim = date("dmY", strtotime('sunday this week'));
        break;
      case 'month':
        $dataIni = date('01mY');
        $dataFim = date('tmY');
        break;
    }

    foreach ($list as $signal) {
      $match_date = date_create_from_format('d/m/Y g:i a', $signal['date']);
      $match_date = $match_date->format('dmY');
      if ($match_date >= $dataIni && $match_date <= $dataFim) {
          switch ($signal['result']) {
            case 'WIN':
              $green++;
              break;
            case 'LOSS':
              $loss++;
              break;
          }
      }
    }

    $html = <<<EOD
      <div style="display: flex;">
        <span>GREEN: $green</span>&nbsp;&nbsp;x&nbsp;&nbsp;<span>LOSS: $loss</span>
      </div>
    EOD;

    return $html;
  }

  function handler_results_with_gales($atts, $content = null)
  {
    date_default_timezone_set('America/Sao_Paulo');
    $default = ['date' => 'today', 'strategy' => '1loss'];
    $filter = shortcode_atts($default, $atts);

    $sg = $g1 = $g2 = $bsg = $bg1 = $bg2 = $loss = 0;
    $list = get_field('signals_list', 'option');

    switch ($filter['date']) {
      case 'today':
        $dataIni = date('dmY');
        $dataFim = date('dmY');
        break;
      case 'yesterday':
        $dataIni = date('dmY', strtotime("-1 days"));
        $dataFim = date('dmY', strtotime("-1 days"));
        break;
      case 'week':
        $dataIni = date("dmY", strtotime('monday this week'));
        $dataFim = date("dmY", strtotime('sunday this week'));
        break;
      case 'month':
        $dataIni = date('01mY');
        $dataFim = date('tmY');
        break;
    }

    foreach ($list as $signal) {
      $match_date = date_create_from_format('d/m/Y g:i a', $signal['date']);
      $match_date = $match_date->format('dmY');
      if ($match_date >= $dataIni && $match_date <= $dataFim) {
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
    }

    $green = $sg + $g1 + $g2 + $bsg + $bg1 + $bg2;

    $html = <<<EOD
      <ul style="margin-top: -20px;">
        <li>SG: $sg</li>
        <li>G1: $g1</li>
        <li>G2: $g2</li>
        <li>Branco SG: $bsg</li>
        <li>Branco G1: $bg1</li>
        <li>Branco G2: $bg2</li>
      </ul>
      <div style="display: flex;">
        <span>GREEN: $green</span>&nbsp;&nbsp;x&nbsp;&nbsp;<span>LOSS: $loss</span>
      </div>
    EOD;

    return $html;
  }

  // function handler_strategy_l1($atts, $content = null)
  // {
  //   date_default_timezone_set('America/Sao_Paulo');

  //     $default = ['date' => 'today'];
  //     $filter = shortcode_atts($default, $atts);
  
  //     $active = $sg = $g1 = $g2 = $bsg = $bg1 = $bg2 = $loss = 0;
  //     $list = get_field('signals_list', 'option');
  
  //     switch ($filter['date']) {
  //       case 'today':
  //         $dataIni = date('dmY');
  //         $dataFim = date('dmY');
  //         break;
  //       case 'yesterday':
  //         $dataIni = date('dmY', strtotime("-1 days"));
  //         $dataFim = date('dmY', strtotime("-1 days"));
  //         break;
  //       case 'week':
  //         $dataIni = date("dmY", strtotime('monday this week'));
  //         $dataFim = date("dmY", strtotime('sunday this week'));
  //         break;
  //       case 'month':
  //         $dataIni = date('01mY');
  //         $dataFim = date('tmY');
  //         break;
  //     }
  
  //     foreach ($list as $signal) {
  //       $match_date = date_create_from_format('d/m/Y g:i a', $signal['date']);
  //       $match_date = $match_date->format('dmY');
  
  //       if ($active == 1) {
  //         if ($match_date >= $dataIni && $match_date <= $dataFim) {
  //           if ($signal['white']) {
  //             switch ($signal['result']) {
  //               case 'SG':
  //                 $bsg++;
  //                 break;
  //               case 'G1':
  //                 $bg1++;
  //                 break;
  //               case 'G2':
  //                 $bg2++;
  //                 break;
  //             }
  //           } else {
  //             switch ($signal['result']) {
  //               case 'SG':
  //                 $sg++;
  //                 break;
  //               case 'G1':
  //                 $g1++;
  //                 break;
  //               case 'G2':
  //                 $g2++;
  //                 break;
  //               case 'LOSS':
  //                 $loss++;
  //                 break;
  //             }
  //           }
  //         }
  //       }
  //       $active = $signal['result']  == "LOSS" ? 1 : 0;
  //     }
  
  //     $green = $sg + $g1 + $g2 + $bsg + $bg1 + $bg2;
  
  //     $html = <<<EOD
  //       <ul style="margin-top: -20px;">
  //         <li>SG: $sg</li>
  //         <li>G1: $g1</li>
  //         <li>G2: $g2</li>
  //         <li>Branco SG: $bsg</li>
  //         <li>Branco G1: $bg1</li>
  //         <li>Branco G2: $bg2</li>
  //       </ul>
  //       <div style="display: flex;">
  //         <span>GREEN: $green</span>&nbsp;&nbsp;x&nbsp;&nbsp;<span>LOSS: $loss</span>
  //       </div>
  //     EOD;
  
  //     return $html;
  // }
}

new CTR_Shortcode();
