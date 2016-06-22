<?php

namespace Drupal\recipe_search_ui\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class RecipeSearchPageController.
 *
 * @package Drupal\recipe_search\Controller
 */
class RecipeSearchUIPageController extends ControllerBase {

  /**
   * Page controller.
   */
  public function page() {
    return [
      '#attached' => [
        'library' => ['recipe_search_ui/recipe_search_ui'],
      ],
      '#theme' => 'recipe_search_ui',
      '#markup' => '',
    ];
  }

}
