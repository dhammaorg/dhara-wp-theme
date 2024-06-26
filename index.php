<?php
get_header();
if (is_restricted()) {
    show_404();
} else {
    ?>
    <div id="page-<?php echo get_the_ID(); ?>-content" class="page-content">
        <!--Begin Content-->
        <?php
        if (have_posts()) {
            while (have_posts()) {
                the_post();
                ?>

                <h1><?php the_title(); ?></h1>
                <?php the_content(__('(more...)'));
            }
        }
        ?>
        <!--End Content-->
    </div>
    <?php
}
get_footer();
