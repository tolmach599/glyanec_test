<?php

namespace Drupal\glyanec_test\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DateFormatterInterface;

/**
 * Class SiteInfoExtension.
 *
 * Add custom Twig function.
 */
class SiteInfoExtension extends AbstractExtension {

  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * SiteInfoExtension constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   Date formatter.
   */
  public function __construct(ConfigFactoryInterface $config_factory, DateFormatterInterface $date_formatter) {
    $this->configFactory = $config_factory;
    $this->dateFormatter = $date_formatter;
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new TwigFunction('site_info', [$this, 'getSiteInfo']),
    ];
  }

  /**
   * Prepare the site name and date-time values.
   */
  public function getSiteInfo() {
    $site_name = $this->configFactory->get('system.site')->get('name');
    $timestamp = time();
    $formatted_date = $this->dateFormatter->format($timestamp, 'date_time_glyanec');

    return $site_name . ' - ' . $formatted_date;
  }

  /**
   * Return extension name.
   */
  public function getName() {
    return 'site_info_extension';
  }

}
