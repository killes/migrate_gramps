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
 *   id = "textstyles"
 * )
 *
 * Use to transform an Gramps text styles to HTML.
 */
class TextStyles extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property): string {
    $text = $value[0];
    $format = $value[1];
    $format_instructions = $value[2];

    // We process the text according the formatting instructions and return
    // HTML.
    if (!is_null($format_instructions) && count($format_instructions)) {
      // If we only have one instruction wrap it in an array, too.
      if (count($format_instructions) == 1) {
        $format_instructions = [$format_instructions];
      }
      /*
        Formatting instructions consist of a name, an optional value and one or
        more start and end values indicating the ranges of characters they apply
        to. The problem is that these ranges may overlap for different
        formatters (text colour and size for example). It is possible, that
        invalid HTML is returned.
      */
      $elements = [];
      foreach ($format_instructions as $instruction) {
        $name = $instruction->attributes()['name']->__toString();
        $format_value = NULL;
        if ($instruction->attributes()['value']) {
          $format_value = $instruction->attributes()['value']->__toString();
        }
        foreach ($instruction->children() as $child_name => $data) {
          if ($child_name == 'range') {
            $start = $data->attributes()['start']->__toString();
            $end = $data->attributes()['end']->__toString();
            $elements[] = [
              'start' => $start,
              'end' => $end,
              'name' => $name,
              'value' => $format_value,
            ];
          }
        }
      }
      // We get the sorted start and end positions.
      $starts  = array_column($elements, 'start');
      $ends  = array_column($elements, 'end');
      asort($starts, SORT_NUMERIC);
      asort($ends, SORT_NUMERIC);
      $starts  = array_unique($starts);
      $ends  = array_unique($ends);

      // Loop over start and end positions and get the correctly formatted
      // element.
      $additions = [];
      foreach ($starts as $start) {
        if (!isset($additions[$start])) {
          $additions[$start] = [];
        }
        $additions[$start]['start'] = [];
        foreach ($elements as $element) {
          if ($element['start'] == $start) {
            $additions[$start]['start'][] = $this->_get_start_element($element);
          }
        }
      }
      foreach ($ends as $end) {
        if (!isset($additions[$end])) {
          $additions[$end] = [];
        }
        $additions[$end]['end'] = [];
        foreach ($elements as $element) {
          if ($element['end'] == $end) {
            $additions[$end]['end'][] = $this->_get_end_element($element);
          }
        }
      }
      ksort($additions, SORT_NUMERIC);

      // Split the text into an array, one char each element.
      $text_array = mb_str_split($text, 1, 'UTF-8');

      // Add formatted html at the correct positions, keep count of inserts.
      $added = 0;
      foreach ($additions as $idx => $addition) {
        if (isset($addition['end'])) {
          foreach ($addition['end'] as $end) {
            array_splice($text_array, $idx + $added, 0, $end);
            $added++;
          }
        }
        if (isset($addition['start'])) {
          foreach ($addition['start'] as $start) {
            array_splice($text_array, $idx + $added, 0, $start);
            $added++;
          }
        }
      }

      $processed_text = implode('', $text_array);

      // @TODO: Add some validation.

      $text = $processed_text;
    }

    // "format" means to preserve whitespace.
    if ($format) {
      $text = '<div style="white-space: pre;">' . $text . '</div>';
    }

    // We return the text.
    return $text;
  }

  /**
   * Helper function, return start of HTML tags.
   *
   * Note: For these, except the links, to show up on a Drupal site, you will
   * need something like https://www.drupal.org/project/extended_html_filter
   *
   * @TODO: We could convert some of those into html5 elements, but the exact
   * sizes and colors would be lost. We could also add external CSS.
   */
  function _get_start_element($element) {
    switch ($element['name']) {
      case 'link':
        $parts = explode(':', $element['value']);
        switch ($parts[0]) {
          case 'gramps':
            return '<a href="/' . substr($parts[1], 2) . '">';
          case 'http':
          case 'https':
            return '<a href="' . $element['value'] . '">';
          case 'relative':
            return '<a href="file:/' . $parts[1] . '">';
        }
      case 'bold':
        return '<span style="font-weight: bold;">';
      case 'italic':
        return '<span style="font-style: italic;">';
      case 'underline':
        return '<span style="text-decoration-line: underline;">';
      case 'fontface':
        return '<span style="font-family: ' . $element['value'] . ';">';
      case 'fontsize':
        return '<span style="font-size: ' . $element['value'] . ';">';
      case 'fontcolor':
        return '<span style="color: ' . $element['value'] . ';">';
      case 'highlight':
        return '<span style="background-color: ' . $element['value'] . ';">';
      case 'superscript':
        return '<span style="vertical-align: super; font-size: smaller;">';
      default:
        return '<span>';
    }
  }

  /**
   * Helper function, return end of HTML tags.
   */
  function _get_end_element($element) {
    switch ($element['name']) {
      case 'link':
        return '</a>';
      default:
        return '</span>';
    }
  }
}
