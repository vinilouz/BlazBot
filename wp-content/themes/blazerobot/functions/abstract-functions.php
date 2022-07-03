<?php
if (!defined('WPINC')) {
  header('Location: /');
  exit;
}

// --------------------------------

/**
 * Classe
 */
class Abstract_Functions
{

  /**
   * Retorna a variável get_template_diretory_uri()
   * @param $path - (optional) Caminho dentro do tema
   */
  public static function theme_url($path)
  {
    $path = (null !== $path) ? "/{$path}" : '';
    return get_template_directory_uri() . $path;
  }

  /**
   * Retorna a função get_template_part( $slug, $name = '' )
   */
  public static function inc($slug, $name)
  {
    get_template_part($slug, $name);
  }

  /**
   * Retorna a URL da página passada em $slug
   */
  public static function page_url($slug)
  {
    return get_permalink(get_page_by_path($slug));
  }

  // -----------------------------------------------------------------------------

  public static function convertPhoneStringToDirectCall($phone)
  {
    return  preg_replace('/\D/', '', $phone);
  }

  // -----------------------------------------------------------------------------


  /**
   * Easy pre response
   *
   * @param $variable
   * @return string
   */
  public static function pre($variable)
  {
    $pre = "<pre style='all:revert;background:#3e3e3e;color:#fff;padding:20px;z-index:999;position:relative;overflow:auto;text-align:left;'>";
    $pre .= print_r($variable, true);
    $pre .= "</pre>";
    if (current_user_can('administrator')) {
      echo $pre;
    }
  }

  /**
   * Page type
   * @return string
   */
  public static function page_type()
  {
    global $wp_query;
    $loop = 'notfound';

    if ($wp_query->is_page && !$wp_query->is_tax) {
      $loop = is_front_page() ? 'front' : 'page';
    } elseif ($wp_query->is_home) {
      $loop = 'home';
    } elseif ($wp_query->is_singular) {
      $loop = ($wp_query->is_attachment) ? 'attachment' : 'single';
    } elseif ($wp_query->is_category) {
      $loop = 'category';
    } elseif ($wp_query->is_tag) {
      $loop = 'tag';
    } elseif ($wp_query->is_tax) {
      $loop = 'tax';
    } elseif ($wp_query->is_archive) {
      if ($wp_query->is_day) {
        $loop = 'day';
      } elseif ($wp_query->is_month) {
        $loop = 'month';
      } elseif ($wp_query->is_year) {
        $loop = 'year';
      } elseif ($wp_query->is_author) {
        $loop = 'author';
      } else {
        $loop = 'archive';
      }
    } elseif ($wp_query->is_search) {
      $loop = 'search';
    } elseif ($wp_query->is_404) {
      $loop = 'notfound';
    }

    return $loop;
  }

  /**
   * Slugify a string
   *
   * @param string $text
   * @param string $divider
   * @return string
   */
  public static function slugify($text, string $divider = '-')
  {
    // replace non letter or digits by divider
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, $divider);

    // remove duplicate divider
    $text = preg_replace('~-+~', $divider, $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
      return 'n-a';
    }

    return $text;
  }

  /**
   * Get page object by template name
   * @param string $template_name
   * @return object
   */
  public static function get_page_by_template($template_name)
  {
    $pages = get_pages(
      array(
        'meta_key' => '_wp_page_template',
        'meta_value' => $template_name . '.php'
      )
    );

    $page = null;

    if ($pages) {
      $page = array_shift($pages);
    }

    return $page;
  }

  /**
   * Encurtador de string
   *
   * @param string $string
   * @param integer $length
   * @param string $stringFim
   * @param boolean $removerTags
   * @return string
   */
  public static function read_more(string $string = null, int $length = null, string $stringFim = null, bool $removerTags = null)
  {
    $string = trim($string);
    if ($removerTags)
      $string = strip_tags($string);
    if (strlen($string) <= $length)
      return $string;
    $length -= strlen($stringFim);
    $string = substr($string, 0, $length);
    return $string . $stringFim;
  }

  /**
   * Converter url youtube para embed url
   * @param string $string
   */
  public static function youtube_embed(string $url = null)
  {
    if (strpos($url, 'youtube') !== false) {
      parse_str(parse_url($url, PHP_URL_QUERY), $id);
      $id = $id['v'];
      $embed_url = "https://www.youtube.com/embed/$id";
      return $embed_url;
    }
    return $url;
  }
} // Abstract_Functions


/**
 * Funções
 */

function page_type()
{
  return Abstract_Functions::page_type();
}

function theme_url($path = null)
{
  return Abstract_Functions::theme_url($path);
}

function get_page_by_template($template_name)
{
  return Abstract_Functions::get_page_by_template($template_name);
}

function youtube_embed($url = null)
{
  return Abstract_Functions::youtube_embed($url);
}

function pre($variable = null)
{
  return Abstract_Functions::pre($variable);
}

function slugify($text = null, $divider = '-')
{
  return Abstract_Functions::slugify($text, $divider);
}

function inc($slug, $name = null)
{
  return Abstract_Functions::inc($slug, $name);
}

function convertPhoneStringToDirectCall($phone)
{
  return Abstract_Functions::convertPhoneStringToDirectCall($phone);
}

function page_url($slug)
{
  return Abstract_Functions::page_url($slug);
}

function read_more(string $string, int $length, string $stringFim = '', bool $removerTags = false)
{
  return Abstract_Functions::read_more($string, $length, $stringFim, $removerTags);
}
