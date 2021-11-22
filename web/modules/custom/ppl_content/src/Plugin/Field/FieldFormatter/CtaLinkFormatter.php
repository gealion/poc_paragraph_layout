<?php

namespace Drupal\ppl_content\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Template\Attribute;
use Drupal\Core\Url;
use Drupal\link\LinkItemInterface;
use Drupal\link\Plugin\Field\FieldFormatter\LinkFormatter;

/**
 * Plugin implementation of the 'CTA Link' formatter.
 *
 * @FieldFormatter(
 *   id = "cta_link",
 *   label = @Translation("CTA Link"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class CtaLinkFormatter extends LinkFormatter {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];
    $count = $items->count();
    $i = 0;
    foreach ($items as $delta => $item) {
      $url = $this->buildUrl($item)->toString();
      $element[$delta] = [
        '#theme' => 'cta_link',
        '#attributes' => new Attribute([
          'class' => [
            'btn',
          ],
          'type' => 'button',
          'href' => $url,
        ]),
        '#title' => $item->title ?? $url,

      ];
      $i++;
     if ($i === $count) {
        $element[$delta]['#attributes']->addClass('btn-primary');
      }
     else {
       $element[$delta]['#attributes']->addClass('btn-secondary');
     }
    }

    return $element;
  }

}
