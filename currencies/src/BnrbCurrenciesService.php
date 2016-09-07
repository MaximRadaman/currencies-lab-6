<?php
/**
 * @file
 * Contains \Drupal\currencies\BnrbCurrenciesService.
 */

namespace Drupal\currencies;

/**
 * Class CurrenciesService.
 *
 * @package Drupal\currencies
 */
class BnrbCurrenciesService implements CurrenciesServiceInterface {

  const CURRENCIES_BNRB_URL = '//www.nbrb.by/Services/XmlExRates.aspx';

  /**
   * Http client.
   *
   * @var \GuzzleHttp\Client.
   */
  protected $httpClient;

  /**
   * Date object in format m/d/Y.
   *
   * @var Object date.
   */
  protected $date;

  /**
   * BnrbCurrenciesService constructor.
   *
   * @param \DateTime $date
   *    Date object or null.
   */
  public function __construct(\DateTime $date = NULL) {
    $this->httpClient = \Drupal::httpClient();

    if (!isset($date)) {
      $date = new \DateTime();
    }
    $this->date = $date->format('m/d/Y');
  }

  public function setDate($date) {
    $this->date = $date;
  }

  /**
   * Get all Currencies related to BYN.
   */
  public function getAllCurrencies() {
    $currencies = [];
    $cid = 'currencies_nbrb:' . $this->date;

    if ($cache = \Drupal::cache()->get($cid)) {
      $currencies = $cache->data;
    }
    else {
      // Get Currencies.
      try {
        $response = $this->httpClient->request('GET', self::CURRENCIES_BNRB_URL, [
          'query' => ['ondate' => $this->date],
        ]);
      }
      catch (RequestException $e) {
        watchdog_exception('currencies', $e);
      }

      if ($response->getStatusCode() == 200) {
        try {
          $xml = new \SimpleXMLElement($response->getBody());
        }
        catch (ParseException $e) {
          watchdog_exception('currencies', $e);
        }

        foreach ($xml->Currency as $currency) {
          $currencies[(string) $currency->CharCode] = (object) (array) $currency;
        }
      }
      if ($currencies) {
        // Cache for 1 day.
        \Drupal::cache()->set($cid, $currencies, mktime(0, 0, 0, date('n'), date('j') + 1));
      }
    }

    return $currencies;
  }

}
