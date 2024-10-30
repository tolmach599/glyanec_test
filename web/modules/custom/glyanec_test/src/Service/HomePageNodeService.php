<?php

namespace Drupal\glyanec_test\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\path_alias\AliasManagerInterface;
use Drupal\node\Entity\Node;

/**
 * Service to get node which was set as a home.
 */
class HomePageNodeService {
  /**
   * The config factory reference.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The path alias manager reference.
   *
   * @var \Drupal\path_alias\AliasManagerInterface
   */
  protected $aliasManager;

  /**
   * HomePageNodeService constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory.
   * @param \Drupal\path_alias\AliasManagerInterface $alias_manager
   *   Path alias manager.
   */
  public function __construct(ConfigFactoryInterface $config_factory, AliasManagerInterface $alias_manager) {
    $this->configFactory = $config_factory;
    $this->aliasManager = $alias_manager;
  }

  /**
   * Get node which was set as a home page.
   *
   * @return \Drupal\node\Entity\Node|null
   *   Node object or NULL, if home page is not a node.
   */
  public function getHomePageNode() {
    $front_page_path = $this->configFactory->get('system.site')->get('page.front');
    $system_path = $this->aliasManager->getPathByAlias($front_page_path);
    if (preg_match('/^\/node\/(\d+)$/', $system_path, $matches)) {
      $nid = $matches[1];
      $node = Node::load($nid);

      if ($node instanceof Node) {
        return $node;
      }
    }

    return NULL;
  }

}
