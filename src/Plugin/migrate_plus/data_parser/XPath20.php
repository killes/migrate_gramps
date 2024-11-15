<?php

declare(strict_types = 1);

namespace Drupal\migrate_gramps\Plugin\migrate_plus\data_parser;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\migrate\MigrateException;
use Drupal\migrate_plus\DataParserPluginBase;

use lyquidity\xml\MS\XmlNamespaceManager;
use lyquidity\XPath2\XPath2Expression;
use lyquidity\xml\schema\SchemaTypes;
use lyquidity\XPath2\DOM\DOMXPathNavigator;
use lyquidity\xml\xpath\XPathNodeType;
use lyquidity\XPath2\NodeProvider;
use lyquidity\XPath2\XPath2Exception;
use lyquidity\XPath2\lyquidity\Log;
use lyquidity\XPath2\FunctionTable;
use lyquidity\XPath2\Iterator\DocumentOrderNodeIterator;
use lyquidity\XPath2\XPath2ResultType;
use lyquidity\xml\xpath\XPathNavigator;

/**
 * Obtain XML data for migration using https://github.com/bseddon/XPath20.
 *
 * Install using composer.
 *
 *
 * @DataParser(
 *   id = "xpath20",
 *   title = @Translation("Xpath 2.0 for XML processing")
 * )
 */
class Xpath20 extends DataParserPluginBase {

  /**
   * DOM of the XML file.
   *
   * @var \DOMDocument|bool
   */
  protected $dom = FALSE;

  protected $nsMgr = FALSE;

  /**
   * Iterator of matches from XPath 2.0 expression.
   *
   * @var lyquidity\XPath2\Iterator\ExprIterator|bool
   */
  protected $iterator = FALSE;


  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    // Suppress errors during parsing, so we can pick them up after.
    libxml_use_internal_errors(TRUE);
  }

  /**
   * {@inheritdoc}
   */
  protected function openSourceUrl($url): bool {
    // Clear XML error buffer. Other Drupal code that executed during the
    // migration may have polluted the error buffer and could create false
    // positives in our error check below. We are only concerned with errors
    // that occur from attempting to load the XML string into an object here.
    libxml_clear_errors();

    $xml_data = $this->getDataFetcherPlugin()->getResponseContent($url);
    $this->dom = new \DOMDocument();
    $this->dom->loadXML($xml_data);
    foreach (libxml_get_errors() as $error) {
      $error_string = self::parseLibXmlError($error);
      throw new MigrateException($error_string);
    }

    // Create a navigator and move to the appropriate node (usually the first
    // child of the document.
    $nav = new DOMXPathNavigator($this->dom);
    $nav->MoveToRoot();
    $nav->MoveToChild(XPathNodeType::Element);

    // Create a node provider so the expression can be evaluated against the
    // document nodes.
    $provider = new NodeProvider($nav);

    // Create a namespace manager to be used by the expression evaluator
    $this->nsMgr = new XmlNamespaceManager();
    // Load the namespaces from the document. $node is a DOMNameSpaceNode so
    // extract the prefix from the node name
    $xpath = new \DOMXPath($this->dom);
    foreach($xpath->query('namespace::*', $this->dom->documentElement) as $node) {
      $this->nsMgr->addNamespace(\str_replace(['xmlns', ':'], ['', ''], $node->nodeName), (string)$node->nodeValue );
    }

    // Add more namespaces if desired.
    $namespaces = $this->configuration['namespaces'];
    foreach ($namespaces as $key => $value) {
      $this->nsMgr->addNamespace($key, $value);
    }

    // Compile an expression to retrieve a list of the context elements. This
    // can be any valid XPath statement
    $query = $this->configuration['item_selector'];
    $expression = XPath2Expression::Compile($query, $this->nsMgr);

    // If the query requires variables then they will appear in an indexed array
    $vars = array();
    // Evaluate the expression
    $result = $expression->EvaluateWithVars($provider, $vars);
    $this->iterator = $result;
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  protected function fetchNextRow(): void {
    if (!$this->iterator->MoveNext()) {
      return;
    };
#    var_dump($this->iterator->getCurrentPosition());
    $target_element = $this->iterator->GetCurrent();

    // If we've found the desired element, populate the currentItem and
    // currentId with its data.
    if ($target_element !== FALSE && !is_null($target_element)) {
      foreach ($this->fieldSelectors() as $field_name => $xpath) {
        #var_dump($field_name. $xpath);
        $this->currentItem[$field_name] = [];
        $provider = new NodeProvider($target_element);
        $expression = XPath2Expression::Compile($xpath, $this->nsMgr);
        $vars = [];
        $result = $expression->EvaluateWithVars($provider, $vars);
        if (\is_int($result)) {
          $this->currentItem[$field_name][] = $result;
          continue;
        }
        foreach ($result as $value) {
          $current_node = $value->getUnderlyingObject();
          if ($current_node instanceof \DOMAttr) {
            $this->currentItem[$field_name][] = $current_node->value;
          }
          else if ($current_node instanceof \DOMElement) {
            $this->currentItem[$field_name][] = $current_node->nodeValue;
          }
        }
       # var_dump($field_name, $this->currentItem[$field_name]);
      }
      // Reduce single-value results to scalars.
      foreach ($this->currentItem as $field_name => $values) {
        if (is_array($values) && count($values) == 1) {
          $this->currentItem[$field_name] = reset($values);
        }
      }
      #var_dump($field_name, $this->currentItem[$field_name]);
    }
  }

  /**
   * Parses a LibXMLError to a error message string.
   *
   * @param \LibXMLError $error
   *   Error thrown by the XML.
   *
   * @return string
   *   Error message
   */
  public static function parseLibXmlError(\LibXMLError $error): TranslatableMarkup {
    $error_code_name = 'Unknown Error';
    switch ($error->level) {
      case LIBXML_ERR_WARNING:
        $error_code_name = t('Warning');
        break;

      case LIBXML_ERR_ERROR:
        $error_code_name = t('Error');
        break;

      case LIBXML_ERR_FATAL:
        $error_code_name = t('Fatal Error');
        break;
    }

    return t(
      "@libxmlerrorcodename @libxmlerrorcode: @libxmlerrormessage\nLine: @libxmlerrorline\nColumn: @libxmlerrorcolumn\nFile: @libxmlerrorfile",
      [
        '@libxmlerrorcodename' => $error_code_name,
        '@libxmlerrorcode' => $error->code,
        '@libxmlerrormessage' => trim((string) $error->message),
        '@libxmlerrorline' => $error->line,
        '@libxmlerrorcolumn' => $error->column,
        '@libxmlerrorfile' => $error->file,
      ]
    );
  }
}
