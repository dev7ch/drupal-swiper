<?php

namespace Drupal\swiper\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\swiper\SwiperDefaults;

/**
 * Class SwiperForm.
 *
 * @package Drupal\swiper\Form
 */
class SwiperForm extends EntityForm
{

    /**
     * {@inheritdoc}
     */
    public function form(array $form, FormStateInterface $form_state)
    {
        $form = parent::form($form, $form_state);

        $swiper = $this->entity;
        $options = $swiper->getOptions();
        $default_options = SwiperDefaults::defaultOptions();

        $form['label'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Label'),
            '#maxlength' => 255,
            '#default_value' => $swiper->label(),
            '#description' => $this->t('A human-readable title for this option set.'),
            '#required' => TRUE,
        ];

        $form['id'] = [
            '#type' => 'machine_name',
            '#default_value' => $swiper->id(),
            '#machine_name' => [
                'exists' => '\Drupal\swiper\Entity\Swiper::load',
            ],
            '#disabled' => !$swiper->isNew(),
        ];

        // Options Vertical Tab Group table.
        // @TODO Add more options.
        $form['tabs'] = [
            '#type' => 'vertical_tabs',
        ];

        // General Slideshow and Animiation Settings.
        $form['general_params'] = [
            '#type' => 'details',
            '#title' => $this->t('General Swiper params'),
            //'#group' => 'tabs',
            '#open' => TRUE,
        ];

        $form['general_params']['loop'] = [
            '#type' => 'checkbox',
            '#title' => 'Loop slider',
            '#default_value' => $options['loop'] ?? $default_options['loop'],
        ];

        $form['general_params']['effect'] = [
            '#type' => 'select',
            '#title' => $this->t('effect'),
            '#description' => $this->t("Tranisition effect. Could be \"slide\", \"fade\", \"cube\", \"coverflow\" or \"flip\""),
            '#options' => [
                'slide' => $this->t('Slide'),
                'fade' => $this->t('Fade'),
                'cube' => $this->t('Cube'),
                'coverflow' => $this->t('Coverflow'),
                'flip' => $this->t('Flip'),
            ],
            '#default_value' => $options['effect'] ?? $default_options['direction'],
        ];

        $form['general_params']['direction'] = [
            '#type' => 'select',
            '#title' => $this->t('Direction'),
            '#description' => $this->t("Select direction. Could be 'horizontal' or 'vertical' (for vertical slider)."),
            '#options' => [
                'horizontal' => $this->t('Horizontal'),
                'vertical' => $this->t('Vertical'),
            ],
            '#default_value' => $options['direction'] ?? $default_options['direction'],
        ];

        $form['general_params']['speed'] = array(
            '#type' => 'number',
            '#title' => $this->t('Speed'),
            '#description' => $this->t('Duration of transition between slides (in ms).'),
            // Only positive numbers.
            '#min' => 0,
            // Only integers.
            '#step' => 1,
            '#default_value' => $options['speed'] ?? $default_options['speed'],
        );

        $form['general_params']['prevButton'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Prev Button Selector CSS Class'),
            '#description' => $this->t('Duration of transition between slides (in ms).'),
            '#default_value' => $options['prevButton'] ? $options['prevButton'] : false,
        );

        $form['general_params']['nextButton'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Prev Button Selector '),
            '#default_value' => $options['nextButton'] ? $options['nextButton'] : false,
        );

        $form['general_params']['paginationClass'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Prev Pagination Selector CSS Class'),
            '#default_value' => $options['paginationClass'] ?? $options['paginationClass'],
        );

        $form['general_params']['scrollbarClass'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Prev Scrollbar Selector CSS Class'),
            '#default_value' => $options['scrollbarClass'] ?? $options['scrollbarClass'],
        );

/*        $form['general_params']['navButtons'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('Enable Nav Buttons'),
            '#default_value' => isset($options['navButtons']) ? $options['navButtons'] : false,
        ];*/


        $form['general_params']['initialSlide'] = array(
            '#type' => 'number',
            '#title' => $this->t('Initial Slide'),
            '#description' => $this->t('Select the starting slide'),
            // Only positive numbers.
            '#min' => 1,
            // Only integers.
            '#step' => 1,
            '#default_value' => $options['initialSlide'] ?? $default_options['initialSlide'],
        );

        $form['general_params']['keyboardControl'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('Enable Keyboard control'),
            '#default_value' => $options['keyboardControl'] ?? $default_options['keyboardControl'],
        ];


        $form['general_params']['mousewheelControl'] = [
            '#type' => 'checkbox',
            '#title' => $this->t('Enable mousewheel control'),
            '#default_value' => $options['mousewheelControl'] ?? $default_options['mousewheelControl'],
        ];
        // Advanced Options.
        $form['advanced'] = [
            '#type' => 'details',
            '#title' => $this->t('Advanced Options'),
            '#group' => 'tabs',
        ];

        $form['advanced']['namespace'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Namespace'),
            '#description' => $this->t('Prefix string attached to the classes of all elements generated by the plugin.'),
            '#size' => 40,
            '#maxlength' => 255,
            '#element_validate' => ['::validateNamespace'],
            '#default_value' => isset($options['namespace']) ? $options['namespace'] : $default_options['namespace'],
        ];

        $form['advanced']['coverflowEffect'] = [
            '#type' => 'fieldset',
            '#title' => 'Coverflow',
            '#description' => 'Add your configs here',
            '#tree' => true,
            '#weight' => 30
        ];
        $form['advanced']['coverflowEffect']['slideShadows'] = [
            '#type' => 'select',
            '#title' => 'Enables slides shadows',
            '#options' => [
                true => $this->t('True'),
                false => $this->t('False'),
            ],

            '#default_value' => $options['coverflowEffect']['slideShadows'] ?? $default_options['coverflowEffect']['slideShadows'],
        ];

        $form['advanced']['coverflowEffect']['rotate'] = [
            '#type' => 'number',
            '#title' => 'Slide rotate in degrees',
            '#default_value' => $options['coverflowEffect']['rotate'] ?? $default_options['coverflowEffect']['rotate'],
        ];

        $form['advanced']['coverflowEffect']['stretch'] = [
            '#type' => 'number',
            '#title' => 'Stretch space between slides (in px)',
            '#default_value' => $options['coverflowEffect']['stretch'] ?? $default_options['coverflowEffect']['stretch'],
        ];

        $form['advanced']['coverflowEffect']['depth'] = [
            '#type' => 'number',
            '#title' => 'Depth offset in px (slides translate in Z axis)',
            '#default_value' => $options['coverflowEffect']['depth'] ?? $default_options['coverflowEffect']['depth'],
        ];

        $form['advanced']['coverflowEffect']['modifier'] = [
            '#type' => 'number',
            '#title' => 'Effect multipler',
            '#default_value' => $options['coverflowEffect']['modifier'] ?? $default_options['coverflowEffect']['modifier'],
        ];

        # Advanced Settings

        $form['advanced']['visibilityFullFit'] = [
            '#type' => 'checkbox',
            '#title' => 'Visibilty Full Fit',
            '#default_value' => $options['visibilityFullFit'] ?? true
        ];

        $form['advanced']['autoResize'] = [
            '#type' => 'checkbox',
            '#title' => 'Auto resize',
            '#default_value' => $options['autoResize'] ?? true
        ];

        // Grid Options.
        $form['grid'] = [
            '#type' => 'details',
            '#title' => $this->t('Grid Options'),
            '#group' => 'tabs',
        ];

        $form['grid']['spaceBetween'] = array(
            '#type' => 'number',
            '#title' => $this->t('Space Between'),
            '#description' => $this->t('Distance between slides in px.'),
            // Only integers.
            '#step' => .1,
            '#default_value' => $options['spaceBetween'] ?? $default_options['spaceBetween'],
        );

        $form['grid']['slidesPerView'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Slider per View'),
            '#description' => $this->t('Number of slides per view (slides visible at the same time on slider\'s container).<br/>
          <small>If you use it with "auto" value and along with loop: true then you need to specify loopedSlides parameter with amount of slides to loop (duplicate)</small><br/>
          <small>slidesPerView \'auto\' is currently not compatible with multi row mode, when slidesPerColumn.</small>'),
            // Only integers.
            '#default_value' => $options['slidesPerView'] ?? $default_options['slidesPerView'],
        );
        $form['grid']['sliderPerColumn'] = array(
            '#type' => 'select',
            '#title' => $this->t('Slides per Column'),
            '#description' => $this->t('Number of slides per column, for multirow layout.'),
            '#options' => [
                'column' => $this->t('Colum'),
                'row' => $this->t('Row'),
            ],
            '#default_value' => $options['sliderPerColumn'] ?? $default_options['sliderPerColumn'],
        );
        $form['grid']['sliderPerColumnFill'] = array(
            '#type' => 'number',
            '#title' => $this->t('Slides per Column fill'),
            '#description' => $this->t('Could be \'column\' or \'row\'. Defines how slides should fill rows, by column or by row'),
            // Only integers.
            '#step' => 1,
            '#default_value' => $options['sliderPerColumnFill'] ? $default_options['sliderPerColumnFill'] : false,
        );
        $form['grid']['sliderPerGroup'] = array(
            '#type' => 'number',
            '#title' => $this->t('Slides per Group'),
            '#description' => $this->t('Set numbers of slides to define and enable group sliding. Useful to use with slidesPerView > 1'),
            // Only integers.
            '#step' => 1,
            '#default_value' => $options['sliderPerGroup'] ? $default_options['sliderPerGroup'] : false,
        );
        $form['grid']['centeredSlides'] = array(
            '#type' => 'checkbox',
            '#title' => $this->t('Slides centered'),
            '#default_value' => $options['centeredSlides'] ? $options['centeredSlides'] : false,
        );

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $form, FormStateInterface $form_state)
    {
        /** @var \Drupal\swiper\Entity\Swiper $swiper */
        $swiper = $this->entity;

        $status = parent::save($form, $form_state);

        switch ($status) {
            case SAVED_NEW:
                drupal_set_message($this->t('Created the %label Swiper options.', [
                    '%label' => $swiper->label(),
                ]));
                break;

            default:
                drupal_set_message($this->t('Saved the %label Swiper options.', [
                    '%label' => $swiper->label(),
                ]));
        }
        $form_state->setRedirectUrl($swiper->toUrl('collection'));
    }

