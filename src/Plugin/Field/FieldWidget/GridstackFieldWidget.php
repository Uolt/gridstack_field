<?php
/**
 * Created by PhpStorm.
 * User: Odyssey
 * Date: 11/05/2016
 * Time: 19:50
 */

namespace Drupal\gridstack_field\Plugin\Field\FieldWidget;


use Drupal\Component\Utility\Html;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\gridstack_field\GridstackFieldHelperInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'postal_code' widget.
 *
 * @FieldWidget(
 *   id = "gridstack_field_widget",
 *   module = "gridstack_field",
 *   label = @Translation("Gridstack field"),
 *   field_types = {
 *     "gridstack_field"
 *   }
 * )
 */
class GridstackFieldWidget extends WidgetBase implements ContainerFactoryPluginInterface {

  /**
   * The Config factory service.
   *
   * @var
   */
  protected $config;

  /**
   * @var \Drupal\gridstack_field\GridstackFieldHelperInterface
   */
  private $helper;

  /**
   * {@inheritdoc}
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, GridstackFieldHelperInterface $helper) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
    $this->helper = $helper;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
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

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $helper = $this->helper;
    $settings = $this->getFieldSettings();
    $settings = $this->getSettings();


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
    // Pass settings into script.
    $build['#attached']['drupalSettings']['gridstack_field']['settings'] = $settings;

    // Add Backbone, Underscore and Gridstack libraries.
    $element['#attached']['library'][] = 'gridstack_field/gridstack_field.library';

    $value = isset($items[$delta]['json']) ? $items[$delta]['json'] : '';
    $element['items'] = [
      '#markup' => '<div class="gridstack-items"><div class="grid-stack"></div></div>',
    ];
    $element['gridstack_group'] = [
      '#type' => 'fieldset',
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
      '#title' => Html::escape(isset($element['#title']) ? $element['#title'] : ''),
      '#description' => Html::escape(isset($element['#description']) ? $element['#description'] : ''),
    ];
    $element['gridstack_group']['add_item'] = [
      '#type' => 'button',
      '#button_type' => 'button',
      '#value' => $this->t('Add item'),
      '#executes_submit_callback' => FALSE,
    ];
    $element['gridstack_group']['gridstack_autocomplete'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Node'),
      '#maxlength' => 60,
      '#autocomplete_path' => 'gridstack_field/' . $field['field_name'] . '/autocomplete',
    ];
    $element['json'] = [
      '#type' => 'textfield',
      '#default_value' => $value,
      '#maxlength' => 2048,
      '#size' => 60,
    ];

    return $element;



//    $value = isset($items[$delta]->value) ? $items[$delta]->value : '';
//    $element += array(
//      '#type' => 'textfield',
//      '#default_value' => $value,
//      '#size' => 16,
//      '#maxlength' => 16,
//      '#element_validate' => array(
//        array($this, 'validate'),
//      ),
//      '#description' => $this->t('Select country for validation'),
//    );
//    return array('value' => $element);
  }

  /**
   * {@inheritdoc}
   */
  public function validate($element, FormStateInterface $form_state) {
//    $field_settings = $this->getFieldSettings();
//    $validator = $this->postalCodeValidation;
//    $config = $this->config;
//    $value = trim($element['#value']);
  }
}
