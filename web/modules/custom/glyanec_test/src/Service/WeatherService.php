<?php

namespace Drupal\glyanec_test\Service;

use GuzzleHttp\ClientInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;

/**
 * Receive weather data from OpenWeather API.
 */
class WeatherService {

  /**
   * HTTP client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * The module config.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Logger.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * WeatherService constructor.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   HTTP client.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Configurations.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   Logger factory.
   */
  public function __construct(ClientInterface $http_client, ConfigFactoryInterface $config_factory, LoggerChannelFactoryInterface $logger_factory) {
    $this->httpClient = $http_client;
    $this->configFactory = $config_factory;
    $this->logger = $logger_factory->get('glyanec_test');
  }

  /**
   * Receive data about current weather.
   *
   * @param float $latitude
   *   Latitude.
   * @param float $longitude
   *   Londitude.
   *
   * @return array
   *   Data about weather.
   */
  public function getCurrentWeather(float $latitude, float $longitude) {
    // It should be supported by configuration form for the module.
    // To be supported later
    // $api_key = $this->configFactory->get('glyanec_test.settings')->get('openweather_api_key');
    $api_key = '42d04ead7abb234d51741976869576d8';
    $url = "https://api.openweathermap.org/data/2.5/weather?lat={$latitude}&lon={$longitude}&units=metric&exclude=minutely,hourly,daily,alerts&appid={$api_key}";

    try {
      $response = $this->httpClient->request('GET', $url);
      $data = json_decode($response->getBody()->getContents(), TRUE);

      return $data;
    }
    catch (\Exception $e) {
      $this->logger->error('Error fetching weather data: @message', ['@message' => $e->getMessage()]);

      return [];
    }
  }

}
