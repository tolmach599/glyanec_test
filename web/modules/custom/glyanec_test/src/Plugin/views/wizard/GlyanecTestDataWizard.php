<?php

namespace Drupal\glyanec_test\Plugin\views\wizard;

use Drupal\views\Plugin\views\wizard\WizardPluginBase;
use Drupal\views\ViewEntityInterface;

/**
 * Glyanec test wizard plugin.
 *
 * @ViewsWizard(
 *   id = "glyanec_test_data_wizard",
 *   base_table = "glyanec_data_table",
 *   title = @Translation("Glyanec Test Data Wizard")
 * )
 */
class GlyanecTestDataWizard extends WizardPluginBase {

  /**
   * {@inheritdoc}
   */
  protected function defaultDisplay(ViewEntityInterface $view, $display_id, array &$display) {
    // Устанавливаем формат отображения и добавляем поля.
    $display['display_options']['style'] = [
      'type' => 'table',
      'options' => [],
    ];

    // Добавляем поля для отображения данных.
    $display['display_options']['fields']['title'] = [
      'id' => 'title',
      'table' => 'glyanec_data_table',
      'field' => 'title',
    ];
    $display['display_options']['fields']['description'] = [
      'id' => 'description',
      'table' => 'glyanec_data_table',
      'field' => 'description',
    ];
    $display['display_options']['fields']['created'] = [
      'id' => 'created',
      'table' => 'glyanec_data_table',
      'field' => 'created',
      'plugin_id' => 'date',
    ];

    // Добавляем сортировку по дате создания.
    $display['display_options']['sorts']['created'] = [
      'id' => 'created',
      'table' => 'glyanec_data_table',
      'field' => 'created',
      'order' => 'DESC',
    ];
  }

}
