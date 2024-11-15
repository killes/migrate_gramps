<?php

declare(strict_types=1);

namespace Drupal\migrate_gramps\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Gramps Date type configuration entity.
 *
 * @ConfigEntityType(
 *   id = "gramps_date_type",
 *   label = @Translation("Gramps Date type"),
 *   label_collection = @Translation("Gramps Date types"),
 *   label_singular = @Translation("gramps date type"),
 *   label_plural = @Translation("gramps dates types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count gramps dates type",
 *     plural = "@count gramps dates types",
 *   ),
 *   handlers = {
 *     "form" = {
 *       "add" = "Drupal\migrate_gramps\Form\GrampsDateTypeForm",
 *       "edit" = "Drupal\migrate_gramps\Form\GrampsDateTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *     "list_builder" = "Drupal\migrate_gramps\GrampsDateTypeListBuilder",
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer gramps_date types",
 *   bundle_of = "gramps_date",
 *   config_prefix = "gramps_date_type",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/gramps_date_types/add",
 *     "edit-form" = "/admin/structure/gramps_date_types/manage/{gramps_date_type}",
 *     "delete-form" = "/admin/structure/gramps_date_types/manage/{gramps_date_type}/delete",
 *     "collection" = "/admin/structure/gramps_date_types",
 *   },
 *   config_export = {
 *     "id",
 *     "uuid",
 *   },
 * )
 */
final class GrampsDateType extends ConfigEntityBundleBase {

  /**
   * The machine name of this gramps date type.
   */
  protected string $id;

  /**
   * The human-readable name of the gramps date type.
   */
  protected string $label;

}
