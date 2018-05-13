<?php

namespace Drupal\swiper\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\swiper\Entity\Swiper;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Route controller class for the swiper module options configuration.
 */
class SwiperOptionsController extends ControllerBase {

  /**
   * Enables a Swiper object.
   *
   * @param \Drupal\swiper\Entity\Swiper $swiper
   *   The Swiper object to enable.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   A redirect response to the Swiper options listing page.
   */
  public function enable(Swiper $swiper) {
    $swiper->enable()->save();
    return new RedirectResponse($swiper->url('collection', ['absolute' => TRUE]));
  }

  /**
   * Disables an Swiper object.
   *
   * @param \Drupal\swiper\Entity\Swiper $swiper
   *   The Swiper object to disable.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   A redirect response to the Swiper options listing page.
   */
  public function disable(Swiper $swiper) {
    $swiper->disable()->save();
    return new RedirectResponse($swiper->url('collection', ['absolute' => TRUE]));
  }

}
