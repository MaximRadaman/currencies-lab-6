<?php

namespace Drupal\currencies;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Currency rate entities.
 *
 * @ingroup currencies
 */
class CurrencyRateListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['currency'] = $this->t('Currency');
    $header['rate'] = $this->t('Rate to BYN');
    $header['diff'] = $this->t('Rate to previous day');
    $header['rate_date'] = $this->t('Rate day');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\currencies\Entity\CurrencyRate */
    $row['currency'] = $this->l(
      $entity->label(),
      new Url(
        'entity.currency_rate.edit_form', array(
          'currency_rate' => $entity->id(),
        )
      )
    );
    $row['rate'] = $entity->get('rate')->value;
    $row['rate_diff'] = $entity->get('rate_diff')->value;
    $row['rate_date'] = $entity->get('rate_date')->value;
    return $row + parent::buildRow($entity);
  }

}
