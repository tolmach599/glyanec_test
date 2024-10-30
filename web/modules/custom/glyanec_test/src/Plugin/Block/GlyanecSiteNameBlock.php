<?php

namespace Drupal\glyanec_test\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a block to show the site name.
 *
 * @Block(
 *   id = "glyanec_site_name_block",
 *   admin_label = @Translation("Glyanec site name block")
 * )
 */
class GlyanecSiteNameBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Configuration manager.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    /** @var \Drupal\Core\Config\ConfigFactoryInterface $config_factory */
    $config_factory = $container->get('config.factory');

    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $config_factory,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $site_config = $this->configFactory->get('system.site');
    $site_name = $site_config->get('name') ?? '';

    return [
      '#theme' => 'glyanec_site_name_block_template',
      '#site_name' => $site_name ? $this->t('HelloWorld') . ' - ' . $site_name : '',
    ];
  }

}
