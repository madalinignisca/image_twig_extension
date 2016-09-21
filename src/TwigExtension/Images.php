<?php

namespace Drupal\image_twig_extension\TwigExtension;

use Drupal\Core\Template\TwigExtension;
use Drupal\Core\Render\Renderer;
use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;

/**
 * Class Images.
 *
 * @package Drupal\image_twig_extension
 */
class Images extends TwigExtension {


   /**
    * {@inheritdoc}
    */
    public function getTokenParsers() {
      return [];
    }

   /**
    * {@inheritdoc}
    */
    public function getNodeVisitors() {
      return [];
    }

   /**
    * {@inheritdoc}
    */
    public function getFilters() {
      return [];
    }

   /**
    * {@inheritdoc}
    */
    public function getTests() {
      return [];
    }

   /**
    * {@inheritdoc}
    */
    public function getFunctions() {
      return array(
        new \Twig_SimpleFunction('image_style', array($this, 'imageStyle'), array('is_safe' => array('html'))),
      );
    }

   /**
    * {@inheritdoc}
    */
    public function getOperators() {
      return [];
    }

   /**
    * {@inheritdoc}
    */
    public function getName() {
      return 'image_twig_extension.twig.extension';
    }

    /**
   * Generate images styles for given image
   */
  public static function imageStyle($file_id, $styles) {
    // Convert the $file_id if it's a token from a views replacement pattern
    if (is_object($file_id) && get_class($file_id) == 'Drupal\Core\Render\Markup') {
      $file_id = (string) $file_id;
      $file_id = (int) $file_id;
    }
    $file = File::load($file_id);
    $transform = array();
    if (isset($file->uri->value)) {
      $transform['source'] = $file->url();
      foreach ($styles as $style) {
        $transform[$style] = ImageStyle::load($style)->buildUrl($file->uri->value);
      }
    }
    return $transform;
  }

}
