/**
 * @file
 * Loads the Swiper library.
 */

(function ($) {

  'use strict';

  Drupal.behaviors.swiper = {
    attach: function (context, settings) {
      var sliders = [];
      var id;
      if ($.type(settings.swiper) !== 'undefined'
        && $.type(settings.swiper.instances) !== 'undefined') {

        for (id in settings.swiper.instances) {

          if (settings.swiper.instances.hasOwnProperty(id)) {
            if ($.type(settings.swiper.options) !== 'undefined'
              && settings.swiper.instances[id] in settings.swiper.options) {
              sliders[id] = settings.swiper.options[settings.swiper.instances[id]];
            }
          }
        }
      }
      // Slider set.
      for (id in sliders) {
        if (sliders.hasOwnProperty(id)) {
          _swiper_init(id, settings.swiper.options[settings.swiper.instances[id]], context);
        }
      }
    }
  };

  /**
   * Initialize the swiper instance.
   *
   * @param {string} id
   * Id selector of the swiper object.
   * @param {object} options
   * The options to apply to the swiper object.
   * @param {object} context
   * The DOM context.
     * @private
     */
  function _swiper_init(id, options, context) {
    $('#' + id, context).once('swiper').each(function () {
      if (options) {
        new Swiper('#' + id, options);
      }
      else {
        new Swiper('#' + id);
      }
    });
  }

}(jQuery));
