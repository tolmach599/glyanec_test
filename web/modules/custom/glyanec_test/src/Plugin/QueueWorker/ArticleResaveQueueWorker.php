<?php

namespace Drupal\glyanec_test\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\node\Entity\Node;

/**
 * Resaves article nodes.
 *
 * @QueueWorker(
 *   id = "article_resave_queue",
 *   title = @Translation("Article Resave Queue Worker"),
 *   cron = {"time" = 60}
 * )
 */
class ArticleResaveQueueWorker extends QueueWorkerBase {

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    if (!empty($data['nid'])) {
      $node = Node::load($data['nid']);
      if ($node && $node->bundle() === 'article') {
        // Disable creating a new revision.
        $node->setNewRevision(FALSE);
        $node->save();
      }
    }
  }

}
