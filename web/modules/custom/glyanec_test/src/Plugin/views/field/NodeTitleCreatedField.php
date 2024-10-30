<?php

namespace Drupal\glyanec_test\Plugin\views\field;

use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
use Drupal\Core\Render\Markup;
use Drupal\Core\Datetime\DrupalDateTime;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Defines a custom field that shows the node title and creation date.
 *
 * @ViewsField("node_title_created_field")
 */
class NodeTitleCreatedField extends FieldPluginBase implements ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Add 'title' and 'created' fields to the view's SQL query.
    $this->addAdditionalFields(['title', 'created']);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $title = $this->getValue($values, 'title');
    $created_timestamp = $values->_entity->getCreatedTime();
    $created_date = (new DrupalDateTime())->setTimestamp($created_timestamp)->format('d.m.Y H:i:s');

    return Markup::create($this->sanitizeValue($title) . ' - ' . $created_date);
  }

}
