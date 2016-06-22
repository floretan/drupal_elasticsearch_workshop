<?php

namespace Drupal\recipe_search\Plugin\ElasticsearchIndex;

use Drupal\elasticsearch_helper\Plugin\ElasticsearchIndexBase;

/**
 * @ElasticsearchIndex(
 *   id = "drupal_recipes",
 *   label = @Translation("Drupal Recipes Index"),
 *   indexName = "drupal_recipes",
 *   typeName = "recipe",
 *   entityType = "node",
 *   bundle = "recipe"
 * )
 */
class RecipeIndex extends ElasticsearchIndexBase {


}