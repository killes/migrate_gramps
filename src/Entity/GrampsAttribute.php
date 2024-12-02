<?php

declare(strict_types=1);

namespace Drupal\migrate_gramps\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\migrate_gramps\GrampsAttributeInterface;

/**
 * Defines the gramps attribute entity class.
 *
 * @ContentEntityType(
 *   id = "gramps_attribute",
 *   label = @Translation("Gramps Attribute"),
 *   label_collection = @Translation("Gramps Attributes"),
 *   label_singular = @Translation("gramps attribute"),
 *   label_plural = @Translation("gramps attributes"),
 *   label_count = @PluralTranslation(
 *     singular = "@count gramps attributes",
 *     plural = "@count gramps attributes",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\migrate_gramps\GrampsAttributeListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   base_table = "gramps_attribute",
 *   admin_permission = "administer gramps_attribute",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/gramps-attribute",
 *   },
 *   field_ui_base_route = "entity.gramps_attribute.settings",
 * )
 */
final class GrampsAttribute extends ContentEntityBase implements GrampsAttributeInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    // All fields below match the name definition from grampsxml.dtd.
    // The Gramps "priv" field is reverse to the Drupal "status" field.
    $fields['priv'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Priv'))
      ->setDescription(t('The Priv field of the Attribute entity.'))
      ->setReadOnly(TRUE);

    $fields['type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Type'))
      ->setDescription(t('The type field of the Attribute entity.'))
      ->setReadOnly(TRUE);

    $fields['value'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Value'))
      ->setDescription(t('The value field of the Attribute entity.'))
      ->setReadOnly(TRUE);

    $fields['citations'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Citations'))
      ->setDescription(t('The Citations reference field of the Name entity.'))
      ->setSettings([
        'target_type' => 'node',
        'target_bundles' => ['citation'],
      ])
      ->setReadOnly(TRUE)
      ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED);

    $fields['notes'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Notes'))
      ->setDescription(t('The Notes reference field of the Name entity.'))
      ->setSettings([
        'target_type' => 'node',
        'target_bundles' => ['note'],
      ])
      ->setReadOnly(TRUE)
      ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED);

    return $fields;
  }
}
