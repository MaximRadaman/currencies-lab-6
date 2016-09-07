<?php

namespace Drupal\currencies\Plugin\Field\FieldFormatter;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceFormatterBase;

/**
 * Plugin implementation of the 'author' formatter.
 *
 * @FieldFormatter(
 *   id = "currency",
 *   label = @Translation("Currency"),
 *   description = @Translation("Display the referenced currency entity."),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class CurrencyFormatter extends EntityReferenceFormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = array();

    foreach ($this->getEntitiesToView($items, $langcode) as $delta => $entity) {
      $elements[$delta] = array(
        '#theme' => 'currency_autocomplete',
        '#account' => $entity,
        '#link_options' => array('attributes' => array('rel' => 'currency')),
        '#cache' => array(
          'tags' => $entity->getCacheTags(),
        ),
      );
    }

    return $elements;
  }

}
