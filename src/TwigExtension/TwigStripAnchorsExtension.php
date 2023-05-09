<?php

namespace Drupal\stop14_tools\TwigExtension;

use Twig\Extension\AbstractExtension;
use Twig\Extension\ExtensionInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TwigStripAnchorsExtension extends AbstractExtension  {
  /**
   * This is the same name we used on the services.yml file
   */
  public function getName() {
    return 'stop14_tools.twig_strip_anchors_extension';
  }


  public function getFilters() {
    return [
      new TwigFilter('strip_anchors', [$this, 'stripAnchors']),
    ];
  }

  /**
   *  A poor person’s approach to stripping anchor tags
   *  Provides an allowable set of tags to pass to strip_tags.
   *
   *  Using preg_replace is a better approach for a future refactor.
   */
  public function stripAnchors($context) {
    $allowed_tags = '<div><article><picture><figure><figcaption><source><img><p><strong><em><span><audio><video><ul><li>';

    if(is_string($context)) {
      $context = strip_tags($context,$allowed_tags);
      return $context;
    }

    $rendered_html = strip_tags(\Drupal::service('renderer')->render($context),$allowed_tags);

    return ['#markup' => $rendered_html];
  }
}
