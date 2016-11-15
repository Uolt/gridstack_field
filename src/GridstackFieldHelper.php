<?php

namespace Drupal\gridstack_field;




class GridstackFieldHelper implements GridstackFieldHelperInterface {
  /**
   * {@inheritdoc}
   */
  public function getOptions($type) {
    $options = array();

    switch ($type) {
      case 'bool':
        $options = array(
          'animate',
          'alwaysShowResizeHandle',
          'auto',
          'disableDrag',
          'disableResize',
          'float',
        );
        break;

      case 'int':
        $options = array(
          'height',
          'width',
          'cellHeight',
          'minWidth',
          'rtl',
          'verticalMargin',
        );
        break;

      default:
        break;
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function getDisplays($type) {
    $view_modes = \Drupal::entityManager()->getViewModes('node');
    $view_mode_settings = \Drupal::entityManager()->getViewModeOptionsByBundle('node', $type);


//    $view_mode_settings2 = \Drupal::entityManager()->getViewModes('node');
//    $view_mode_settings3 = \Drupal::entityManager()->getViewModeOptions('node');
//    $view_mode_settings4 = \Drupal::entityManager()->getViewModeOptionsByBundle('node', $type);
//    echo '<pre>getViewModes(node):<br>' . print_r($view_mode_settings2, 1) . '</pre>';


    $displays = array();
    foreach ($view_modes as $view_mode_name => $view_mode_info) {
      if (isset($view_mode_settings[$view_mode_name])) {
        $displays[$view_mode_name] = $view_mode_info['label'];
      }
    }
    return $displays;
  }

  /**
   * Static method for calling getOptions.
   */
  public static function getOptionsHelper($type) {
    return self::getOptions($type);
  }

  /**
   * Static method for calling getDisplays.
   */
  public static function getDisplaysHelper($type) {
    return self::getDisplays($type);
  }
}
