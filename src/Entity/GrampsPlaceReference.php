<?php

declare(strict_types=1);

namespace Drupal\migrate_gramps\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\migrate_gramps\GrampsPlaceReferenceInterface;

/**
 * Defines the gramps place reference entity class.
 *
 * @ContentEntityType(
 *   id = "gramps_place_reference",
 *   label = @Translation("Gramps Place Reference"),
 *   label_collection = @Translation("Gramps Place References"),
 *   label_singular = @Translation("gramps place reference"),
 *   label_plural = @Translation("gramps place references"),
 *   label_count = @PluralTranslation(
 *     singular = "@count gramps place references",
 *     plural = "@count gramps place references",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\migrate_gramps\GrampsPlaceReferenceListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   base_table = "gramps_place_reference",
 *   admin_permission = "administer gramps_place_reference",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/gramps-place-reference",
 *   },
 *   field_ui_base_route = "entity.gramps_place_reference.settings",
 * )
 */
final class GrampsPlaceReference extends ContentEntityBase implements GrampsPlaceReferenceInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['date'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Date'))
      ->setDescription(t('The Date field of the Name entity.'))
      ->setSettings([
        'target_type' => 'gramps_date',
      ])
      ->setSetting('handler_settings', [
        'target_bundles' => [
          'gramps_date_datestr' => 'gramps_date_datestr',
          'gramps_date_dateval' => 'gramps_date_dateval',
          'gramps_date_daterange' => 'gramps_date_daterange',
          'gramps_date_datespan' => 'gramps_date_datespan',
        ]
      ])
      ->setReadOnly(TRUE);

    return $fields;
  }

}
