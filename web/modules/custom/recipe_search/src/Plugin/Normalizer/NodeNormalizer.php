<?php

namespace Drupal\recipe_search\Plugin\Normalizer;

use Drupal\serialization\Normalizer\ContentEntityNormalizer;
use Drupal\node\Entity\Node;

/**
 * Normalizes / denormalizes Drupal nodes into an array structure good for ES.
 */
class NodeNormalizer extends ContentEntityNormalizer {

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var array
   */
  protected $supportedInterfaceOrClass = ['Drupal\node\Entity\Node'];

  /**
   * Supported formats.
   *
   * @var array
   */
  protected $format = ['elasticsearch_helper'];

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = array()) {
    /** @var Node $object */

    /**
     * TASK 6: Structure your data however you want to index it.
     */
    $data =  [
      'id' => $object->id(),
      'name' => $object->getTitle(),
      'description' => $object->body->value,
      'url' => $object->url(),
      'source' => 'Drupal',
    ];

    foreach ($object->field_ingredients as $ingredient) {
      $data['ingredients'][] = $ingredient->value;
    }

    return $data;
  }
}