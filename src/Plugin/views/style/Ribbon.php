<?php

/**
 * @file
 * Contains \Drupal\stop14_tools\Plugin\views\style\Ribbon.
 *
 */

namespace Drupal\stop14_tools\Plugin\views\style;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\style\StylePluginBase;
use Drupal\views\ViewExecutable;

/**
 * Style plugin to render each item in a Ribbon Layout.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "ribbon",
 *   title = @Translation("Ribbon"),
 *   help = @Translation("Create a Netflix-stlye ribbon interface."),
 *   theme = "views_view_ribbon",
 *   display_types = {"normal"}
 * )
 */
class Ribbon extends StylePluginBase {

  /**
   * Does the style plugin allows to use style plugins.
   *
   * @var bool
   */
  protected $usesRowPlugin = TRUE;

  /**
   * Does the style plugin support custom css class for the rows.
   *
   * @var bool
   */
  protected $usesRowClass = TRUE;

  /**
   * Does the style plugin support grouping of rows.
   *
   * @var bool
   */
  protected $usesGrouping = FALSE;

  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
    parent::init($view, $display, $options);
  }


  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    // Get default options from the Ribbon.
    $default_options = \Drupal::service('stop14_tools.ribbon.service')
      ->getRibbonDefaultOptions();

    // Set default values for the Ribbon.
    foreach ($default_options as $option => $default_value) {
      $options[$option] = [
        'default' => $default_value,
      ];
      if (is_bool($default_value)) {
        $options[$option]['bool'] = TRUE;
      }
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    // Add viewer options to views form.
    $form['ribbon'] = [
      '#type' => 'details',
      '#title' => $this->t('Ribbon'),
      '#open' => TRUE,
    ];
    if (\Drupal::service('stop14_tools.ribbon.service')->flightCheck()) {
      $form += \Drupal::service('stop14_tools.ribbon.service')
        ->buildSettingsForm($this->options);

      // Display each option within the viewer fieldset.
      foreach (\Drupal::service('stop14_tools.ribbon.service')
                 ->getRibbonDefaultOptions() as $option => $default_value) {
        $form[$option]['#fieldset'] = 'ribbon';
      }
    }
    else {
      // Disable Ribbon as plugin is not installed.
      $form['ribbon_disabled'] = [
        '#markup' => $this->t('Drupal is missing one or more supporting libraries. The Ribbon has been disabled.'),
        '#fieldset' => 'ribbon',
      ];
    }
  }
}
