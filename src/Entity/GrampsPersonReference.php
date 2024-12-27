<?php

declare(strict_types=1);

namespace Drupal\migrate_gramps\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\migrate_gramps\GrampsPersonReferenceInterface;

/**
 * Defines the gramps person reference entity class.
 *
 * @ContentEntityType(
 *   id = "gramps_person_reference",
 *   label = @Translation("Gramps Person Reference"),
 *   label_collection = @Translation("Gramps Person References"),
 *   label_singular = @Translation("gramps person reference"),
 *   label_plural = @Translation("gramps person references"),
 *   label_count = @PluralTranslation(
 *     singular = "@count gramps person references",
 *     plural = "@count gramps person references",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\migrate_gramps\GrampsPersonReferenceListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   base_table = "gramps_person_reference",
 *   admin_permission = "administer gramps_person_reference",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/gramps-person-reference",
 *   },
 *   field_ui_base_route = "entity.gramps_person_reference.settings",
 * )
 */
final class GrampsPersonReference extends ContentEntityBase implements GrampsPersonReferenceInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['person'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Person'))
      ->setDescription(t('The Person reference field of the Person Reference entity.'))
      ->setSettings([
        'target_type' => 'node',
        'target_bundles' => ['person'],
      ])
      ->setReadOnly(TRUE)
      ->setCardinality(1);

    $fields['reference'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Citations'))
      ->setDescription(t('The Referenec field of the Person Reference entity.'))
      ->setSettings([
        'target_type' => 'node',
        'target_bundles' => ['person'],
      ])
      ->setReadOnly(TRUE)
      ->setCardinality(1);

    $fields['rel'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Relationship'))
      ->setSetting('max_length', 255)
      ->setReadOnly(TRUE);

    $fields['priv'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Priv'))
      ->setDescription(t('The Priv field of the Person Reference entity.'))
      ->setReadOnly(TRUE);

    $fields['citations'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Citations'))
      ->setDescription(t('The Citations reference field of the Person Reference entity.'))
      ->setSettings([
        'target_type' => 'node',
        'target_bundles' => ['citation'],
      ])
      ->setReadOnly(TRUE)
      ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED);

    $fields['notes'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Citations'))
      ->setDescription(t('The Notes reference field of the Person Reference entity.'))
      ->setSettings([
        'target_type' => 'node',
        'target_bundles' => ['note'],
      ])
      ->setReadOnly(TRUE)
      ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED);

    return $fields;
  }

}
