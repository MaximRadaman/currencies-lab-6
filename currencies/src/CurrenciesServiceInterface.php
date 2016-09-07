<?php
/**
 * @file
 * Contains \Drupal\currencies\CurrenciesServiceInterface.
 */

namespace Drupal\currencies;

/**
 * Interface CurrenciesServiceInterface.
 */
interface CurrenciesServiceInterface {

  /**
   * Retrive Currencies to BYN.
   */
  public function getAllCurrencies();

}
