<?php

/**
 * @file
 * Contains currency_rate.page.inc.
 *
 * Page callback for Currency rate entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Currency rate templates.
 *
 * Default template: currency_rate.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_currency_rate(array &$variables) {
  // Fetch CurrencyRate Entity Object.
  $currency_rate = $variables['elements']['#currency_rate'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
