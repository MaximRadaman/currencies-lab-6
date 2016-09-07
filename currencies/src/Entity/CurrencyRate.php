<?php

namespace Drupal\currencies\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Currency rate entity.
 *
 * @ingroup currencies
 *
 * @ContentEntityType(
 *   id = "currency_rate",
 *   label = @Translation("Currency rate"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\currencies\CurrencyRateListBuilder",
 *     "views_data" = "Drupal\currencies\Entity\CurrencyRateViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\currencies\Form\CurrencyRateForm",
 *       "add" = "Drupal\currencies\Form\CurrencyRateForm",
 *       "edit" = "Drupal\currencies\Form\CurrencyRateForm",
 *       "delete" = "Drupal\currencies\Form\CurrencyRateDeleteForm",
 *     },
 *     "access" = "Drupal\currencies\CurrencyRateAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\currencies\CurrencyRateHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "currency_rate",
 *   admin_permission = "administer currency rate entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "currency",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/currency_rate/{currency_rate}",
 *     "add-form" = "/admin/structure/currency_rate/add",
 *     "edit-form" = "/admin/structure/currency_rate/{currency_rate}/edit",
 *     "delete-form" = "/admin/structure/currency_rate/{currency_rate}/delete",
 *     "collection" = "/admin/structure/currency_rate",
 *   },
 *   field_ui_base_route = "currency_rate.settings"
 * )
 */
class CurrencyRate extends ContentEntityBase implements CurrencyRateInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += array(
      'user_id' => \Drupal::currentUser()->id(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('currency')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('currency', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? NODE_PUBLISHED : NODE_NOT_PUBLISHED);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Currency rate entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDefaultValueCallback('Drupal\node\Entity\Node::getCurrentUserId')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ),
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['currency'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Currency'))
      ->setDescription(t('The Currency entity with display'))
      ->setSetting('target_type', 'currency')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'currency',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => -4,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['rate'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Rate'))
      ->setDescription(t('Rate to BYN'))
      ->setDisplayOptions('form', [
        'type' => 'textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['rate_diff'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Rate diff'))
      ->setDescription(t('Rate diff to previous day'))
      ->setDisplayOptions('form', [
        'type' => 'textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['rate_date'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Start date'))
      ->setDescription(t('The date that the survey is started.'))
      ->setSetting('datetime_type', 'date')
      ->setRequired(true)
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Currency rate is published.'))
      ->setDefaultValue(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
