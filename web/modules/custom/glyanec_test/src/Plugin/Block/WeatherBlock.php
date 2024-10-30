<?php

namespace Drupal\glyanec_test\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\glyanec_test\Service\WeatherService;

/**
 * Provides a 'Weather' block.
 *
 * @Block(
 *   id = "weather_block",
 *   admin_label = @Translation("Weather Block"),
 * )
 */
class WeatherBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Weather service.
   *
   * @var \Drupal\glyanec_test\Service\WeatherService
   */
  protected $weatherService;

  /**
   * WeatherBlock constructor.
   *
   * @param array $configuration
   *   Configuration.
   * @param string $plugin_id
   *   Plugin ID.
   * @param mixed $plugin_definition
   *   Plugin definition.
   * @param \Drupal\glyanec_test\Service\WeatherService $weather_service
   *   Weather service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, WeatherService $weather_service) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->weatherService = $weather_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('glyanec_test.weather_service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Probably need to be stored in module config.
    // To be supported later.
    $latitude = 49.99081;
    $longitude = 36.22398;

    $weather_data = $this->weatherService->getCurrentWeather($latitude, $longitude);

    return [
      '#theme' => 'weather_block',
      '#weather' => $weather_data,
      '#cache' => [
        'contexts' => [],
        'tags' => [],
        'max-age' => 3600,
      ],
    ];
  }

}
