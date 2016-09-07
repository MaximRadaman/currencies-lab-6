<?php

namespace Drupal\currencies\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Currency entity.
 *
 * @ConfigEntityType(
 *   id = "currency",
 *   label = @Translation("Currency"),
 *   handlers = {
 *     "list_builder" = "Drupal\currencies\CurrencyListBuilder",
 *     "form" = {
 *       "add" = "Drupal\currencies\Form\CurrencyForm",
 *       "edit" = "Drupal\currencies\Form\CurrencyForm",
 *       "delete" = "Drupal\currencies\Form\CurrencyDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\currencies\CurrencyHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "currency",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/currency/{currency}",
 *     "add-form" = "/admin/structure/currency/add",
 *     "edit-form" = "/admin/structure/currency/{currency}/edit",
 *     "delete-form" = "/admin/structure/currency/{currency}/delete",
 *     "collection" = "/admin/structure/currency"
 *   }
 * )
 */
class Currency extends ConfigEntityBase implements CurrencyInterface {

  /**
   * The Currency ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Currency name.
   *
   * @var string
   */
  protected $name;

  /**
   * The Currency code.
   *
   * @var string
   */
  protected $code;

  /**
   * The Currency display option (block/page).
   *
   * @var string
   */
  protected $display;

  /**
   * Get Currency code.
   */
  public function getCode() {
    return $this->code;
  }

  /**
   * Get Currency display.
   */
  public function getDisplay() {
    return $this->display;
  }

}
