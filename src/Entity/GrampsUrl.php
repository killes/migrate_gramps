<?php

declare(strict_types=1);

namespace Drupal\migrate_gramps\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\migrate_gramps\GrampsUrlInterface;

/**
 * Defines the gramps url entity class.
 *
 * @ContentEntityType(
 *   id = "gramps_url",
 *   label = @Translation("Gramps Url"),
 *   label_collection = @Translation("Gramps Urls"),
 *   label_singular = @Translation("gramps url"),
 *   label_plural = @Translation("gramps urls"),
 *   label_count = @PluralTranslation(
 *     singular = "@count gramps urls",
 *     plural = "@count gramps urls",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\migrate_gramps\GrampsUrlListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   base_table = "gramps_url",
 *   admin_permission = "administer gramps_url",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/gramps-url",
 *   },
 *   field_ui_base_route = "entity.gramps_url.settings",
 * )
 */
final class GrampsUrl extends ContentEntityBase implements GrampsUrlInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    // All fields below match the name definition from grampsxml.dtd.
    // The Gramps "priv" field is reverse to the Drupal "status" field.
    $fields['priv'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Priv'))
      ->setDescription(t('The Priv field of the Gramps Url entity.'))
      ->setReadOnly(TRUE);

    $fields['type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Type'))
      ->setDescription(t('The type field of the Gramps Url entity.'))
      ->setReadOnly(TRUE);

    $fields['href'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Href'))
      ->setDescription(t('The href field of the Gramps Url entity.'))
      ->setReadOnly(TRUE);

    $fields['description'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Description'))
      ->setDescription(t('The description field of the Gramps Url entity.'))
      ->setReadOnly(TRUE);

    return $fields;
  }

}
