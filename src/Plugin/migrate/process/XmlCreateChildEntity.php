<?php

namespace Drupal\migrate_gramps\Plugin\migrate\process;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This plugin generates entities from XML that only exist in context of their
 * parent.
 *
 * @MigrateProcessPlugin(
 *   id = "xml_create_child_entity",
 * )
 *
 * Based on Migrate Child Entity Generate
 *  https://www.drupal.org/project/migrate_child_entity_generate
 *
 * Example usage:
 * @code
 * destination:
 *   plugin: 'entity:node'
 * source:
 *   # assuming we're using a source that will deliver several XML matches:
 *   fields:
 *     -
 *     - name: src_names
 *       label: 'Names'
 *       selector: ns:name
 * process:
 *   field_names:
 *     plugin: xml_create_child_entity
 *     source: src_names
 *     entity_type: name
 *     values:
 *       first: ns:first
 *       call: ns:call
 * @endcode
 *
 */
class XmlCreateChildEntity extends ProcessPluginBase implements ContainerFactoryPluginInterface {
  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration = NULL) {
    $instance = new static($configuration, $plugin_id, $plugin_definition);
    $instance->entityTypeManager = $container->get('entity_type.manager');

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $definition = $this->entityTypeManager
      ->getDefinition($this->configuration['entity_type']);
    $storage = $this->entityTypeManager
      ->getStorage($this->configuration['entity_type']);

    $values = [];
    if (isset($this->configuration['bundle']) && $bundle = $this->configuration['bundle']) {
      $values[$definition->getKey('bundle')] = $bundle;
    }

    foreach ($this->configuration['values'] ?? [] as $fieldName => $xpath) {
      $this->registerNamespaces($value);
      $fieldValue = NULL;
      foreach ($value->xpath($xpath) as $match) {
        if ($match->children() && !trim((string) $match)) {
          $fieldValue = $match;
        }
        else {
          $fieldValue = (string) $match;
        }
      }

      NestedArray::setValue($values, explode(Row::PROPERTY_SEPARATOR, $fieldName), $fieldValue, TRUE);
    }
    foreach ($this->configuration['default_values'] ?? [] as $fieldName => $fieldValue) {
      NestedArray::setValue($values, explode(Row::PROPERTY_SEPARATOR, $fieldName), $fieldValue, TRUE);
    }

    $entity = $storage->create($values);
    $entity->save();

    return $entity;
  }

  /**
   * Registers the iterator's namespaces to a SimpleXMLElement.
   *
   * @param \SimpleXMLElement $xml
   *   The element to apply namespace registrations to.
   */
  protected function registerNamespaces(\SimpleXMLElement $xml): void {
    if (isset($this->configuration['namespaces']) && is_array($this->configuration['namespaces'])) {
      foreach ($this->configuration['namespaces'] as $prefix => $ns) {
        $xml->registerXPathNamespace($prefix, $ns);
      }
    }
  }
}
