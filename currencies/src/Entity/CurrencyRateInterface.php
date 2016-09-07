<?php

namespace Drupal\currencies\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Currency rate entities.
 *
 * @ingroup currencies
 */
interface CurrencyRateInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Currency rate name.
   *
   * @return string
   *   Name of the Currency rate.
   */
  public function getName();

  /**
   * Sets the Currency rate name.
   *
   * @param string $name
   *   The Currency rate name.
   *
   * @return \Drupal\currencies\Entity\CurrencyRateInterface
   *   The called Currency rate entity.
   */
  public function setName($name);

  /**
   * Gets the Currency rate creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Currency rate.
   */
  public function getCreatedTime();

  /**
   * Sets the Currency rate creation timestamp.
   *
   * @param int $timestamp
   *   The Currency rate creation timestamp.
   *
   * @return \Drupal\currencies\Entity\CurrencyRateInterface
   *   The called Currency rate entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Currency rate published status indicator.
   *
   * Unpublished Currency rate are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Currency rate is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Currency rate.
   *
   * @param bool $published
   *   TRUE to set this Currency rate to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\currencies\Entity\CurrencyRateInterface
   *   The called Currency rate entity.
   */
  public function setPublished($published);

}
