<?php

namespace Drupal\glyanec_test\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Create dump for specified entity.
 */
class EntityDumpService {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * EntityDumpService constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Create dump for specified entity.
   *
   * @param string $entity_type
   *   Entity type (like 'node', 'user').
   * @param int $entity_id
   *   Entity ID.
   *
   * @return array
   *   The entity dump.
   */
  public function getEntityDump(string $entity_type, int $entity_id) {
    $storage = $this->entityTypeManager->getStorage($entity_type);
    $entity = $storage->load($entity_id);

    if ($entity) {
      return $entity->toArray();
    }

    return [];
  }

}
