<?php

namespace Drupal\stop14_tools\TwigExtension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TwigCastVariablesExtension extends AbstractExtension  {
  /**
   * This is the same name we used on the services.yml file
   */
  public function getName() {
    return 'stop14_tools.twig_cast_variables_extension';
  }

  public function getFilters()
  {
      return [
          new TwigFilter('int', function ($value) {
              return (int) $value;
          }),
          new TwigFilter('float', function ($value) {
              return (float) $value;
          }),
          new TwigFilter('string', function ($value) {

            // Concatenate 1-dimensional arrays into string

            if (is_array($value)) {
              $output = '';
              foreach ($value as $row) {
                $output .= " " .$row;
              }
              $value = $output;
            }

              return (string) $value;
          }),
          new TwigFilter('bool', function ($value) {
              return (bool) $value;
          }),
          new TwigFilter('array', function (object $value) {
              return (array) $value;
          }),
          new TwigFilter('object', function (array $value) {
              return (object) $value;
          }),
      ];
  }
}
