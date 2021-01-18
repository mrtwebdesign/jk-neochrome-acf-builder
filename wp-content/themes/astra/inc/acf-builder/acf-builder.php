<?php defined('ABSPATH') || exit;

final class ACF_Builder
{

    private static $_instance = null;

    public function __construct()
    {

        add_action('init', [$this, 'init']);

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
                'title' => __('Repeater Builder', 'glekk'),
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
                'label' => esc_html__('Enable/Disable Landing Page Builder', 'neochrome'),
                'name' => 'option_repeater_builder_toggle',
                'type' => 'true_false',
                'required' => 0,
                'conditional_logic' => 0,
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => esc_html__('Enable', 'neochrome'),
                'ui_off_text' => esc_html__('Disable', 'neochrome'),
                'parent' => 'group_repeater_builder_settings',
            ));

            acf_add_local_field(array(
                'key' => 'field_repeater_builder',
                'label' => esc_html__('Landing Page Builder', 'neochrome'),
                'name' => 'option_repeater_builder',
                'type' => 'repeater',
                'label_placement' => 'top',
                'instructions' => esc_html__('Build your own landing page', 'neochrome'),
                'required' => 0,
                'min' => 0,
                'max' => 0,
                'layout' => 'block',
                'button_label' => esc_html__('Add new Section', 'neochrome'),
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
                        'label' => esc_html__('Section Type', 'neochrome'),
                        'name' => 'option_builder_section_type',
                        'type' => 'select',
                        'instructions' => esc_html__('Select section type', 'neochrome'),
                        'required' => 0,
                        'conditional_logic' => 0,
                        'choices' => array(
                            'section-test' => esc_html__('Test Section 1'),
                            'section-test2' => esc_html__('Test Section 2'),
                            'section-test3' => esc_html__('Test Section 3'),
                            'section-test4' => esc_html__('Test Section 4'),
                        ),
                        'default_value' => 'section-test',
                        'layout' => 'horizontal',
                        'return_format' => 'value',
                    ),
                ),
            ));

        endif;

    }

}

ACF_Builder::instance();
