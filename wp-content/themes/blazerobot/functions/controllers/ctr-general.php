<?

/**
 * Class for Header and Footer
 */
class CTR_General
{

  public function __construct()
  {
  }


  /**
   * Retorna o Whatsapp da Empresa
   * @param false $href_mode Utilizado para enviar somente os números
   *
   * @return string
   */
  public static function get_general_whatsapp($href_mode = false)
  {
    $whatsapp = get_field('whatsapp', 'option');

    if ($href_mode) return preg_replace('/\D/', '', $whatsapp);
    if ($whatsapp) return $whatsapp;

    return false;
  }
}

new CTR_General();
