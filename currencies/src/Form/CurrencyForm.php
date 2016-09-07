<?php

namespace Drupal\currencies\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class CurrencyForm.
 *
 * @package Drupal\currencies\Form
 */
class CurrencyForm extends EntityForm {

  protected $entityQuery;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\Query\QueryFactory $entity_query
   *   The entity query.
   */
  public function __construct(QueryFactory $entity_query) {
    $this->entityQuery = $entity_query;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.query')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $currency = $this->entity;

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Currency Name'),
      '#autocomplete_route_name' => 'currencies.bnrb.currencies_names',
      '#maxlength' => 255,
      '#default_value' => $currency->label(),
      '#description' => $this->t("Label for the Currency."),
      '#required' => TRUE,
    ];

    $form['code'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Currency code'),
      '#autocomplete_route_name' => 'currencies.bnrb.currencies_codes',
      '#default_value' => $currency->getCode(),
      '#maxlength' => 3,
      '#required' => TRUE,
    ];

    $form['display'] = [
      '#type' => 'select',
      '#title' => $this->t('Currency display'),
      '#options' => [
        'page'  => $this->t('Page'),
        'block' => $this->t('Block'),
      ],
      '#default_value' => $currency->getDisplay() ? $currency->getDisplay() : $this->t('Page'),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ($form_state->getValue('code') && $form_state->getValue('display')) {
      $id = strtolower(trim($form_state->getValue('code'))) . '_' . $form_state->getValue('display');
      if ($this->entity->isNew()) {
        if ($this->exist($id)) {
          $form_state->setErrorByName('display', $this->t('Currency with this display already exists.'));
        }
        else {
          $this->entity->set('id', $id);
        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $currency = $this->getEntity();
    $status = $currency->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Currency.', [
          '%label' => $currency->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Currency.', [
          '%label' => $currency->label(),
        ]));
    }
    $form_state->setRedirectUrl($currency->urlInfo('collection'));
  }

  /**
   * Check if Currency exists.
   */
  public function exist($id) {
    $entity = $this->entityQuery->get('currency')
      ->condition('id', $id)
      ->execute();
    return (bool) $entity;
  }

}
