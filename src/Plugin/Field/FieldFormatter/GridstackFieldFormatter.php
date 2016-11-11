<?php
/**
 * Created by PhpStorm.
 * User: Odyssey
 * Date: 11/05/2016
 * Time: 19:49
 */

namespace Drupal\gridstack_field\Plugin\Field\FieldFormatter;


use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\gridstack_field\GridstackFieldHelperInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'postal_code' formatter.
 *
 * @FieldFormatter(
 *   id = "gridstack_field_formatter",
 *   label = @Translation("Gridstack field"),
 *   field_types = {
 *     "gridstack_field"
 *   }
 * )
 */
class GridstackFieldFormatter extends FormatterBase {

  private $helper;

  /**
   * {@inheritdoc}
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, GridstackFieldHelperInterface $helper) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
    $this->helper = $helper;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['third_party_settings'],
      $container->get('config.factory'),
      $container->get('gridstack_field.helper')
    );
  }

//  /**
//   * {@inheritdoc}
//   */
//  public static function defaultSettings() {
//    $settings = parent::defaultSettings();
//
//    $settings['height'] = '0';
//    $settings['width'] = '12';
//    $settings['cellHeight'] = '60';
//    $settings['minWidth'] = '768';
//    $settings['rtl'] = 'auto';
//    $settings['verticalMargin'] = '10';
//    $settings['animate'] = 0;
//    $settings['alwaysShowResizeHandle'] = 0;
//    $settings['auto'] = 1;
//    $settings['disableDrag'] = 0;
//    $settings['disableResize'] = 0;
//    $settings['draggable'] = 0;
//    $settings['float'] = 0;
//
//    return $settings;
//  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $settings = [
      'height' => '0',
      'width' => '12',
      'cellHeight' => '60',
      'minWidth' => '768',
      'rtl' => 'auto',
      'verticalMargin' => '10',
      'animate' => 0,
      'alwaysShowResizeHandle' => 0,
      'auto' => 1,
      'disableDrag' => 0,
      'disableResize' => 0,
      'float' => 0,
    ] + parent::defaultSettings();

    return $settings;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);

    $form['buuuuuuuu'] = [
      '#title' => $this->t('BUUUUUUUUUUUUUUUUU'),
      '#type' => 'checkbox',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];
    $helper = $this->helper;
    $settings = $this->getFieldSettings();
    $settings = $this->getSettings();


    foreach ($items as $delta => $item) {
      // If we have item then.
      if ($item['json']) {
        $formatted_text = $item['json'];
        // Show text with tags.
        $element[$delta]['#markup'] = $formatted_text;
      }

      // Converting options to boolean type for preventing issues
      // with incorrect types.
      $options = $helper->getOptions('bool');
      foreach ($options as $option) {
        $settings[$option] = (bool) $settings[$option];
      }

      // Converting options to int type for preventing issues
      // with incorrect types.
      $options = $helper->getOptions('int');
      foreach ($options as $option) {
        $settings[$option] = intval($settings[$option]);
      }

//      $this->setSettings($settings);

      // Pass settings into script.
      $build['#attached']['drupalSettings']['gridstack_field']['settings'] = $settings;

      // Add Backbone, Underscore and Gridstack libraries.
      $element['#attached']['library'][] = 'gridstack_field/gridstack_field.library';
    }

    return $element;


//    $element = [];
//
//    foreach ($items as $delta => $item) {
//      $element[$delta] = [
//        '#type' => 'markup',
//        '#markup' => $item->value,
//      ];
//    }
//
//    return $element;
  }
}
