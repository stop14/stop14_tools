<?php

namespace Drupal\stop14_tools\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a user interface block for Browser mechanisms.
 * CURRENT A STUB FUNCTION.
 *
 * @Block(
 *   id = "stop14_tools_browser_toolbar",
 *   admin_label = @Translation("Stop14 Browser Toolbar"),
 *   category = @Translation("Stop14")
 * )
 */
class BrowserToolbar extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['content'] = [
      '#theme' => 'stop14_browser_toolbar',
      '#attached' =>[
        'library' => [
          'stop14_tools/browsertools',
        ],
      ],

    ];
    return $build;
  }

}
