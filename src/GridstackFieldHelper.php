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
    $entity_info = \Drupal::entityTypeManager()->getDefinition('node');
//    $view_modes = $entity_info['view modes'];
    $view_modes = \Drupal::entityManager()->getViewModeOptionsByBundle('node', $type);
//    echo '<pre>' . print_r($view_modes, 1) . '</pre>';die;
//    $view_mode_settings = field_view_mode_settings('node', $type);
    $view_mode_settings = \Drupal::entityManager()->getViewModeOptionsByBundle('node', $type);
    $displays = array();
    foreach ($view_modes as $view_mode_name => $view_mode_info) {
//      if (!empty($view_mode_settings[$view_mode_name]['custom_settings'])) {
//        $displays[$view_mode_name] = $view_mode_info['label'];
//      }
      dsm($view_mode_name);
      dsm($view_mode_info);
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