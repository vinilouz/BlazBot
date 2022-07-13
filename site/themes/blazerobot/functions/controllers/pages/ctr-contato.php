<?php

class CTR_Contato
{

  public static function get_content()
  {
    $fields = new stdClass();
    $fields->title = get_the_title();
    $fields->intro = get_field('intro');
    $fields->text = get_field('text');

    return $fields;
  }

  public function __construct()
  {
    add_action('wp_ajax_nopriv_send_form_contact', array($this, 'send_form_contact'));
    add_action('wp_ajax_send_form_contact', array($this, 'send_form_contact'));
  }

  public function send_form_contact()
  {
    $data = $_POST;

    $validate = $this->validate_form($data);
    if ($validate !== true) {
      wp_send_json_error($validate);
    }

    $contact = [
      'post_title' => __('Contato de', 'blazerobot') . ' ' . $data['ctt_name'],
      'post_status' => 'publish',
      'post_type' => 'contact_record'
    ];

    $contact_id = wp_insert_post($contact);
    update_post_meta($contact_id, 'type', 'Contato');
    update_post_meta($contact_id, 'name', $data['ctt_name']);
    update_post_meta($contact_id, 'email', $data['ctt_email']);
    update_post_meta($contact_id, 'phone', $data['ctt_phone']);
    update_post_meta($contact_id, 'company', $data['ctt_company']);
    update_post_meta($contact_id, 'partners', $data['ctt_partners']);
    update_post_meta($contact_id, 'message', $data['ctt_message']);
    update_post_meta($contact_id, 'news', $data['ctt_news']);

    $date = new DateTime("now",  new DateTimeZone('America/Sao_Paulo'));
    $news = $data['ctt_news'] ? __('Sim', 'blazerobot') : __('Não', 'blazerobot');
    $logo = theme_url('public/images/exit-logo.png');

    $body = file_get_contents(theme_url('partials/mail-template/mail-template-contact.php'));
    $body = str_replace("%titulo%",    __('Fale conosco', 'blazerobot'), $body);
    $body = str_replace("%logo%",     $logo, $body);
    $body = str_replace("%name%",     $data['ctt_name'], $body);
    $body = str_replace("%email%",    $data['ctt_email'], $body);
    $body = str_replace("%phone%",    $data['ctt_phone'], $body);
    $body = str_replace("%company%",  $data['ctt_company'], $body);
    $body = str_replace("%partners%", $data['ctt_partners'], $body);
    $body = str_replace("%message%",  $data['ctt_message'], $body);
    $body = str_replace("%news%",     $news, $body);
    $body = str_replace("%sent_date%", $date->format("d/m/Y H:i:s"), $body);

    $headers[] = "Content-type: text/html";
    $headers[] = 'Reply-To: ' . $data['ctt_name'] . ' <' . $data['ctt_email'] . '>';
    $subject   = $data['ctt_name'] . ' ' . __("- Página de Contato", 'blazerobot');

    $email = get_field('email', 'option', true);
    if (!$email) {
      $email = get_option('admin_email');
    }

    if ($mail = wp_mail($email, $subject, $body, $headers))
      wp_send_json_success(__('Mensagem enviada com sucesso!', 'blazerobot'));
    else
      wp_send_json_error(__('Erro ao enviar formulário.', 'blazerobot'));
    return $body;
  }

  public function validate_form($data)
  {
    if (!isset($data['ctt_name']) || $data['ctt_name'] == '') {
      return __('Insira um nome', 'blazerobot');
    }

    if (!isset($data['ctt_email']) || $data['ctt_email'] == '') {
      return __('Insira um e-mail', 'blazerobot');
    }

    if (!isset($data['ctt_phone']) || $data['ctt_phone'] == '') {
      return __('Insira um celular', 'blazerobot');
    }

    if (!isset($data['ctt_company']) || $data['ctt_company'] == '') {
      return __('Insira o nome da empresa', 'blazerobot');
    }

    if (!isset($data['ctt_partners']) || $data['ctt_partners'] == '') {
      return __('Insira os colaboradores', 'blazerobot');
    }

    if (!isset($data['ctt_agree']) || $data['ctt_agree'] == '') {
      return __('Concorde com processo das informações', 'blazerobot');
    }

    if (!isset($data['ctt_message']) || $data['ctt_message'] == '') {
      return __('Insira uma mensagem', 'blazerobot');
    }

    if (!isset($data['recaptcha_response']) || !$this->verify_recaptcha($data['recaptcha_response']))
      return __('Por favor insira todos os dados novamente.', 'blazerobot');

    return true;
  }

  /**
   * Utilizado para validar recaptcha do google
   * @param string $response
   * @return bool
   */
  private function verify_recaptcha($response)
  {
    // Build POST request:
    $recaptcha_url      = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret   = '6LejdowgAAAAABgZF-1449y2vCPW51tuS5X9JJr6';
    $recaptcha_response = $response;

    // Make and decode POST request:
    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);

    // Take action based on the score returned:
    if ($recaptcha->score >= 0.5) {
      return true;
    } else {
      return false;
    }
  }
}

new CTR_Contato();
