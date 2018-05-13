<?php

namespace Drupal\swiper;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Swiper options entities.
 */
interface SwiperInterface extends ConfigEntityInterface {

  /**
   * Returns the array of swiper library options.
   *
   * @param bool $strict
   *   Use strict typecasting, as defined by the swiper library.
   *   This fixes the typecasting of options that we defined
   *   differently in the schema.
   *
   * @return array
   *   The array of options.
   */
  public function getOptions($strict = FALSE);

  /**
   * Returns the value of a swiper library option.
   *
   * @param string $name
   *   The option name.
   *
   * @return mixed
   *   The option value.
   */
  public function getOption($name);

  /**
   * Sets the swiper library options array.
   *
   * @param array $options
   *   New/updated array of options.
   */
  public function setOptions(array $options);

}
