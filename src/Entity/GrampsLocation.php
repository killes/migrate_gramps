<?php

declare(strict_types=1);

namespace Drupal\migrate_gramps\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\migrate_gramps\GrampsLocationInterface;

/**
 * Defines the gramps location entity class.
 *
 * @ContentEntityType(
 *   id = "gramps_location",
 *   label = @Translation("Gramps Location"),
 *   label_collection = @Translation("Gramps Locations"),
 *   label_singular = @Translation("gramps location"),
 *   label_plural = @Translation("gramps locations"),
 *   label_count = @PluralTranslation(
 *     singular = "@count gramps locations",
 *     plural = "@count gramps locations",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\migrate_gramps\GrampsLocationListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   base_table = "gramps_location",
 *   admin_permission = "administer gramps_location",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/gramps-location",
 *   },
 *   field_ui_base_route = "entity.gramps_location.settings",
 * )
 */
final class GrampsLocation extends ContentEntityBase implements GrampsLocationInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['description'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Description'))
      ->setDisplayOptions('form', [
        'type' => 'text_textarea',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'text_default',
        'label' => 'above',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
