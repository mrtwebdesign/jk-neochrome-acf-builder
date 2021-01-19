<?php defined('ABSPATH') || exit;

final class ACF_Builder
{

    private static $_instance = null;

    public function __construct()
    {

        add_action('init', [$this, 'init']);

        add_action('admin_head', [$this, 'enqueue_admin_styles']);

    }

    public function enqueue_admin_styles()
    {

        if (!wp_style_is('acf-repeater-builder-css')):

            wp_enqueue_style('acf-repeater-builder-css', get_template_directory_uri() . '/inc/assets/css/acf_repeater_builder.css');

        endif;

    }

    public static function instance()
    {

        if (is_null(self::$_instance)) :

            self::$_instance = new self();

        endif;

        return self::$_instance;

    }

    public function init()
    {

        if (function_exists('acf_add_local_field_group')):

            acf_add_local_field_group(array(
                'key' => 'group_repeater_builder_settings',
                'title' => __('Landing Page Builder', 'glekk'),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'page',
                        ),
                    ),
                ),
                'position' => 'normal',
            ));

        endif;

        if (function_exists('acf_add_local_field')):

            acf_add_local_field(array(
                'key' => 'field_repeater_builder_toggle',
                'label' => esc_html('Enable/Disable Landing Page Builder'),
                'name' => 'option_repeater_builder_toggle',
                'type' => 'true_false',
                'required' => 0,
                'conditional_logic' => 0,
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => esc_html('Enable'),
                'ui_off_text' => esc_html('Disable'),
                'parent' => 'group_repeater_builder_settings',
            ));

            acf_add_local_field(array(
                'key' => 'field_repeater_builder',
                'label' => esc_html('Landing Page Builder'),
                'name' => 'option_repeater_builder',
                'type' => 'repeater',
                'label_placement' => 'top',
                'instructions' => esc_html('Build your own landing page'),
                'required' => 0,
                'min' => 1,
                'max' => 0,
                'layout' => 'block',
                'button_label' => esc_html('Add new Section'),
                'parent' => 'group_repeater_builder_settings',
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_repeater_builder_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ),
                    ),
                ),
                'sub_fields' => array(
                    array(
                        'key' => 'field_builder_section_type',
                        'label' => esc_html('Section Type'),
                        'name' => 'option_builder_section_type',
                        'type' => 'select',
                        'instructions' => esc_html('Select the section type'),
                        'required' => 0,
                        'conditional_logic' => 0,
                        'choices' => array(
                            'hero-section' => esc_html('Hero Section'),
                            'buy-now-get-more-info-section' => esc_html('Buy Now / Get More Info'),
                            'gallery-section' => esc_html('Gallery'),
                            'faqs-section' => esc_html('FAQs'),
                            'rich-text-row-section' => esc_html('Rich Text Row'),
                            'footer-section' => esc_html('Footer'),
                        ),
                        'default_value' => 'rich-text-row-section',
                        'layout' => 'horizontal',
                        'return_format' => 'value',
                    ),

                    /* HERO SECTION START */

                    array(
                        'key' => 'field_builder_section_hero_type_of_bg',
                        'label' => esc_html('Type of Background'),
                        'name' => 'option_builder_section_hero_type_of_bg',
                        'instructions' => esc_html('Select the type of Hero Header'),
                        'type' => 'button_group',
                        'required' => 0,
                        'choices' => array(
                            'color' => esc_html('Color'),
                            'image' => esc_html('Image'),
                        ),
                        'default_value' => 'image',
                        'layout' => 'horizontal',
                        'return_format' => 'value',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_bg_color',
                        'label' => esc_html('Background Color'),
                        'name' => 'option_builder_section_hero_bg_color',
                        'instructions' => esc_html('Select the Hero Header Background Color'),
                        'type' => 'color_picker',
                        'default_value' => '#ffffff',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_hero_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'color',
                                ),
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_bg_image',
                        'label' => esc_html('Background Image'),
                        'name' => 'option_builder_section_hero_bg_image',
                        'instructions' => esc_html('Select the Hero Header Background Image'),
                        'type' => 'image',
                        'return_format' => 'id',
                        'preview_size' => 'medium',
                        'library' => 'all',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_hero_type_of_bg',
                                    'operator' => '==',
                                    'value' => 'image',
                                ),
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_main_headline',
                        'label' => esc_html('Main Headline'),
                        'name' => 'option_builder_section_hero_headline',
                        'instructions' => esc_html('Input the Hero Main Headline'),
                        'type' => 'text',
                        'placeholder' => esc_html('Main Headline'),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_sub_headline',
                        'label' => esc_html('Sub-Headline'),
                        'name' => 'option_builder_section_hero_sub_headline',
                        'instructions' => esc_html('Input the Sub-Headline'),
                        'type' => 'text',
                        'placeholder' => esc_html('Sub-Headline'),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_secondary_headline',
                        'label' => esc_html('Secondary Headline'),
                        'name' => 'option_builder_section_hero_secondary_headline',
                        'instructions' => esc_html('Input the Secondary Headline'),
                        'type' => 'text',
                        'placeholder' => esc_html('Secondary Headline'),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                            ),
                        ),
                    ),
                    array(
                        'key' => 'field_builder_section_hero_propositions',
                        'label' => esc_html('Propositions'),
                        'name' => 'option_builder_section_hero_propositions',
                        'type' => 'repeater',
                        'label_placement' => 'top',
                        'instructions' => esc_html('Add Propositions'),
                        'required' => 0,
                        'min' => 1,
                        'max' => 0,
                        'layout' => 'row',
                        'button_label' => esc_html('Add new Proposition'),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'hero-section',
                                ),
                            ),
                        ),
                        'sub_fields' => array(
                            array(
                                'key' => 'field_builder_section_hero_proposition_title',
                                'label' => esc_html('Proposition Title'),
                                'name' => 'option_builder_section_hero_proposition_title',
                                'instructions' => esc_html('Input the Proposition Title'),
                                'placeholder' => esc_html('Proposition Title'),
                                'type' => 'text',
                            ),
                        )
                    ),

                    /* HERO SECTION END */

                    /* **************************************** */

                    /* BUY NOW / GET MORE INFO SECTION START */

                    array(
                        'key' => 'field_builder_section_buy_now_',
                        'label' => esc_html('Buy Now / Get More Info Section Field'),
                        'name' => 'option_builder_section_buy_now_',
                        'type' => 'text',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'buy-now-get-more-info-section',
                                ),
                            ),
                        ),
                    ),

                    /* BUY NOW / GET MORE INFO SECTION END */

                    /* **************************************** */

                    /* GALLERY SECTION START */

                    array(
                        'key' => 'field_builder_section_gallery_',
                        'label' => esc_html('Gallery Section Field'),
                        'name' => 'option_builder_section_gallery_',
                        'type' => 'text',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'gallery-section',
                                ),
                            ),
                        ),
                    ),

                    /* GALLERY SECTION END */

                    /* **************************************** */

                    /* FAQS SECTION START */

                    array(
                        'key' => 'field_builder_section_faqs_',
                        'label' => esc_html('FAQs Section Field'),
                        'name' => 'option_builder_section_faqs_',
                        'type' => 'text',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'faqs-section',
                                ),
                            ),
                        ),
                    ),

                    /* FAQS SECTION END */

                    /* **************************************** */

                    /* RICH TEXT ROW SECTION START */

                    array(
                        'key' => 'field_builder_section_rich_text_row_',
                        'label' => esc_html('Rich Text Row Section Field'),
                        'name' => 'option_builder_section_rich_text_row_',
                        'type' => 'text',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'rich-text-row-section',
                                ),
                            ),
                        ),
                    ),

                    /* RICH TEXT ROW SECTION END */

                    /* **************************************** */

                    /* FOOTER SECTION START */

                    array(
                        'key' => 'field_builder_section_footer_',
                        'label' => esc_html('Footer Section Field'),
                        'name' => 'option_builder_section_footer_',
                        'type' => 'text',
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_builder_section_type',
                                    'operator' => '==',
                                    'value' => 'footer-section',
                                ),
                            ),
                        ),
                    ),

                    /* FOOTER SECTION END */

                ),
            ));

        endif;

    }

}

ACF_Builder::instance();
