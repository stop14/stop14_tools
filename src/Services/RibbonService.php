<?php

/**
 * @file
 * Ribbon service file.
 *
 */

namespace Drupal\stop14_tools\Services;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Language\LanguageManager;
use Drupal\Core\Theme\ThemeManagerInterface;

/**
 * Wrapper methods for Ribbon API methods.
 *
 *
 * @ingroup ribbon
 */
class RibbonService {

  /**
   * The module handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The theme manager service.
   *
   * @var \Drupal\Core\Theme\ThemeManagerInterface
   */
  protected $themeManager;

  /**
   * The language manager service
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * The config factory service
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a RibbonService object.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param \Drupal\Core\Theme\ThemeManagerInterface $theme_manager
   *   The theme manager.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(ModuleHandlerInterface $module_handler, ThemeManagerInterface $theme_manager, LanguageManager $language_manager, ConfigFactoryInterface $config_factory) {
    $this->moduleHandler = $module_handler;
    $this->themeManager = $theme_manager;
    $this->languageManager = $language_manager;
    $this->configFactory = $config_factory;
  }

  /**
   * Get default options.
   *
   * @return array
   *   An associative array of default options for Ribbon.
   */
  public function getRibbonDefaultOptions() {
    $options = [
      'sml' => 1,
      'med' => 2,
      'lrg' => 3,
      'xlrg' => 3
    ];

    return $options;
  }

  /**
   * Apply Ribbon to a container.
   *
   * @param array $form
   *   The form to which the JS will be attached.
   * @param string $container
   *   The CSS selector of the container element to apply Ribbon to.
   * @param string $item_selector
   *   The CSS selector of the items within the container.
   * @param array $options
   *   An associative array of Ribbon options.
   * @param string[] $viewer_ids
   */
  public function applyRibbonDisplay(&$form, $container, $item_selector, $options = [], $viewer_ids = ['ribbon_default']) {
    if (!empty($container)) {
      // For any options not specified, use default options.
      $options += $this->getRibbonDefaultOptions();
      if (!isset($item_selector)) {
        $item_selector = '';
      }

      // Setup  component.
      $ribbon = [
        'ribbon' => [
          $container => [
            'viewer_ids' => $viewer_ids,
            'sml' => (int) $options['sml'],
            'med' => (int) $options['med'],
            'lrg' => (int) $options['lrg'],
            'xlrg' => (int) $options['xlrg']
          ],
        ],
      ];

      // Allow other modules and themes to alter the settings.
      $context = [
        'container' => $container,
        'item_selector' => $item_selector,
        'options' => $options,
      ];
      $this->moduleHandler->alter('ribbon_component', $ribbon, $context);
      $this->themeManager->alter('ribbon_component', $ribbon, $context);

      $form['#attached']['library'][] = 'ribbon/stop14_tools.ribbon';
      if (isset($form['#attached']['drupalSettings'])) {
        $form['#attached']['drupalSettings'] += $ribbon;
      }
      else {
        $form['#attached']['drupalSettings'] = $ribbon;
      }
    }
  }

  /**
   * Build the settings configuration form.
   *
   * @param array (optional)
   *   The default values for the form.
   *
   * @return array
   *   The form
   */
  public function buildSettingsForm($default_values = []) {
    // Load module default values if empty.
    if (empty($default_values)) {
      $default_values = $this->getRibbonDefaultOptions();
    }

    $form['breakpoints'] = [
      '#type' => 'fieldset',
      '#title' => t('Breakpoint columns')
    ];

    $form['breakpoints']['sml'] = [
      '#type' => 'textfield',
      '#title' => t('Small'),
      '#description' => t("Number of initially visible columns for handheld mobile devices ('sml' breakpoint)."),
      '#default_value' => $default_values['sml'],
      '#size' => 2,
      '#maxlength' => 2
    ];

    $form['breakpoints']['med'] = [
      '#type' => 'textfield',
      '#title' => t('Medium'),
      '#description' => t("Number of initially visible columns for tablet and narrow-viewport devices ('med' breakpoint)."),
      '#default_value' => $default_values['med'],
      '#size' => 2,
      '#maxlength' => 2
    ];

    $form['breakpoints']['lrg'] = [
      '#type' => 'textfield',
      '#title' => t('Large'),
      '#description' => t("Number of initially visible columns for desktop devices ('lrg' breakpoint)."),
      '#default_value' => $default_values['lrg'],
      '#size' => 2,
      '#maxlength' => 2
    ];

    $form['breakpoints']['xlrg'] = [
      '#type' => 'textfield',
      '#title' => t('Extra-Large'),
      '#description' => t("Number of initially visible columns for wide viewports ('xlrg' breakpoint)."),
      '#default_value' => $default_values['xlrg'],
      '#size' => 2,
      '#maxlength' => 2
    ];

    // Allow other modules and themes to alter the form.
    $this->moduleHandler->alter('ribbon_options_form', $form, $default_values);
    $this->themeManager->alter('ribbon_options_form', $form, $default_values);

    return $form;
  }

  /*
   * Check that prerequisites are installed.
   *
   * STUB FUNCTION
   */

  public function flightCheck(): bool {
    return TRUE;
  }

}
