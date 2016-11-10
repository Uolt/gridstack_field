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
    $entity_info = entity_get_info('node');
    $view_modes = $entity_info['view modes'];
    $view_mode_settings = field_view_mode_settings('node', $type);
    $displays = array();
    foreach ($view_modes as $view_mode_name => $view_mode_info) {
      if (!empty($view_mode_settings[$view_mode_name]['custom_settings'])) {
        $displays[$view_mode_name] = $view_mode_info['label'];
      }
    }
    return $displays;
  }
}