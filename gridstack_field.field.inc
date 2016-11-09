<?php

/**
 * @file
 * Implement an gridstack field.
 */

/**
 * Implements hook_field_info().
 */
//function gridstack_field_field_info() {
//  return array(
//    'gridstack_field' => array(
//      'label' => t('Gridstack field'),
//      'description' => t('Implements gridtack plugin'),
//      'default_widget' => 'gridstack_field_widget',
//      'default_formatter' => 'gridstack_field_formatter',
//      'settings' => array(
//        'height' => '0',
//        'width' => '12',
//        'cellHeight' => '60',
//        'minWidth' => '768',
//        'rtl' => 'auto',
//        'verticalMargin' => '10',
//        'animate' => 0,
//        'alwaysShowResizeHandle' => 0,
//        'auto' => 1,
//        'disableDrag' => 0,
//        'disableResize' => 0,
//        'float' => 0,
//      ),
//    ),
//  );
//}

/**
 * Implements hook_field_formatter_info().
 */
//function gridstack_field_field_formatter_info() {
//  return array(
//    'gridstack_field_formatter' => array(
//      'label' => t('Gridstack field'),
//      'field types' => array('gridstack_field'),
//      'settings'  => array(
//        'height' => '0',
//        'width' => '12',
//        'cellHeight' => '60',
//        'minWidth' => '768',
//        'rtl' => 'auto',
//        'verticalMargin' => '10',
//        'animate' => 0,
//        'alwaysShowResizeHandle' => 0,
//        'auto' => 1,
//        'disableDrag' => 0,
//        'disableResize' => 0,
//        'draggable' => 0,
//        'float' => 0,
//      ),
//    ),
//  );
//}

/**
 * Implements hook_field_formatter_view().
 */
function gridstack_field_field_formatter_view($entity_type, $entity, $field, $instance, $langcode, $items, $display) {
  $element = array();

  // Processing format types.
  switch ($display['type']) {
    case 'gridstack_field_formatter':
      foreach ($items as $delta => $item) {
        // If we have item then.
        if ($item['json']) {
          $formatted_text = $item['json'];
          // Show text with tags.
          $element[$delta]['#markup'] = $formatted_text;
        }

        // Converting options to boolean type for preventing issues
        // with incorrect types.
        $options = _gridstack_field_get_options('bool');
        foreach ($options as $option) {
          $field['settings']['row_setting'][$option] = (bool) $field['settings']['row_setting'][$option];
        }

        // Converting options to int type for preventing issues
        // with incorrect types.
        $options = _gridstack_field_get_options('int');
        foreach ($options as $option) {
          $field['settings']['row_setting'][$option] = intval($field['settings']['row_setting'][$option]);
        }

        // Pass settings into script.
        drupal_add_js(array('gridstack_field' => $field['settings']), array('type' => 'setting'));

        // Add Backbone, Underscore and Gridstack libraries.
        drupal_add_library('gridstack_field', 'gridstack', TRUE);
        drupal_add_library('gridstack_field', 'gridstack_field_lib', TRUE);
      }
      break;
  }
  return $element;
}



