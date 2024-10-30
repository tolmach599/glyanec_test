<?php

namespace Drupal\glyanec_test\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

/**
 * Form to demonstrate batch process.
 */
class GlyanecTestBatchResaveNodesForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'batch_resave_nodes_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['node_type'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Node type'),
      '#description' => $this->t('Enter the machine name of the node type you want to resave.'),
      '#required' => TRUE,
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Start Batch Process'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $node_type = $form_state->getValue('node_type');

    $nids = \Drupal::entityQuery('node')
      ->condition('type', $node_type)
      ->condition('status', 1)
      ->accessCheck(FALSE)
      ->execute();

    $operations = [];
    foreach ($nids as $nid) {
      $operations[] = [
        '\Drupal\glyanec_test\Form\GlyanecTestBatchResaveNodesForm::processNode',
        [$nid],
      ];
    }

    $batch = [
      'title' => $this->t('Resaving nodes...'),
      'operations' => $operations,
      'finished' => '\Drupal\glyanec_test\Form\GlyanecTestBatchResaveNodesForm::batchFinishedCallback',
    ];

    batch_set($batch);
  }

  /**
   * Batch operation.
   *
   * @param int $nid
   *   Node ID.
   * @param array $context
   *   Execution context.
   */
  public static function processNode($nid, array &$context) {
    $node = Node::load($nid);
    if ($node) {
      $node->save();
    }

    $context['message'] = t('Resaving node with ID: @nid', ['@nid' => $nid]);
  }

  /**
   * Callback-function after all the operations finish.
   *
   * @param bool $success
   *   Total success.
   * @param array $results
   *   Result of execution.
   * @param array $operations
   *   Remaining operations.
   */
  public static function batchFinishedCallback($success, array $results, array $operations) {
    if ($success) {
      \Drupal::messenger()->addMessage(t('All nodes have been resaved successfully.'));
    }
    else {
      \Drupal::messenger()->addMessage(t('Some nodes could not be processed.'));
    }
  }

}
