<?php

namespace Drupal\glyanec_test\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\node\Entity\Node;
use Drupal\glyanec_test\Service\HomePageNodeService;

/**
 * Class subscriber for page access event.
 */
class HomePageAccessSubscriber implements EventSubscriberInterface {
  /**
   * The current user service reference.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */  protected $currentUser;

  /**
   * The route match service reference.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */ protected $routeMatch;

  /**
   * The home page node service reference.
   *
   * @var \Drupal\glyanec_test\Service\HomePageNodeService
   */ protected $homePageNode;

  /**
   * HomePageAccessSubscriber constructor.
   *
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   Current user.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   Route match.
   * @param \Drupal\glyanec_test\Service\HomePageNodeService $home_page_node
   *   Home Page Node Service.
   */
  public function __construct(AccountProxyInterface $current_user,
                              RouteMatchInterface $route_match,
                              HomePageNodeService $home_page_node
  ) {
    $this->currentUser = $current_user;
    $this->routeMatch = $route_match;
    $this->homePageNode = $home_page_node;
  }

  /**
   * Check access to home page.
   *
   * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
   *   Request event.
   */
  public function checkAccess(RequestEvent $event) {
    $route_name = $this->routeMatch->getRouteName();

    if ($route_name === 'entity.node.canonical' && $this->currentUser->isAnonymous()) {
      $node = $this->routeMatch->getParameter('node');

      if ($node instanceof Node) {
        $nid = $node->id();
        $nodeHome = $this->homePageNode->getHomePageNode();

        if ($nodeHome && $nodeHome->id() == $nid) {
          $response = new RedirectResponse('/user/login');
          $event->setResponse($response);
        }
      }
    }
  }

  /**
   * Get subscribed events.
   */
  public static function getSubscribedEvents() {
    return [
      KernelEvents::REQUEST => ['checkAccess', 30],
    ];
  }

}
