<?php get_header();
if (is_restricted()) {
    show_404();
} else {
?>
    <div id="home-page-content" class="page-content">
        <?php
        if (have_posts()) {
            while (have_posts()) {
                the_post();
                the_content();
            }
        }
        ?>
        <div id="home-page-news-feed">
            <?php
            $recent_posts = wp_get_recent_posts(['category' => 50, 'post_status' => 'publish']);
            echo "<h2>Announcements</h2>";
            echo '<ul id="home-page-news-items">';
            $view_previous_prefix = '';
            if (!empty($recent_posts)) {
                foreach ($recent_posts as $recent) {
                    echo '<li class="home-page-news-item">';
                    echo '<a href="' . get_permalink($recent["ID"]) . '">' . $recent["post_title"] . '</a>';
                    echo '</li>';
                }
            } else {
                $view_previous_prefix = "There are no current announcements. ";
            }
            echo '<li>';
            echo '<a href="/category/front-page/old-announcements/">' . $view_previous_prefix . 'View previous announcements ...' . '</a>';
            echo '</li>';
            echo '</ul>';
            wp_reset_query();
            ?>
        </div>
    </div>
    <?php
}
get_footer();
