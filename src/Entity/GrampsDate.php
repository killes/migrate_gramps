<?php

declare(strict_types=1);

namespace Drupal\migrate_gramps\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\BaseFieldDefinition;
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
 * )
 */
final class GrampsDate extends ContentEntityBase implements GrampsDateInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

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

    $fields['bundle'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Bundle'))
      ->setDescription(t('The bundle of the Gramps Date entity.'))
      ->setRequired(TRUE)
      ->setReadOnly(TRUE);

    return $fields;
  }

  public static function bundleFieldDefinitions(EntityTypeInterface $entity_type, $bundle, array $base_field_definitions) : array {
    if ($gramps_date_type = GrampsDateType::load($bundle)) {
      $fields = [];

      var_dump($gramps_date_type->id());
      switch ($gramps_date_type->id()) {
        case 'datestr':
          $fields['value'] = BundleFieldDefinition::create('string')
            ->setLabel(t('Value'))
            ->setSetting('max_length', 255)
            ->setDisplayOptions('form', [
              'type' => 'string_textfield',
              'weight' => -5,
            ])
            ->setDisplayOptions('view', [
              'label' => 'hidden',
              'type' => 'string',
              'weight' => -5,
            ])
            ->setDisplayConfigurable('view', TRUE);
        case 'dateval':
        case 'daterange':
        case 'datespan':
        default:
          return [];
      }
      var_dump($gramps_date_type->id());
      return $fields;
    }
  }

}
