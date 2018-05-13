<?php

namespace Drupal\swiper\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\swiper\SwiperDefaults;
use Drupal\swiper\SwiperInterface;

/**
 * Defines the Swiper entity.
 *
 * @ConfigEntityType(
 *   id = "swiper",
 *   label = @Translation("Swiper options"),
 *   handlers = {
 *     "list_builder" = "Drupal\swiper\Controller\SwiperListBuilder",
 *     "form" = {
 *       "add" = "Drupal\swiper\Form\SwiperForm",
 *       "edit" = "Drupal\swiper\Form\SwiperForm",
 *       "delete" = "Drupal\swiper\Form\SwiperDeleteForm"
 *     }
 *   },
 *   config_prefix = "options",
 *   admin_permission = "administer swiper",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "status" = "status"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/media/swiper/{swiper}",
 *     "edit-form" = "/admin/config/media/swiper/{swiper}/edit",
 *     "enable" = "/admin/config/media/swiper/{swiper}/enable",
 *     "disable" = "/admin/config/media/swiper/{swiper}/disable",
 *     "delete-form" = "/admin/config/media/swiper/{swiper}/delete",
 *     "collection" = "/admin/config/media/swiper"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "options",
 *   }
 * )
 */
class Swiper extends ConfigEntityBase implements SwiperInterface {
  /**
   * The Swiper options ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Swiper options label.
   *
   * @var string
   */
  protected $label;

  /**
   * The Swiper options options.
   *
   * @var array
   */
  protected $options = [];

  /**
   * {@inheritdoc}
   */
  public function getOptions($strict = FALSE) {
    if ($strict) {
      $options = $this->options;
      if (isset($options['controlNav']) && $options['controlNav'] != 'thumbnails') {
        $options['controlNav'] = boolval($options['controlNav']);
      }
      return $options;
    }
    else {
      return $this->options;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setOptions(array $options) {
    $this->options = $options;
  }

  /**
   * {@inheritdoc}
   */
  public function getOption($name) {
    return isset($this->options[$name]) ? $this->options[$name] : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(array $values = []) {
    $swiper = parent::create($values);
    // Merge options with default options.
    $default_options = SwiperDefaults::defaultOptions();
    $swiper->setOptions($swiper->getOptions() + $default_options);
    return $swiper;
  }

}
