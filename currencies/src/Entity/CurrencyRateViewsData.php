<?php

namespace Drupal\currencies\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Currency rate entities.
 */
class CurrencyRateViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['currency_rate']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Currency rate'),
      'help' => $this->t('The Currency rate ID.'),
    );

    return $data;
  }

}
