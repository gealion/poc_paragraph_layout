<?php


namespace Drupal\ppl_content\Plugin\ExtraField\Display;


use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityViewBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\extra_field\Plugin\ExtraFieldDisplayBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Extract and return Image URL for the background Image of Hero Banner
 * Paragraphs
 *
 * @ExtraFieldDisplay(
 *   id = "reference_grid",
 *   label = @Translation("Reference Grid"),
 *   description = @Translation("REturn a grid of References Teaser"),
 *    bundles = {
 *     "paragraph.reference_list",
 *   }
 * )
 */
class ReferenceGrid extends ExtraFieldDisplayBase implements ContainerFactoryPluginInterface {

  private EntityViewBuilderInterface $nodeViewBuilder;

  private EntityStorageInterface $nodeStorage;

  /**
   * Creates an instance of the plugin.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container to pull out services used in the plugin.
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   *
   * @return static
   *   Returns an instance of this plugin.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * ReferenceGrid constructor.
   *
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entityTypeManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->nodeStorage = $entityTypeManager->getStorage('node');
    $this->nodeViewBuilder = $entityTypeManager->getViewBuilder('node');
  }

  /**
   * @inheritDoc
   */
  public function view(ContentEntityInterface $entity) {
    $query = $this->nodeStorage->getQuery()
      ->condition('type', 'reference');

    if ($entity->get('field_count')->value != 0) {
      $query->range(0, $entity->get('field_count')->value);
    }

    $results = $query->execute();
    $references = $this->nodeStorage->loadMultiple($results);
    return $this->nodeViewBuilder->viewMultiple($references, 'teaser');
  }


}
