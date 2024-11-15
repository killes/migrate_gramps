<?php

namespace Drupal\migrate_gramps\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Defines the Name entity.
 *
 * @ingroup migrate_gramps
 *
 * @ContentEntityType(
 *   id = "name",
 *   label = @Translation("Name"),
 *   base_table = "name",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *   },
 * )
 */
class Name extends ContentEntityBase implements ContentEntityInterface {

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    // Standard field, used as unique if primary index.
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Name entity.'))
      ->setReadOnly(TRUE);

    // Standard field, unique outside of the scope of the current project.
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Name entity.'))
      ->setReadOnly(TRUE);

    // All fields below match the name definition from grampsxml.dtd.

    // First, the attributes.
    $fields['alt'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Alt'))
      ->setDescription(t('The Alt field of the Name entity.'))
      ->setReadOnly(TRUE);

    $fields['type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Type'))
      ->setDescription(t('The Type field of the Name entity.'))
      ->setReadOnly(TRUE);

    $fields['priv'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Alt'))
      ->setDescription(t('The Priv field of the Name entity.'))
      ->setReadOnly(TRUE);

    $fields['sort'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Sort'))
      ->setDescription(t('The Sort field of the Name entity.'))
      ->setReadOnly(TRUE);

    $fields['display'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Display'))
      ->setDescription(t('The Display field of the Name entity.'))
      ->setReadOnly(TRUE);

    // The other fields.
    $fields['first'] = BaseFieldDefinition::create('string')
      ->setLabel(t('First'))
      ->setDescription(t('The First field of the Name entity.'))
      ->setReadOnly(TRUE);

    $fields['call'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Call'))
      ->setDescription(t('The Call field of the Name entity.'))
      ->setReadOnly(TRUE);

    $fields['suffix'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Suffix'))
      ->setDescription(t('The Suffix field of the Name entity.'))
      ->setReadOnly(TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDescription(t('The Title field of the Name entity.'))
      ->setReadOnly(TRUE);

    $fields['nick'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Nick'))
      ->setDescription(t('The Nick field of the Name entity.'))
      ->setReadOnly(TRUE);

    $fields['familynick'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Family nicl'))
      ->setDescription(t('The Familynick field of the Name entity.'))
      ->setReadOnly(TRUE);

    $fields['group'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Group'))
      ->setDescription(t('The Group field of the Name entity.'))
      ->setReadOnly(TRUE);

    $fields['date'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Date'))
      ->setDescription(t('The Date field of the Name entity.'))
      ->setSettings([
        'target_type' => 'gramps_date',
      ])
      ->setReadOnly(TRUE);

    $fields['surname'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Surname'))
      ->setDescription(t('The Surname field of the Name entity.'))
      ->setSettings([
        'target_type' => 'surname',
      ])
      ->setReadOnly(TRUE)
      ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED);

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
      ->setLabel(t('Citations'))
      ->setDescription(t('The Notes reference field of the Name entity.'))
      ->setSettings([
        'target_type' => 'node',
        'target_bundles' => ['note'],
      ])
      ->setReadOnly(TRUE)
      ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED);

    Return $fields;
  }
}
