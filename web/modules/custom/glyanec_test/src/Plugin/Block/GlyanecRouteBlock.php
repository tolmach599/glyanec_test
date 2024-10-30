<?php

namespace Drupal\glyanec_test\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Routing\CurrentRouteMatch;

/**
 * Provides a block to show route name.
 *
 * @Block(
 *   id = "glyanec_route_block",
 *   admin_label = @Translation("Glyanec route block")
 * )
 */
class GlyanecRouteBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The current route matcher.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected $routeMatcher;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CurrentRouteMatch $route_matcher) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routeMatcher = $route_matcher;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    /** @var \Drupal\Core\Routing\CurrentRouteMatch $route_matcher */
    $route_matcher = $container->get('current_route_match');

    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $route_matcher,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $current_route_name = $this->routeMatcher->getRouteName();

    $build = [
      '#markup' => $this->t('Current route:') . ' ' . $current_route_name,
      '#cache' => [
        'contexts' => [
          'url.path',
          'url.query_args',
        ],
      ],
    ];

    return $build;

  }

}
