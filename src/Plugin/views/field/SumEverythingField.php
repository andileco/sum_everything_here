<?php

namespace Drupal\sum_everything_here\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\field\NumericField;
use Drupal\views\ResultRow;

/**
 * @file
 * Defines Drupal\sum_everything_here\Plugin\views\field\SumEverythingField.
 */

/**
 * Field handler to flag the node type.
 *
 * @ingroup views_field_handlers
 * @ViewsField("field_sum_everything_field")
 */
class SumEverythingField extends NumericField {

  private $counter = 0;

  /**
   * Sets the initial Cumulative Field data at zero.
   */
  public function query() {
    $this->additional_fields['sum_everything_field_data'] = 0;
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['data_field'] = ['default' => NULL];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $form['data_field'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Data Fields'),
      '#options' => $this->displayHandler->getFieldLabels(),
      '#default_value' => $this->options['data_field'],
      '#description' => $this->t('Select the field for which to calculate the cumulative value.'),
      '#weight' => -10,
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getValue(ResultRow $values, $field = NULL) {
    parent::getValue($values, $field);

    $fields = $this->options['data_field'];
    $selected_fields = $this->view->display_handler->options['fields'];
    foreach ($selected_fields as $key => $value) {
      if ($value['exclude'] != false) {
        unset($selected_fields[$key]);
      }
    }
    $selected_fields = array_keys($selected_fields);
    foreach ($fields as $field) {
      if (!empty($field) && $field !== 0 && in_array($field, $selected_fields)) {
          $this->additional_fields['sum_everything_field_data']
            = $values->_entity->get($field)->value
            + $this->additional_fields['sum_everything_field_data'];
        $value = $this->additional_fields['sum_everything_field_data'];
      }
    }

    // A control for modules that modify the row count.
    $this->counter++;
    if ($this->counter == $this->view->total_rows) {
      $this->additional_fields['sum_everything_field_data'] = 0;
    }

    // A control to prevent adding across rows.
    $row_index = $this->view->row_index;

    $this->counter++;
    if ($this->counter > $row_index) {
      $this->additional_fields['sum_everything_field_data'] = 0;
    }

    return $value;
  }

}
