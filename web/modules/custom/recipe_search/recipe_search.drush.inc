<?php

use Elasticsearch\ClientBuilder;
use Drush\Log\LogLevel;
use Elasticsearch\Common\Exceptions\Missing404Exception;

/**
 * Implementation of hook_drush_command().
 */
function recipe_search_drush_command() {
  $items['import-recipes'] = array(
    'description' => 'Import recipes.',
  );

  return $items;
}

/**
 * Drush command to import recipes into elasticsearch.
 */
function drush_recipe_search_import_recipes() {

  // Create an elasticsearch client.
  $client = ClientBuilder::fromConfig([
    'hosts' => ['localhost:9200']
  ]);

  // We will call our index "recipes".
  $index_name = 'recipes';
  $type_name = 'recipe';

  try {
    // Remove existing data.
    $client->indices()->delete([
      'index' => $index_name
    ]);
  }
  catch (Missing404Exception $e) {
    // Don't do anything.
  }

  // These are not really ingreadients.
  $irrelevant_ingredients = ['to', 'and', 'or', 'of', 'for', 'with', 'cup', 'cups', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'bunch', 'ounce', 'ounces', 'tablespoon', 'tablespoons', 'tablespons', 'tbsp', 'tbs', 'teaspoon', 'teaspoons', 'tsp', 'pinch', 'taste', 'none', 'warm', 'cold', 'whole', 'weight', 'clove', 'cloves', 'pound', 'can', 'cans', 'package', 'slices', 'chopped', 'stick', 'sticks', 'ground', 'large', 'fluid', 'fresh', 'dash', 'piece', 'pieces', 'box'];

  /**
   * TASK #2: Explicitly set the schema.
   *
   * See https://www.elastic.co/guide/en/elasticsearch/reference/current/indices-create-index.html
   */

  // Write your code here.

  // Hint: You can see the current schema at http://localhost:9200/recipes/_mapping?pretty

  /**
   * End of TASK #2.
   */

  $count = 0;
  $start = time();
  foreach (_drush_recipe_search_load_recipe_data() as $id => $recipe) {
    try {
      /**
       * TASK #1: Index the recipe in Elasticsearch.
       */

      $response = $client->index([
        'index' => $index_name,
        'type' => $type_name,
        'id' => $id,
        'body' => $recipe,
      ]);

      /**
       * End of TASK #1.
       *
       * Optional task: If you are done already, you can speed up the import by
       * using Elasticsearch's Bulk API:
       * https://www.elastic.co/guide/en/elasticsearch/reference/current/docs-bulk.html
       */

      if ($response['_id']) {
        $count++;
        drush_print('Imported ' . $recipe['name']);
      }
    } catch (\Exception $e) {
      drush_log($e, LogLevel::ERROR);
    }
  }

  $time_taken = time() - $start;
  drush_print('Imported ' . $count . ' recipes in ' . $time_taken . ' second');
}

function _drush_recipe_search_load_recipe_data() {
  // Open the database file.
  $file_content = file_get_contents('../datasets/recipes.json');

  // The format is a bit weird, so we need to do some data wrangling.
  $json_snippets = explode("\n", $file_content);

  $sourceLabels = [
    'tastykitchen' => 'Tasty Kitchen',
    'allrecipes' => 'All Recipes',
    'bbcgoodfood' => 'BBC Good Food',
    'epicurious' => 'Epicurious',
    'chow' => 'Chow',
    'bbcfood' => 'BBC Food',
    'williamssonoma' => 'Williams Sonoma',
    'jamieoliver' => 'Jamie Oliver',
    'lovefood	' => 'Love Food',
    'browneyedbaker' => 'Brown Eyed Baker',
    'smittenkitchen	' => 'Smitten Kitchen',
    'cookincanuck' => 'Cookin Canuck',
    'biggirlssmallkitchen	' => 'Big Girl Small Kitchen',
    'steamykitchen' => 'Steamy Kitchen',
    'thepioneerwoman' => 'The Pioneer Woman',
    'whatsgabycooking' => 'What\'s Gaby Cooking',
    'aspicyperspective' => 'A Spicy Perspective',
    'cookbooks' => 'Cookbooks',
    'backtoherroots' => 'Back to the Roots',
    'naturallyella' => 'Naturally Ella',
    'bonappetit' => 'Bon Appétit',
    'cookieandkate' => 'Cookie and Kate',
    'elanaspantry' => 'Elana\'s Pantry',
    'bunkycooks' => 'Bunky Cooks',
    'pickypalate' => 'Picky Palate',
    'eatthelove' => 'Eat the Love',
    'seriouseats' => 'Serious Eats',
    'thevintagemixer' => 'The Vintage Mixer',
    'delishhh' => 'Delishhh',
    'paninihappy' => 'PaniniHappy',
    'foodnetwork' => 'Food Network',
    'thelittlekitchen' => 'The Little Kitchen',
  ];

  $recipes = [];
  foreach ($json_snippets as $json_snippet) {
    if ($recipe = json_decode($json_snippet, TRUE)) {

      // Extract the id.
      $id = $recipe['_id']['$oid'];
      unset ($recipe['_id']);

      // Remove the timestamp, it's all the same date anyway.
      unset ($recipe['ts']);

      // Make things more fun by replacing the source identifyer with its name.
      $recipe['source'] = $sourceLabels[$recipe['source']];

      $recipes[$id] = $recipe;
    }
  }

  return $recipes;
}