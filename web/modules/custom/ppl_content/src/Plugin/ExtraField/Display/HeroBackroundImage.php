<?php
namespace Drupal\ppl_content\Plugin\ExtraField\Display;


use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\extra_field\Plugin\ExtraFieldDisplayBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Extract and return Image URL for the background Image of Hero Banner Paragraphs
 *
 * @ExtraFieldDisplay(
 *   id = "hero_banner_image",
 *   label = @Translation("Hero Banner Image"),
 *   description = @Translation("Return the URL of Background Image for HeroBanner paragraphs"),
 *    bundles = {
 *     "paragraph.hero",
 *   }
 * )
 */
class HeroBackroundImage extends ExtraFieldDisplayBase  implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  private $imageStyleStorage;

  public function view(ContentEntityInterface $entity) {
    if (!$entity->field_media->isEmpty()) {
      $media = $entity->field_media->entity;
      $imageStyle = $this->imageStyleStorage->load('wide');

      return ['#markup' => $imageStyle->buildUrl($media->field_media_image->entity->uri->value)];
    }
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   *
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param \Drupal\Core\Datetime\DateFormatterInterface $dateFormatter
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entityTypeManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->imageStyleStorage = $entityTypeManager->getStorage('image_style');

  }
}
