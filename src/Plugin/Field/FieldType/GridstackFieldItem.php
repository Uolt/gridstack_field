<?php
/**
 * Created by PhpStorm.
 * User: Odyssey
 * Date: 11/05/2016
 * Time: 19:50
 */

namespace Drupal\gridstack_field\Plugin\Field\FieldType;


use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\TypedData\DataDefinitionInterface;
use Drupal\Core\TypedData\TypedDataInterface;
use Drupal\gridstack_field\GridstackFieldHelperInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'postal_code' field type.
 *
 * @FieldType(
 *   id = "gridstack_field",
 *   label = @Translation("Gridstack field"),
 *   module = "gridstack_field",
 *   description = @Translation("Implements gridtack plugin."),
 *   default_widget = "gridstack_field_widget",
 *   default_formatter = "gridstack_field_formatter"
 * )
 */
class GridstackFieldItem extends FieldItemBase {

  private $helper;

  private $html;

  /**
   * {@inheritdoc}
   */
  public function __construct(DataDefinitionInterface $definition, $name, TypedDataInterface $parent, GridstackFieldHelperInterface $helper) {
    parent::__construct($definition, $name, $parent);
    $this->helper = $helper;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
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
    ] + parent::defaultFieldSettings();

    return $settings;
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['json'] = DataDefinition::create('string');
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::fieldSettingsForm($form, $form_state);
    $settings = $this->getSettings();


//    $defaults['row_setting'] = field_info_field_settings($field['type']);
//    $defaults['row_setting'] = $this->defaultFieldSettings();
//    $settings = array_merge($defaults, $this->getSettings());


    foreach (node_type_get_types() as $key => $type) {
      $form[$key] = [
        '#type'  => 'checkbox',
        '#title' => Html::escape($type->get('name')),
        '#default_value' => !empty($settings[$key]) ? $settings[$key] : 0,
      ];
      $displays = $this->helper->getDisplays($key);
      $form[$key . '_display'] = [
        '#type'   => 'container',
        '#states' => [
          'visible' => [
            ':input[name="field[settings][' . $key . ']"]' => ['checked' => TRUE],
          ],
        ],
      ];
      $form[$key . '_display']['settings'] = [
        '#type'    => 'radios',
        '#options' => $displays,
        '#default_value' => !empty($settings[$key . '_display']) ? $settings[$key . '_display']['settings'] : 0,
      ];
    }

    // Settings for Gridstack plugin.
    $form['row_setting'] = [
      '#type' => 'fieldset',
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
      '#title' => t('Row settings'),
    ];
    $form['row_setting']['height'] = [
      '#title' => t('Height'),
      '#type' => 'textfield',
      '#default_value' => $settings['height'],
      '#maxlength' => 60,
      '#size' => 60,
    ];
    $form['row_setting']['width'] = [
      '#title' => t('Width'),
      '#type' => 'textfield',
      '#default_value' => $settings['width'],
      '#maxlength' => 60,
      '#size' => 60,
    ];
    $form['row_setting']['cellHeight'] = [
      '#title' => t('Cell height'),
      '#type' => 'textfield',
      '#default_value' => $settings['cellHeight'],
      '#maxlength' => 60,
      '#size' => 60,
    ];
    $form['row_setting']['minWidth'] = [
      '#title' => t('Min width'),
      '#type' => 'textfield',
      '#default_value' => $settings['minWidth'],
      '#maxlength' => 60,
      '#size' => 60,
    ];
    $form['row_setting']['rtl'] = [
      '#title' => t('RTL'),
      '#type' => 'textfield',
      '#default_value' => $settings['rtl'],
      '#maxlength' => 60,
      '#size' => 60,
    ];
    $form['row_setting']['verticalMargin'] = [
      '#title' => t('Vertical margin'),
      '#type' => 'textfield',
      '#default_value' => $settings['verticalMargin'],
      '#maxlength' => 60,
      '#size' => 60,
    ];
    $form['row_setting']['animate'] = [
      '#type'  => 'checkbox',
      '#title' => t('Animate'),
      '#default_value' => $settings['animate'],
    ];
    $form['row_setting']['alwaysShowResizeHandle'] = [
      '#type'  => 'checkbox',
      '#title' => t('Always show resize handle'),
      '#default_value' => $settings['alwaysShowResizeHandle'],
    ];
    $form['row_setting']['auto'] = [
      '#type'  => 'checkbox',
      '#title' => t('Auto'),
      '#default_value' => $settings['auto'],
    ];
    $form['row_setting']['disableDrag'] = [
      '#type'  => 'checkbox',
      '#title' => t('Disable drag'),
      '#default_value' => $settings['disableDrag'],
    ];
    $form['row_setting']['disableResize'] = [
      '#type'  => 'checkbox',
      '#title' => t('Disable resize'),
      '#default_value' => $settings['disableResize'],
    ];
    $form['row_setting']['float'] = [
      '#type'  => 'checkbox',
      '#title' => t('float'),
      '#default_value' => $settings['float'],
    ];

    return $form;



    // Get base form from FileItem.
//    $element = parent::fieldSettingsForm($form, $form_state);
//    $settings = $this->getSettings();
//
//    $options = array('any' => (string) $this->t('Any'));
//    $postal_code_validation_data = $this->postalCodeValidation->getValidationPatterns();
//
//    $countrylist = CountryManager::getStandardList();
//
//    foreach ($postal_code_validation_data as $country => $regex) {
//      $options[$country] = $countrylist[Unicode::strtoupper($country)]->render();
//    }
//
//    $value = isset($settings['country_select']) ? $settings['country_select'] : 'any';
//
//    $element['country_select'] = array(
//      '#type' => 'select',
//      '#title' => $this->t('Country'),
//      '#options' => $options,
//      '#default_value' => $value,
//      '#description' => $this->t('Select country for validation'),
//    );

//    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'json' => [
          'type' => 'text',
          'size' => 'medium',
          'not null' => FALSE,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('json')->getValue();
    return $value === NULL || $value === '';

//    if (empty($item['json'])) {
//      return TRUE;
//    }
  }
}
