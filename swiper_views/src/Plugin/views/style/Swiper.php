<?php

namespace Drupal\swiper_views\Plugin\views\style;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\style\StylePluginBase;

/**
 * Style plugin to render each item in an ordered or unordered list.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "swiper",
 *   title = @Translation("Swiper"),
 *   help = @Translation("Display the results in a Swiper widget."),
 *   theme = "swiper_views_style",
 *   theme_file = "swiper_views.theme.inc",
 *   display_types = {"normal"}
 * )
 */
class Swiper extends StylePluginBase
{
    /**
     * {@inheritdoc}
     */
    protected $usesRowPlugin = TRUE;

    /**
     * {@inheritdoc}
     */
    protected $usesFields = TRUE;

    /**
     * {@inheritdoc}
     */
    protected $usesOptions = TRUE;

    /**
     * {@inheritdoc}
     */
    public function evenEmpty()
    {
        return FALSE;
    }

    /**
     * {@inheritdoc}
     */
    protected function defineOptions()
    {
        $options = parent::defineOptions();
        $options['options'] = ['default' => 'default'];
        $options['id'] = ['default' => ''];
        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function buildOptionsForm(&$form, FormStateInterface $form_state)
    {
        parent::buildOptionsForm($form, $form_state);

        $form['swiper'] = [
            '#type' => 'fieldset',
            '#title' => $this->t('Swiper'),
        ];

        $form['swiper']['options'] = [
            '#title' => t('Options'),
            '#type' => 'select',
            '#options' => swiper_options_list(),
            '#default_value' => $this->options['options'],
        ];

        $form['swiper']['id'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Element ID'),
            '#description' => $this->t("<p>Manually define the Swiper container ID attribute <em>Ensure you don't display similar ID elements on the same page</em>.</p>"),
            '#size' => 40,
            '#maxlength' => 255,
            '#default_value' => $this->options['id'],
        ];

        $form['swiper']['navButtons'] = [
            '#title' => t('Enable Prev/Next Buttons'),
            '#type' => 'checkbox',
            '#default_value' => $this->options['navButtons'],
        ];
        $form['swiper']['nextButton'] = [
            '#title' => t('Enter Next Buttons CSS selector'),
            '#type' => 'textfield',
            '#size' => 40,
            '#placeholder' => t('.swiper-next-button'),
            '#default_value' => $this->options['nextButton'] ? $this->options['nextButton'] : '.swiper-next-button',
        ];

        $form['swiper']['prevButton'] = [
            '#title' => t('Enter Prev Buttons CSS selector'),
            '#type' => 'textfield',
            '#size' => 40,
            '#placeholder' => t('.swiper-prev-button'),
            '#default_value' => $this->options['prevButton'] ?? $this->options['prevButton'],
            '#description' => $this->t("<p>Define the Swiper CSS Class for the <b>Prev/Next</b> Buttons above, <em>Ensure you don't display similar ID elements on the same page</em></p>."),

        ];

        $form['swiper']['scrollbar'] = [
            '#title' => t('Enable Scrollbar'),
            '#type' => 'checkbox',
            '#default_value' => $this->options['scrollbar'],
        ];

        $form['swiper']['scrollbarClass'] = [
            '#title' => t('Enter Scrollbar CSS selector'),
            '#placeholder' => t('.swiper-scrollbar'),
            '#type' => 'textfield',
            '#size' => 40,
            '#default_value' => $this->options['scrollbarClass'] ? $this->options['scrollbarClass'] : '.swiper-srcollbar' ,
            '#description' => $this->t("<p>Define the Swiper CSS Class for the <b>Scrollbar</b> Buttons above, <em>Ensure you don't display similar ID elements on the same page</em></p>."),

        ];

        $form['swiper']['pagination'] = [
            '#title' => t('Enable Pagination'),
            '#type' => 'checkbox',
            '#default_value' => $this->options['pagination'],
        ];

        $form['swiper']['paginationClass'] = [
            '#title' => t('Enter Scrollbar CSS selector'),
            '#placeholder' => t('.swiper-pagination'),
            '#type' => 'textfield',
            '#size' => 40,
            '#default_value' => $this->options['paginationClass'] ? $this->options['paginationClass'] : '.swiper-pagination' ,
            '#description' => $this->t("<p>Define the Swiper CSS Class for the <b>Pagination</b> Buttons above, <em>Ensure you don't display similar ID elements on the same page</em></p>."),

        ];

    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {

// Group the rows according to the grouping field, if specified.
        $sets = parent::render();

// Render each group separately and concatenate.
        $output = $sets;

        foreach ($sets as $key => &$set) {
            $output[$key] = [
                '#theme' => $this->themeFunctions(),
                '#view' => $this->view,
                '#options' => $this->options,
                '#rows' => $set['#rows'],
                '#title' => $set['#title'],
            ];
        }

        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function submitOptionsForm(&$form, FormStateInterface $form_state)
    {
        parent::submitOptionsForm($form, $form_state);

        /* Move swiper options to the parent array so that
        * values are saved properly.
        * Original: values['style_options']['swiper'] =
        *   ['options', 'caption', 'id'].
        */
        $swiper_options = $form_state->getValue(['style_options', 'swiper']);

// Edit:  values['style_options'] += ['options', 'caption', 'id'].
        foreach ($swiper_options as $key => $value) {
            $form_state->setValue(['style_options', $key], $value);
        }
// Edit:  values['style_options']['swiper'] = NULL.
        $form_state->setValue(['style_options', 'swiper'], NULL);
    }

}
