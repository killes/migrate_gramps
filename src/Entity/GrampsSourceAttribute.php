<?php

declare(strict_types=1);

namespace Drupal\migrate_gramps\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\migrate_gramps\GrampsSourceAttributeInterface;

/**
 * Defines the gramps source attribute entity class.
 *
 * @ContentEntityType(
 *   id = "gramps_source_attribute",
 *   label = @Translation("Gramps Source Attribute"),
 *   label_collection = @Translation("Gramps Source Attributes"),
 *   label_singular = @Translation("gramps source attribute"),
 *   label_plural = @Translation("gramps source attributes"),
 *   label_count = @PluralTranslation(
 *     singular = "@count gramps source attributes",
 *     plural = "@count gramps source attributes",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\migrate_gramps\GrampsSourceAttributeListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   base_table = "gramps_source_attribute",
 *   admin_permission = "administer gramps_source_attribute",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/gramps-source-attribute",
 *   },
 *   field_ui_base_route = "entity.gramps_source_attribute.settings",
 * )
 */
final class GrampsSourceAttribute extends ContentEntityBase implements GrampsSourceAttributeInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    // All fields below match the name definition from grampsxml.dtd.
    // The Gramps "priv" field is reverse to the Drupal "status" field.
    $fields['priv'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Priv'))
      ->setDescription(t('The Priv field of the Source Attribute entity.'))
      ->setReadOnly(TRUE);

    $fields['type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Type'))
      ->setDescription(t('The type field of the Source Attribute entity.'))
      ->setReadOnly(TRUE);

    $fields['value'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Value'))
      ->setDescription(t('The value field of the Source Attribute entity.'))
      ->setReadOnly(TRUE);

    return $fields;
  }
}
