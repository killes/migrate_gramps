<?php

declare(strict_types=1);

namespace Drupal\migrate_gramps;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Provides a list controller for the gramps date entity type.
 */
final class GrampsDateListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader(): array {
    $header['id'] = $this->t('ID');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    /** @var \Drupal\migrate_gramps\GrampsDateInterface $entity */
    $row['id'] = $entity->id();
    return $row + parent::buildRow($entity);
  }

}
