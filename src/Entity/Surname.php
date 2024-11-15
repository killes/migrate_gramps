<?php

namespace Drupal\migrate_gramps\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Defines the Surname entity.
 *
 * @ingroup migrate_gramps
 *
 * @ContentEntityType(
 *   id = "surname",
 *   label = @Translation("Surname"),
 *   base_table = "surname",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *   },
 * )
 */
class Surname extends ContentEntityBase implements ContentEntityInterface {

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    // Standard field, used as unique if primary index.
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Surname entity.'))
      ->setReadOnly(TRUE);

    // Standard field, unique outside of the scope of the current project.
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Surname entity.'))
      ->setReadOnly(TRUE);

    // All fields below match the surname definition from grampsxml.dtd.

    $fields['value'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Value'))
      ->setDescription(t('The value field of the Surname entity.'))
      ->setReadOnly(TRUE);

    $fields['prefix'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Prefix'))
      ->setDescription(t('The Prefix field of the Surname entity.'))
      ->setReadOnly(TRUE);

    $fields['prim'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Prim'))
      ->setDescription(t('The Prim field of the Surname entity.'))
      ->setReadOnly(TRUE);

    $fields['derivation'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Derivation'))
      ->setDescription(t('The Derivation field of the Surname entity.'))
      ->setReadOnly(TRUE);

    $fields['connector'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Connector'))
      ->setDescription(t('The Connector field of the Surname entity.'))
      ->setReadOnly(TRUE);

    Return $fields;
  }
}
