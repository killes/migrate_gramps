<?php

declare(strict_types=1);

namespace Drupal\migrate_gramps\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\migrate_gramps\GrampsEventReferenceInterface;

/**
 * Defines the gramps event reference entity class.
 *
 * @ContentEntityType(
 *   id = "gramps_event_reference",
 *   label = @Translation("Gramps Event Reference"),
 *   label_collection = @Translation("Gramps Event References"),
 *   label_singular = @Translation("gramps event reference"),
 *   label_plural = @Translation("gramps event references"),
 *   label_count = @PluralTranslation(
 *     singular = "@count gramps event references",
 *     plural = "@count gramps event references",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\migrate_gramps\GrampsEventReferenceListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   base_table = "gramps_event_reference",
 *   admin_permission = "administer gramps_event_reference",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/gramps-event-reference",
 *   },
 *   field_ui_base_route = "entity.gramps_event_reference.settings",
 * )
 */
final class GrampsEventReference extends ContentEntityBase implements GrampsEventReferenceInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    // All fields below match the name definition from grampsxml.dtd.
    // The Gramps "priv" field is reverse to the Drupal "status" field.
    $fields['priv'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Priv'))
      ->setDescription(t('The Priv field of the Event Reference entity.'))
      ->setReadOnly(TRUE);

    $fields['role'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Role'))
      ->setDescription(t('The Role field of the Event Reference entity.'))
      ->setReadOnly(TRUE);

    $fields['eventref'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Citations'))
      ->setDescription(t('The reference field of the Event Reference entity.'))
      ->setSettings([
        'target_type' => 'node',
        'target_bundles' => ['event'],
      ])
      ->setReadOnly(TRUE)
      ->setCardinality(1);

    $fields['attributes'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Attributes'))
      ->setDescription(t('The Notes reference field of the Event Reference entity.'))
      ->setSettings([
        'target_type' => 'gramps_attribute',
      ])
      ->setReadOnly(TRUE)
      ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED);

    $fields['notes'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Notes'))
      ->setDescription(t('The Notes reference field of the Event Reference entity.'))
      ->setSettings([
        'target_type' => 'node',
        'target_bundles' => ['note'],
      ])
      ->setReadOnly(TRUE)
      ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED);

    return $fields;
  }

}
