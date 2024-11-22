<?php

declare(strict_types=1);

namespace Drupal\migrate_gramps\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\migrate_gramps\BundleFieldDefinition;
use Drupal\migrate_gramps\GrampsDateInterface;

/**
 * Defines the gramps date entity class.
 *
 * @ContentEntityType(
 *   id = "gramps_date",
 *   label = @Translation("Gramps Date"),
 *   label_collection = @Translation("Gramps Dates"),
 *   label_singular = @Translation("gramps date"),
 *   label_plural = @Translation("gramps dates"),
 *   label_count = @PluralTranslation(
 *     singular = "@count gramps dates",
 *     plural = "@count gramps dates",
 *   ),
 *   bundle_label = @Translation("Gramps Date type"),
 *   handlers = {
 *     "list_builder" = "Drupal\migrate_gramps\GrampsDateListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\migrate_gramps\Form\GrampsDateForm",
 *       "edit" = "Drupal\migrate_gramps\Form\GrampsDateForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "delete-multiple-confirm" = "Drupal\Core\Entity\Form\DeleteMultipleForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\migrate_gramps\Routing\GrampsDateHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "gramps_date",
 *   admin_permission = "administer gramps_date types",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "bundle",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/gramps-date",
 *     "add-form" = "/gramps-date/add/{gramps_date_type}",
 *     "add-page" = "/gramps-date/add",
 *     "canonical" = "/gramps-date/{gramps_date}",
 *     "edit-form" = "/gramps-date/{gramps_date}",
 *     "delete-form" = "/gramps-date/{gramps_date}/delete",
 *     "delete-multiple-form" = "/admin/content/gramps-date/delete-multiple",
 *   },
 *   bundle_entity_type = "gramps_date_type",
 *   field_ui_base_route = "entity.gramps_date_type.edit_form",
 * )
 */
class GrampsDate extends ContentEntityBase implements GrampsDateInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['label'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Label'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public static function bundleFieldDefinitions(EntityTypeInterface $entity_type, $bundle, array $base_field_definitions) {
    var_dump($bundle);
    $fields = [];

    if ($bundle == 'datestr') {
      $fields['datestr'] = BundleFieldDefinition::create('string')
        ->setTargetEntityTypeId($entity_type->id())
        ->setTargetBundle($bundle)
        ->setLabel(t('Value'))
        ->setDescription(t('The value of the Gramps Date entity of bundle datestr.'))
        ->setSetting('max_length', 255)
        ->setDisplayOptions('form', [
          'type' => 'string_textfield',
          'weight' => -5,
        ])
        ->setDisplayConfigurable('form', TRUE);
    }
    if ($bundle == 'dateval') {
      # This should be a Date field, however neither Drupal nor SQL deal well
      # with Gramps' idea of a date.
      $fields['dateval'] = BundleFieldDefinition::create('string')
        ->setTargetEntityTypeId($entity_type->id())
        ->setTargetBundle($bundle)
        ->setLabel(t('Value'))
        ->setDescription(t('The value of the Gramps Date entity of bundle dateval.'))
        ->setSetting('max_length', 255)
        ->setDisplayOptions('form', [
          'type' => 'string_textfield',
          'weight' => -5,
        ])
        ->setDisplayConfigurable('form', TRUE);
      $fields['type'] = BundleFieldDefinition::create('string')
        ->setTargetEntityTypeId($entity_type->id())
        ->setTargetBundle($bundle)
        ->setLabel(t('Type'))
        ->setDescription(t('The type of the Gramps Date entity.'));
    }
    if ($bundle == 'daterange' || $bundle == 'datespan') {
      # These should be Date fields, however neither Drupal nor SQL deal well
      # with Gramps' idea of a date.
      $fields['start'] = BundleFieldDefinition::create('string')
        ->setTargetEntityTypeId($entity_type->id())
        ->setTargetBundle($bundle)
        ->setLabel(t('Value'))
        ->setDescription(t('The stsrt date of the Gramps Date entity.'))
        ->setSetting('max_length', 255)
        ->setDisplayOptions('form', [
          'type' => 'string_textfield',
          'weight' => -5,
        ])
        ->setDisplayConfigurable('form', TRUE);
      $fields['stop'] = BundleFieldDefinition::create('string')
        ->setTargetEntityTypeId($entity_type->id())
        ->setTargetBundle($bundle)
        ->setLabel(t('Value'))
        ->setDescription(t('The end date of the Gramps Date entity.'))
        ->setSetting('max_length', 255)
        ->setDisplayOptions('form', [
          'type' => 'string_textfield',
          'weight' => -5,
        ])
        ->setDisplayConfigurable('form', TRUE);
    }
    if (in_array($bundle, ['dateval', 'daterange', 'datespan'])) {
      $fields['cformat'] = BundleFieldDefinition::create('string')
        ->setTargetEntityTypeId($entity_type->id())
        ->setTargetBundle($bundle)
        ->setLabel(t('Calendar format'))
        ->setDescription(t('The cformat of the Gramps Date entity.'))
        ->setReadOnly(TRUE);
      $fields['quality'] = BundleFieldDefinition::create('string')
        ->setTargetEntityTypeId($entity_type->id())
        ->setTargetBundle($bundle)
        ->setLabel(t('Quality'))
        ->setDescription(t('The quality of the Gramps Date entity.'))
        ->setReadOnly(TRUE);
      $fields['dualdated'] = BundleFieldDefinition::create('integer')
        ->setTargetEntityTypeId($entity_type->id())
        ->setTargetBundle($bundle)
        ->setLabel(t('Dual dated'))
        ->setDescription(t('Whether the Gramps Date entity is dual dated.'))
        ->setReadOnly(TRUE);
      $fields['newyear'] = BundleFieldDefinition::create('string')
        ->setTargetEntityTypeId($entity_type->id())
        ->setTargetBundle($bundle)
        ->setLabel(t('New year'))
        ->setDescription(t('When the new year starts for the Gramps Date entity.'))
        ->setReadOnly(TRUE);
    }

    return $fields;
  }
}
