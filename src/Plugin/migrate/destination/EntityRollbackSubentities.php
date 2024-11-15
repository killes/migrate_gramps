<?php

namespace Drupal\migrate_gramps\Plugin\migrate\destination;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate\Attribute\MigrateDestination;
use Drupal\migrate\Plugin\migrate\destination\EntityContentBase;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * We need rollback support for deleting Subsidiary entities.
 *
 */
#[MigrateDestination('entityrollbacksubentities:node')]
class EntityRollbackSubentities extends EntityContentBase {


  /**
   * {@inheritdoc}
   */
  public function rollback(array $destination_identifier) {
    $entity = $this->storage->load(reset($destination_identifier));

    // Delete attached entities on given fields.
    if ($entity) {
      var_dump($this->configuration['fields']);
      foreach ($this->configuration['fields'] as $config) {
        $sub_config = [];
        if (array_key_exists('subentities', $config) ) {
          $sub_config = $config['subentities'];
          unset($config['subentities']);
        }
        $fieldName = key($config);
        $dependend_entityType = $config[$fieldName];
        if ($entity->hasField($fieldName)) {
          $dependend_ids = array_column($entity->{$fieldName}->getValue(), 'target_id');;
          if ($dependend_ids) {
            $dependend_storage_handler = \Drupal::entityTypeManager()->getStorage($dependend_entityType);
            $dependend_entities = $dependend_storage_handler->loadMultiple($dependend_ids);
            foreach ($sub_config as $sub_dependend) {
              $fieldName = key($sub_dependend);
              $sub_entityType = $sub_dependend[$fieldName];
              $sub_dependend_storage_handler = \Drupal::entityTypeManager()->getStorage($sub_entityType);
              foreach ($dependend_entities as $dependend_entity) {
                $sub_ids = array_column($sub_dependend_entity->{$fieldName}->getValue(), 'target_id');;
                $sub_dependend_entities = $dependend_storage_handler->loadMultiple($sub_ids);
                $dependend_storage_handler->delete($sub_dependend_entities);
              }
            }
            # $storage_handler->delete($entities);
          }
        }
      }
    }

    // Execute the normal rollback from here.
    parent::rollback($destination_identifier);
  }
}
