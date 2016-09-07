<?php

namespace Drupal\currencies\Form;

use Behat\Mink\Exception\Exception;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\currencies\BnrbCurrenciesService;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Class CurrencyRateSettingsForm.
 *
 * @package Drupal\currencies\Form
 *
 * @ingroup currencies
 */
class CurrencyRateSettingsForm extends FormBase {

  protected $entityTypeManager;
  protected $bnrb;
  protected $entityQuery;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('entity.query'),
      $container->get('currencies.nbrb')
    );
  }

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\Query\QueryFactory $entity_query
   *   The entity query.
   */
  public function __construct(EntityTypeManager $entityTypeManager, QueryFactory $entity_query, BnrbCurrenciesService $bnrb) {
    $this->entityTypeManager = $entityTypeManager;
    $this->entityQuery = $entity_query;
    $this->bnrb = $bnrb;
  }

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'CurrencyRate_settings';
  }

  /**
   * Defines the settings form for Currency rate entities.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   Form definition array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['CurrencyRate_settings']['#markup'] = 'Settings form for Currency rate entities. Manage field settings here.';

    $form['actions']['#type'] = 'actions';
    $form['actions']['update'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Update currencies rates'),
      '#button_type' => 'primary',
    );

    return $form;
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // From config.
    $curr_ids = $this->entityQuery->get('currency')
      ->execute();
    // From remote service.
    $bnrb_currencies = $this->bnrb->getAllCurrencies();

    // Previous day currencies.
    $this->bnrb->setDate(date('m/d/Y', strtotime(' -1 day')));
    $bnrb_currencies_prev = $this->bnrb->getAllCurrencies();

    if (!$curr_ids || !$bnrb_currencies) {
      return;
    }
    // Remove all CurrencyRates.
    $cur_rate_ids = $this->entityQuery->get('currency_rate')
      ->execute();
    $cur_rate_storage = $this->entityTypeManager->getStorage('currency_rate');
    if ($cur_rate_ids) {
      $currencyRates = $cur_rate_storage->loadMultiple($cur_rate_ids);
      if ($currencyRates) {
        $cur_rate_storage->delete($currencyRates);
      }
    }

    foreach ($curr_ids as $key => $curr_id) {
      preg_match('/([a-zA-Z]{2,3})_/', $curr_id, $matches);
      $curr_code = strtoupper($matches[1]);
      if ( (!$bnrb_data = $bnrb_currencies[$curr_code]) || (!$bnrb_prev_data = $bnrb_currencies_prev[$curr_code])) {
        continue;
      }
      // Create new
      try{
        $currencyRate = $cur_rate_storage->create([
          'currency' => $curr_id,
          'rate' => $bnrb_data->Rate,
          'rate_diff' => (($bnrb_data->Rate) - ($bnrb_prev_data->Rate)),
          'rate_date' => date('Y-m-d'),
        ]);
        $currencyRate->save();
      }
      catch(\Exception $e) {
        throw new \Exception($e->getMessage());
      }
    }
  }

}
