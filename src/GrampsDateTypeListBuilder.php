<?php

declare(strict_types=1);

namespace Drupal\migrate_gramps;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of gramps date type entities.
 *
 * @see \Drupal\migrate_gramps\Entity\GrampsDateType
 */
final class GrampsDateTypeListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader(): array {
    $header['label'] = $this->t('Label');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    $row['label'] = $entity->label();
    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function render(): array {
    $build = parent::render();

    $build['table']['#empty'] = $this->t(
      'No gramps date types available. <a href=":link">Add gramps date type</a>.',
      [':link' => Url::fromRoute('entity.gramps_date_type.add_form')->toString()],
    );

    return $build;
  }

}