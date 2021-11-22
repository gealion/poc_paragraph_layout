<?php

namespace Drupal\ppl_content\Plugin\Layout;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Layout\LayoutDefault;
use Drupal\Core\Plugin\PluginFormInterface;


class OneColumnLayout extends LayoutDefault implements PluginFormInterface{
  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $configuration = parent::defaultConfiguration();
    return $configuration + [
        'background_color' => 'bg-white',
        'width' => 'centered',
      ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['background_color'] = [
      '#type' => 'radios',
      '#title' => $this->t('Background Color'),
      '#default_value' => $this->configuration['background_color'],
      '#options' => [
        'bg-primary' => $this->t('primary'),
        'bg-secondary' => $this->t('secondary'),
        'bg-success' => $this->t('success'),
        'bg-danger' => $this->t('danger'),
        'bg-warning' => $this->t('warning'),
        'bg-info' => $this->t('info'),
        'bg-light' => $this->t('light'),
        'bg-dark' => $this->t('dark'),
        'bg-white' => $this->t('white'),
      ],
      '#description' => $this->t('Choose the background color for this layout.'),
    ];
    $form['width'] = [
      '#type' => 'radios',
      '#title' => $this->t('Width'),
      '#default_value' => $this->configuration['width'],
      '#options' => [
        'centered' => $this->t('Centered'),
        'full_width' => $this->t('Full Width'),
      ],
      '#description' => $this->t('Choose if the content of this layout should be full width or centered.'),
    ];
    return parent::buildConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    $this->configuration['background_color'] = $form_state->getValue('background_color');

    $this->configuration['width'] = $form_state->getValue('width');
  }

  /**
   * {@inheritdoc}
   */
  public function build(array $regions) {
    $build = parent::build($regions);
    $build['#attributes']['class'][] = $this->configuration['background_color'];

    if ($this->configuration['width'] === 'centered') {
      $build['#theme'] = $build['#theme'] . '__centered';
    }
    return $build;
  }

}
