<?php
/**
 * @file
 * Contains \Drupal\currencies\Plugin\Block\CurrenciesRatesBlock.
 */

namespace Drupal\currencies\Plugin\Block;

use Behat\Mink\Exception\Exception;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityManager;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provide a CurrenciesRatesBlock plugin.
 *
 * @Block(
 *   id = "currencies_block",
 *   admin_label = @Translation("Currencies block"),
 * )
 */
class CurrenciesRatesBlock extends BlockBase{


  /**
   * {@inheritdoc}
   */
  public function build() {

//    $config = $this->getConfiguration();
//    $currencies = \Drupal::service('currencies.nbrb')->getAllCurrencies();
//
//    if (isset($config['currencies_list'])) {
//      foreach ($currencies as $key => $curriency) {
//        if (!in_array($key, $config['currencies_list'])) {
//          unset($currencies[$key]);
//        }
//      }
//    }
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
        if ($currency->getDisplay() != 'block') { continue; }

        $output .= "<tr>";
        $output .= "<td>{$code}/BYN </td>";
        $output .= "<td>{$currencyRate->rate->value}</td>";
        $output .= "<td>{$currencyRate->rate_diff->value}</td>";
        $output .= "</tr>";
      }
      $output .= "</table>";
    }

    return [
      '#markup' => isset($output) ?  $output : 'No data',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

//    $config = $this->getConfiguration();
//
//    $options = \Drupal::service('currencies.nbrb')->getAllCurrencies();
//
//    $form['currencies_list'] = [
//      '#type' => 'checkboxes',
//      '#title' => $this->t('Select currencies'),
//      '#description' => $this->t('If none selected all currencies will be displayed'),
//      '#options' => array_combine(array_keys($options), array_keys($options)),
//      '#default_value' => isset($config['currencies_list']) ? $config['currencies_list'] : '',
//    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
//    $this->configuration['currencies_list'] = [];
//    foreach ($form_state->getValue('currencies_list') as $key => $value) {
//      if (!$value) {
//        continue;
//      }
//      $this->configuration['currencies_list'][$key] = $value;
//    }
  }

}
