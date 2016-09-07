<?php

namespace Drupal\currencies;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of Currency entities.
 */
class CurrencyListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Currency');
    $header['code'] = $this->t('Code');
    $header['display'] = $this->t('Display');
    $header['id'] = $this->t('Machine name');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['code'] = $entity->getCode();
    $row['display'] = $entity->getDisplay();
    $row['id'] = $entity->id();

    return $row + parent::buildRow($entity);
  }

}
