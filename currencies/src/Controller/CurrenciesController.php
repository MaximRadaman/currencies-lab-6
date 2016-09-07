<?php
/**
 * @file
 * Contains \Drupal\currencies\Controller\CurrenciesController.
 */

namespace Drupal\currencies\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\currencies\CurrenciesServiceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CurrenciesController.
 *
 * @package Drupal\currencies\Controller
 */
class CurrenciesController extends ControllerBase {

  /**
   * Currency service.
   */
  protected $currenciesService;

  /**
   * CurrenciesController constructor.
   */
  public function __construct(CurrenciesServiceInterface $currenciesService) {
    $this->currenciesService = $currenciesService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('currencies.nbrb')
    );
  }

  /**
   * Get all currencies.
   */
  public function getAllCurrencies() {

    /**
     * Render All currencies page using block.
     *
     * $currencies_block = \Drupal::service('plugin.manager.block')->createInstance('currencies_block', []);
     * $currencies_block = $currencies_block->build();
     * $output = isset($currencies_block['#markup']) ? $currencies_block['#markup'] : '';
     */

    // Render All currencies page using service.
    $entityQuery = \Drupal::service('entity.query');
    $entityTypeManager = \Drupal::service('entity_type.manager');
    $cur_rate_ids = $entityQuery->get('currency_rate')
      ->execute();
    $cur_rate_storage = $entityTypeManager->getStorage('currency_rate');
    if ($cur_rate_ids) {
      $currencyRates = $cur_rate_storage->loadMultiple($cur_rate_ids);
      $output = "<table width='100%'>";
      foreach ($currencyRates as $currencyRate) {
        $currency = $currencyRate
          ->get('currency')
          ->first()
          ->get('entity')
          ->getTarget()
          ->getValue();
        $code = $currency->getCode();
        if ($currency->getDisplay() != 'page') { continue; }

        $output .= "<tr>";
        $output .= "<td>{$code}/BYN </td>";
        $output .= "<td>{$currencyRate->rate->value}</td>";
        $output .= "<td>{$currencyRate->rate_diff->value}</td>";
        $output .= "</tr>";
      }
      $output .= "</table>";
    }

    return [
      '#markup' => $output,
    ];
  }

  /**
   * Get currencies Codes.
   */
  public function bnrbCurrenciesCodes(Request $request) {
    $output = [];
    $string = $request->query->get('q');
    $currencies = $this->currenciesService->getAllCurrencies();

    if ($currencies) {
      foreach ($currencies as $currency) {
        if (!preg_match('/^' . strtoupper($string) . '/', $currency->CharCode, $matches)) {
          continue;
        }
        $output[] = ['value' => $currency->CharCode, 'label' => $currency->CharCode];
      }
    }

    return new JsonResponse($output);
  }

  /**
   * Get currencies Names.
   */
  public function bnrbCurrenciesNames(Request $request) {
    $output = [];
    $currencies = $this->currenciesService->getAllCurrencies();
    $string = $request->query->get('q');

    if ($currencies) {
      foreach ($currencies as &$currency) {
        if (!preg_match('/' . strtoupper($string) . '/', $currency->Name, $matches)) {
          continue;
        }
        $output[] = ['value' => $currency->Name, 'label' => $currency->Name];
      }
    }

    return new JsonResponse($output);
  }

}
