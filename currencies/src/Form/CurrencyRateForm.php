<?php

namespace Drupal\currencies\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\Date;

/**
 * Form controller for Currency rate edit forms.
 *
 * @ingroup currencies
 */
class CurrencyRateForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;

    /* @var $entity \Drupal\currencies\Entity\CurrencyRate */
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $status = parent::save($form, $form_state);
    $entity = $this->entity;

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Currency rate.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Currency rate.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.currency_rate.canonical', ['currency_rate' => $entity->id()]);
  }

}
