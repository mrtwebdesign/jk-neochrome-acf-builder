<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astra
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!ACF_Builder::get_field('field_repeater_builder_toggle')):

    ?>
    <?php astra_content_bottom(); ?>
    </div> <!-- ast-container -->
    </div><!-- #content -->
    <?php
    astra_content_after();

    astra_footer_before();

    astra_footer();

    astra_footer_after();
    ?>
    </div><!-- #page -->

<?php endif; ?>

<?php
astra_body_bottom();
wp_footer();
?>
</body>
</html>
