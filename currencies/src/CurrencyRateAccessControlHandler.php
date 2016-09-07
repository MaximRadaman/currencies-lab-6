<?php

namespace Drupal\currencies;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Currency rate entity.
 *
 * @see \Drupal\currencies\Entity\CurrencyRate.
 */
class CurrencyRateAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\currencies\Entity\CurrencyRateInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished currency rate entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published currency rate entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit currency rate entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete currency rate entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add currency rate entities');
  }

}
