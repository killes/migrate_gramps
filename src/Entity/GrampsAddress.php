<?php

declare(strict_types=1);

namespace Drupal\migrate_gramps\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\migrate_gramps\GrampsAddressInterface;

/**
 * Defines the gramps address entity class.
 *
 * @ContentEntityType(
 *   id = "gramps_address",
 *   label = @Translation("Gramps Address"),
 *   label_collection = @Translation("Gramps Addresses"),
 *   label_singular = @Translation("gramps address"),
 *   label_plural = @Translation("gramps addresses"),
 *   label_count = @PluralTranslation(
 *     singular = "@count gramps addresses",
 *     plural = "@count gramps addresses",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\migrate_gramps\GrampsAddressListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   base_table = "gramps_address",
 *   admin_permission = "administer gramps_address",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/gramps-address",
 *   },
 *   field_ui_base_route = "entity.gramps_address.settings",
 * )
 */
final class GrampsAddress extends ContentEntityBase implements GrampsAddressInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    // All fields below match the name definition from grampsxml.dtd.

    // The Gramps "priv" field is reverse to the Drupal "status" field.
    $fields['priv'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Priv'))
      ->setDescription(t('The Priv field of the Name entity.'))
      ->setReadOnly(TRUE);

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

    $fields['street'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Street'))
      ->setDescription(t('The street field of the Name entity.'))
      ->setReadOnly(TRUE);

    $fields['locality'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Locality'))
      ->setDescription(t('The locality field of the Name entity.'))
      ->setReadOnly(TRUE);

    $fields['city'] = BaseFieldDefinition::create('string')
      ->setLabel(t('City'))
      ->setDescription(t('The city field of the Name entity.'))
      ->setReadOnly(TRUE);

    $fields['county'] = BaseFieldDefinition::create('string')
      ->setLabel(t('County'))
      ->setDescription(t('The county field of the Name entity.'))
      ->setReadOnly(TRUE);

    $fields['state'] = BaseFieldDefinition::create('string')
      ->setLabel(t('State'))
      ->setDescription(t('The state field of the Name entity.'))
      ->setReadOnly(TRUE);

    $fields['country'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Country'))
      ->setDescription(t('The country field of the Name entity.'))
      ->setReadOnly(TRUE);

    $fields['postal'] = BaseFieldDefinition::create('string')
      ->setLabel(t(''))
      ->setDescription(t('The postal field of the Name entity.'))
      ->setReadOnly(TRUE);

    $fields['phone'] = BaseFieldDefinition::create('string')
      ->setLabel(t(''))
      ->setDescription(t('The phone field of the Name entity.'))
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
