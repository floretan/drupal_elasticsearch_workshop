<?php

namespace Drupal\recipe_search\Controller;

use Drupal\Core\Controller\ControllerBase;
use Elasticsearch\ClientBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RecipeSearchAPIController.
 *
 * @package Drupal\recipe_search\Controller
 */
class RecipeSearchAPIController extends ControllerBase {

  /**
   * @var \Elasticsearch\Client
   */
  protected $client;

  /**
   * RecipeSearchAPIController constructor.
   */
  public function __construct() {
    // Our Elasticsearch client.
    $this->client = ClientBuilder::fromConfig([
      'hosts' => ['localhost:9200']
    ]);
  }

  /**
   * Search.
   *
   * @return string
   *   Return Hello string.
   */
  public function search(Request $request) {

    // The keywords typed in by the user.
    $input = $request->query->get('input');

    // Selected facet values.
    $selected_sources = $request->query->get('sources');
    $selected_ingredients = $request->query->get('ingredients');

    // Paging information.
    $from = $request->query->get('from');
    $size = $request->query->get('size');



    $data = [
      // The total number of results for the given search parameters.
      'total' => 0,

      // These should be structured objects.
      // In this case we can return the _source property from each Elasticsearch result.
      'hits' => [],

      // These should be "buckets" as returned by Elasticsearch term aggregations.
      'sourceFacet' => [],
      'ingredientsFacet' => [],
    ];

    /**
     * TASK #3: Modify the query to filter results based on the selected sources.
     * Optional task: optimize the payload by returning only the fields we want (hint: check Source Filtering).
     *
     * TASK #4: Add the ingredients facet.
     */

    $query = [
      'index' => 'recipes',
      'body' => [
        'query' => [
          'bool' => [
            'must' => [
              [
                'match' => [
                  // Search everywhere.
                  '_all' => $input,
                ]
              ]
            ]
          ]
        ],
        'aggs' => [
          'sources' => [
            'terms' => [
              'field' => 'source'
            ],
          ],
        ],
      ],
      'from' => $from,
      'size' => $size,
    ];

    if ($selected_sources) {
      $query['body']['post_filter'] = [
        'terms' => [
          'source' => $selected_sources,
        ]
      ];
    }

    /**
     * End of Task #3 and #4
     */

    // Execute our query.
    $response = $this->client->search($query);

    // Add search results to the response.
    foreach ($response['hits']['hits'] as $result) {
      $data['hits'][] = $result['_source'];
    }

    // Add the total result count.
    $data['total'] = $response['hits']['total'];

    // Add the 'source' facet.
    if (isset($response['aggregations']['sources'])) {
      $data['sourceFacet'] = $response['aggregations']['sources']['buckets'];
    }

    // Add the 'ingredients' facet.
    if (isset($response['aggregations']['ingredients'])) {
      $data['ingredientsFacet'] = $response['aggregations']['ingredients']['buckets'];
    }

    return new JsonResponse($data);
  }

  /**
   * Suggest.
   *
   * @return string
   *   Return Hello string.
   */
  public function suggest(Request $request) {
    $input = strtolower($request->query->get('input'));

    $data = [];

    /**
     * TASK #5: Add autocomplete sugestions.
     *
     * For example:
     * $data[] = ['label' => 'Bacon'];
     */

    /**
     * End of Task #5.
     */

    return new JsonResponse($data);
  }

}
