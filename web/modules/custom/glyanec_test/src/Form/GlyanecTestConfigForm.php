<?php

namespace Drupal\glyanec_test\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configuration form for Glyanec Test module.
 */
class GlyanecTestConfigForm extends ConfigFormBase {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new ConfigForm instance.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['glyanec_test.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'glyanec_test_config_form';
  }

  /**
   * Builds the configuration form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Load existing configuration.
    $config = $this->config('glyanec_test.settings');

    // Checkbox to enable/disable the functionality.
    $form['enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#default_value' => $config->get('enabled'),
    ];

    // Text field for setting a custom title.
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $config->get('title'),
      '#description' => $this->t('Enter a custom title.'),
      '#maxlength' => 255,
      '#required' => TRUE,
    ];

    // Entity reference to select a specific Article node.
    $form['node'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Node'),
      '#target_type' => 'node',
      '#selection_handler' => 'default',
      '#selection_settings' => [
        'target_bundles' => ['article'],
      ],
      '#default_value' => $config->get('node') ? $this->entityTypeManager->getStorage('node')->load($config->get('node')) : NULL,
      '#description' => $this->t('Select an article node.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Save the form values in configuration.
    $this->config('glyanec_test.settings')
      ->set('enabled', $form_state->getValue('enabled'))
      ->set('title', $form_state->getValue('title'))
      ->set('node', $form_state->getValue('node'))
      ->save();

    glyanec_test_add_nodes_to_queue();

    parent::submitForm($form, $form_state);
  }

}
