<?php

declare(strict_types = 1);

namespace Drupal\migrate_gramps\Plugin\migrate\process;

use Drupal\migrate\MigrateException;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * This plugin merges arrays together.
 *
 * @MigrateProcessPlugin(
 *   id = "mergedate"
 * )
 *
 * Use to transform an array. Only the non-null values are considered.  For a
 * configured set "stringify" of bundles and the current bundle "bundle", if the
 * resulting array only has one element, only that element's value is returned.
 *
 */
class MergeDate extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property): array {
    $stringify = (array) $this->configuration['stringify'];
    $bundle = (string) $this->configuration['bundle'];
    if (!is_array($value)) {
      throw new MigrateException(sprintf('Merge Set Values process failed for destination property (%s): input is not an array.', $destination_property));
    }
    $new_value = [];
    foreach ($value as $i => $item) {
      if (!is_null($item)) {
        $new_value[] = $item;
      }
    }
    if (in_array($bundle, $stringify) && count($new_value) == 1) {
      return reset($new_value);
    }
    return $new_value;
  }

}