    /**
     * {@inheritdoc}
     */
    protected function copyFormValuesToEntity(EntityInterface $entity, array $form, FormStateInterface $form_state)
    {
        $options = [];
        $values = $form_state->getValues();
        foreach ($values as $key => $value) {
            if (in_array($key, ['id', 'label'])) {
                $entity->set($key, $value);
            } else {
                $options[$key] = $value;
            }
        }
        $entity->set('options', $options);
    }

    /**
     * {@inheritdoc}
     */
    protected function actions(array $form, FormStateInterface $form_state)
    {
        $actions = parent::actions($form, $form_state);
        // Prevent access to delete button when editing default configuration.
        if ($this->entity->id() == 'default' && isset($actions['delete'])) {
            $actions['delete']['#access'] = FALSE;
        }
        return $actions;
    }

    /**
     * Validation functions.
     */
    public function validateNamespace(array &$element, FormStateInterface $form_state)
    {
        // @todo
        // @see form_error()
        return TRUE;
    }

    /**
     * Validation functions.
     */
    public function validateSelector(array &$element, FormStateInterface $form_state)
    {
        // @todo
        // @see form_error()
        return TRUE;
    }

    /**
     * Validate the correct version for thumbnail options.
     */
    public function validateMinimumVersion22(array &$element, FormStateInterface $form_state)
    {
        $lib = libraries_detect('swiper');
        if (!isset($lib['version'])) {
            drupal_set_message($this->t('Unable to detect Swiper library version. Some options may not function properly. Please review the README.md file for installation instructions.'), 'warning');
        } else {
            $version = $lib['version'];
            $required = "2.2";
            if ($element['#value'] && !version_compare($version, $required, '>=')) {
                $form_state->setError($element, $this->t('To use %name you must install Swiper version !required or higher.', [
                    '%name' => $element['#title'],
                    '!required' => Link::fromTextAndUrl($required, Url::fromUri('https://github.com/woothemes/Swiper/tree/version/2.2')),
                ]));
            }
        }
    }

    /**
     * Validate thumbnail option values.
     *
     * Empties the value of the thumbnail caption option when the paging control
     * is not set to thumbnails.
     *
     * @param array $element
     *   The element to validate.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The form state.
     */
    public function validateThumbnailOptions(array &$element, FormStateInterface $form_state)
    {
        if ($form_state->getValue('controlNav') !== 'thumbnails' && $element['#value']) {
            $form_state->setValueForElement($element, '');
        }
    }

}
