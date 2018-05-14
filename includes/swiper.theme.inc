<?php

/**
 * @file
 * Theming functions for the swiper module.
 *
 * Preprocessor functions fill variables for templates and helper
 * functions to make theming easier.
 */

use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\NestedArray;
use Drupal\swiper\Entity\Swiper;

/**
 * Prepares variables for swiper template.
 *
 * Default template: templates/swiper.html.twig.
 */
function template_preprocess_swiper(&$variables) {
  // Reference configuration variables.
  $settings = &$variables['swiper']['settings'];
  $items = &$variables['swiper']['items'];
  $options = &$settings['options'];

  // Load the selected options.
  if (!empty($options)) {
    $options = Swiper::load($options);
  }

  // Configure attributes for containing elements.
  $attributes = $variables['attributes'];
  // Merge with defined attributes.
  if (isset($settings['attributes']) && is_array($settings['attributes'])) {
    $attributes = NestedArray::mergeDeep($attributes, $settings['attributes']);
  }

  // Set the ID for each swiper instance if none is provided.
  if (empty($attributes['id'])) {
    $swiper_id = &drupal_static('swiper_id', 0);
    $attributes['id'] = 'swiper-' . ++$swiper_id;
  }

  // Add the swiper class to be namespaced.
  $attributes['class'][] = 'swiper';

  // Add the options name as a class to the container.
  $attributes['class'][] = 'options-' . Html::getClass($options->id());

  // Add the image style name as a class to the container.
  if (!empty($settings['image_style'])) {
    $attributes['class'][] = 'imagestyle-' . Html::getClass($settings['image_style']);
  }

  // Pass attributes to twig.
  $variables['attributes'] = $attributes;

  // Add the list render array.
  $variables['content']['list'] = [
    '#theme' => 'swiper_list',
    '#items' => $items,
    '#settings' => $settings,
  ];

  // Finally, add the configuration to the page.
  $attached = [];
  $attached = swiper_add($variables['attributes']['id'], $variables['swiper']['settings']['options']);
  $variables['#attached'] = $attached;
}

/**
 * Prepares variables for swiper list template.
 *
 * Default template: templates/swiper-list.html.twig.
 */
function template_preprocess_swiper_list(&$variables) {
  // Reference configuration variables.
  $options = &$variables['settings']['options'];
  $items = &$variables['items'];
  $attributes = &$variables['attributes'];

  $attributes['class'][] = 'swiper-wrapper';

  // Build the list.
  $variables['content']['list'] = [];
  if (!empty($items)) {
    foreach ($items as $i => $item) {

      $caption = '';
      if (!empty($item['caption'])) {
        $caption = $item['caption'];
      }

      // Add the list item render array.
      $variables['content']['list'][$i] = [
        '#theme' => 'swiper_list_item',
        '#item' => $item['slide'],
        '#caption' => $caption,
        '#settings' => [
          'options' => $options,
        ],
      ];
    }
  }

}

/**
 * Prepares variables for swiper list item template.
 *
 * Default template: templates/swiper-list-item.html.twig.
 */
function template_preprocess_swiper_list_item(&$variables) {

  // Reference configuration variables.
  $item = &$variables['item'];
  $settings = &$variables['settings'];
  $caption = &$variables['caption'];
  $attributes = &$variables['attributes'];
  /** @var \Drupal\swiper\Entity\Swiper $options */
  $options = $settings['options'];

  // Add swiper slide item class.
  $attributes['class'][] = 'swiper-slide';

  // Generated thumbnail support.
  // @TODO review this piece of code.
  if ($options->getOption('controlNav') === "thumbnails") {
    // If the thumbnails are enabled in the options,
    // extract the url to set as the thumbnail data.
    $src = [];
    if (!preg_match("/<img.+?src=[\"'](.+?)[\"'].+?>/", $item, $src)) {
      preg_match("/<img.+?srcset=[\"'](.+?)[\"'].+?>/", $item, $src);
    }

    if (!empty($src[1])) {
      $attributes['data-thumb'] = $src[1];
    }

    // Let's also get the alt attribute to apply to thumbnails.
    // This only works in library version 2.6+.
    $alt = [];
    preg_match("/<img.+?alt=[\"'](.+?)[\"'].+?>/", $item, $alt);

    if (!empty($alt)) {
      $attributes['data-thumb-alt'] = $alt[1];
    }
  }

  if ($options->getOption('thumbCaptions') and !empty($caption)) {
    $attributes['data-thumbcaption'] = $caption;
    // Prevent captions from appearing in the slider as well.
    if (FALSE === $options->getOption('thumbCaptionsBoth')) {
      $caption = '';
    }
  }
}
