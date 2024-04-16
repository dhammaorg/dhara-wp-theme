<?php

function set_archive_page_post_count($wp_query) {
    $wp_query->query_vars['posts_per_page'] = 1000;
}

add_filter('pre_get_posts', 'set_archive_page_post_count');

//Register the permissions metabox for posts and pages
function add_perms_box($post) {
    $screens = ['post', 'page', 'wporg_cpt'];
    foreach ($screens as $screen) {
        add_meta_box(
                'dhamma_perms_box', // Unique ID
                'Permissions', // Box title
                'perms_box_html', // Content callback, must be of type callable
                $screens            // Post type
        );
    }
}

function perms_box_html() {
    $value = get_post_meta(get_the_ID(), "dhamma_perms", true);
    ?>
    <select name="dhamma_perms" id="dhamma_perms" class="postbox">
        <option value="public" <?php selected($value, 'public'); ?>>Public</option>
        <option value="oldstudents" <?php selected($value, 'oldstudents'); ?>>Old Students Only</option>
        <option value="workers" <?php selected($value, 'workers'); ?>>Dhamma Workers Only</option>
    </select>
    <?php
}

add_action('add_meta_boxes', 'add_perms_box');

function dhamma_meta_save($post_id) {
    // Checks save status
    $is_autosave = wp_is_post_autosave($post_id);
    $is_revision = wp_is_post_revision($post_id);

    // Exits script depending on save status
    if ($is_autosave || $is_revision) {
        return;
    }

    // Checks for input and sanitizes/saves if needed
    if (isset($_POST['dhamma_perms'])) {
        update_post_meta($post_id, 'dhamma_perms', $_POST['dhamma_perms']);
    }
}

add_action('save_post', 'dhamma_meta_save');

function is_dev() {
  $url = parse_url( get_site_url(), PHP_URL_HOST );
  returns str_contains( $url, ".dev." ); // is URL nyus.dev.webhost2.dhamma.org or dhara.dev.webhost2.dhamma.org ?
}

function is_restricted() {
    return item_restricted(get_post(), wp_get_current_user());
}

function item_restricted($page, $user) {

    if (null == $page || null == $user) {
        return false; // JJD
    }
    $dhamma_perms = get_post_meta($page->ID, "dhamma_perms", true);
    $requires_os = $dhamma_perms == "oldstudents";
    $requires_worker = $dhamma_perms == "workers";
    if (!is_user_logged_in()) {
        return $requires_os || $requires_worker;
    } else if (get_userdata($user->ID)->user_login == "oldstudent") {
        return $requires_worker;
    } else {
        //dhammaworkers and all named users have full read access
        return false;
    }
}

//Register the New Student and Old Student Menus
function register_all_menu() {
    register_nav_menus([
        'ns-menu' => 'New Student Menu',
        'os-menu' => 'Old Student Menu'
    ]);
}

add_action('init', 'register_all_menu');

//Customize the wp-login.php page (only shown during a failed login at mobile menu)
function my_login_logo_url() {
    return home_url();
}

add_filter('login_headerurl', 'my_login_logo_url');

function my_login_logo_url_title() {
    return 'Dharā Dhamma';
}

add_filter('login_headertitle', 'my_login_logo_url_title');

function my_login_logo() {
    ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/aniwheel.gif );
            background-size: 80px 80px;
        }

        body.login div#login_error a {
            display: none;
        }

        body.login p#nav {
            display: none;
        }
    </style>
    <?php
}

add_action('login_enqueue_scripts', 'my_login_logo');

// Local news feeds

add_shortcode('dhamma_news_feed', 'dhamma_news_feed');

function dhamma_news_feed($atts) {
    $retMe = "";
    $categoryID = ($atts["category"]);
    $recent_posts = wp_get_recent_posts(['category' => $categoryID, 'post_status' => 'publish']);
    if (!empty($recent_posts)) {
        // JJD 8/18/23 #6 handle missing "name" attr; some pages have "title" attr instead of "name"
        $name = $atts["name"] ?? ($atts["title"] ?? ''); 
        $retMe .= "<h2>$name News</h2>";
        $retMe .= '<ul class="local-page-news-items">';
        foreach ($recent_posts as $recent) {
            $retMe .= '<li class="home-page-news-item">';
            $retMe .= '<a href="' . get_permalink($recent["ID"]) . '">' . $recent["post_title"] . '</a>';
            $retMe .= '</li>';
        }
        $retMe .= '</ul>';
    }
    wp_reset_query();
    return $retMe;
}

//Useful functions for checking if we're on an os-page or we want the os-menu, for use in other places in theme
function prefers_os_menu($wp) {
    $current_url = home_url(add_query_arg([], $wp->request));
    if (preg_match("|/os|i", $current_url)) {
        return true;
    } else if (preg_match("|/category/|i", $current_url)) {
        $current_announcements_ID = 50;
        $old_announcements_ID = 49;
        $prefers_ns_categories = [$current_announcements_ID, $old_announcements_ID];
        // JJD 8/18/23 #6 handle null object
        $cat_ID = get_queried_object()->term_id ?? 'dummy';
        return !in_array($cat_ID, $prefers_ns_categories);
    } else if (is_single()) {
        return true;
    } else if (is_search()) {
        return true;
    } else if (is_restricted()) {
        return true;
    }
    return false;
}

/* Old students and webmasters should see only OS menu
   todo: rename this function to is_os_user  */
function is_os_page($wp) {
    $is_os_page = false;
    if (is_user_logged_in()) {
        $is_os_page = true;
    }
    return $is_os_page;
}

// This function returns the destination URL after a logout as a string.
function url_after_logout($wp) {
    $url = home_url();
    if (is_search()) {
        // Search is a good place to stay after logging out.
        // It only needs special handling because both
        // get_permalink() and weird about search.
        $url = get_search_link();
    } else if (is_category()) {
        $category = get_queried_object();
        $url = get_category_link($category->term_id);
    } else {
        $permalink = get_permalink();
        if ($permalink) {
            // Proper page/post with permalink. Stay there.
            $url = $permalink;
        }
    }
    return $url;
}

//Add current_page_ancestor to news top-level menu item
function cpt_archive_classes($classes, $item) {
    if (is_archive() || is_single()) {
        $classes = str_replace('menu-item-' . get_theme_mod('dhamma_news_menu_item_id'), 'menu-item-4942 current_page_ancestor', $classes);
    }
    return $classes;
}

add_filter('nav_menu_css_class', 'cpt_archive_classes', 10, 2);

//This removes the admin bar from everyone but admins.
add_action('init', 'remove_admin_bar');

function remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}

//This blocks non-administrative users from accessing the dashboard, redirects to home page instead
add_action('init', 'blockusers_init');

function blockusers_init() {
    if (is_admin() && !current_user_can('administrator') && !( defined('DOING_AJAX') && DOING_AJAX )) {
        wp_redirect(home_url());
        exit;
    }
}

/**
 * Improves the caption shortcode with HTML5 figure & figcaption; microdata & wai-aria attributes
 *
 * @param  string $val     Empty
 * @param  array  $attr    Shortcode attributes
 * @param  string $content Shortcode content
 * @return string          Shortcode output
 */
function dhamma_img_caption_shortcode_filter($val, $attr, $content = null) {
    extract(shortcode_atts([
        'id' => '',
        'align' => 'aligncenter',
        'width' => '',
        'caption' => ''
                    ], $attr));

    // No caption, no dice... But why width?
    if (1 > (int) $width || empty($caption))
        return $val;

    if ($id)
        $id = esc_attr($id);

    // Add itemprop="contentURL" to image - Ugly hack
    $content = str_replace('<img', '<img itemprop="contentURL"', $content);

    $retMe = "";

    if ($align == "aligncenter")
        $retMe .= "<div class='image-caption-center'>";
    else if ($align == "alignleft")
        $retMe .= "<div class='image-caption-left'>";
    else if ($align == "alignright")
        $retMe .= "<div class='image-caption-right'>";

    $retMe .= '<figure id="' . $id . '" aria-describedby="figcaption_' . $id . '" class="wp-caption ' . esc_attr($align) . '" itemscope itemtype="http://schema.org/ImageObject" style="width: ' . (0 + (int) $width) . 'px">' . do_shortcode($content) . '<figcaption id="figcaption_' . $id . '" class="wp-caption-text" itemprop="description">' . $caption . '</figcaption></figure>';

    $retMe .= "</div>";

    return $retMe;
}

add_filter('img_caption_shortcode', 'dhamma_img_caption_shortcode_filter', 10, 3);

//Customize the "[...]" read more link at the end of the_excerpt()
function new_excerpt_more($more) {
    return '<p><a class="read-more" href="' . get_permalink(get_the_ID()) . '">Read the rest of this entry »</a></p>';
}

add_filter('excerpt_more', 'new_excerpt_more');

/* Add theme customization interface to control panel */

function dhara_customize_register($wp_customize) {

    /**
     * Adds textarea support to the theme customizer
     */
    class dhara_textarea_control extends WP_Customize_Control {

        public $type = 'textarea';

        public function render_content() {
            ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea($this->value()); ?></textarea>
            </label>
            <?php
        }

    }

    $wp_customize->add_section(
            'dhara_customizer',
            [
                'title' => 'Theme Customization',
                'priority' => 199
            ]
    );

    $wp_customize->add_setting('dhamma_short_location', ['transport' => 'refresh']);
    $wp_customize->add_control('dhamma_short_location', ['section' => 'dhara_customizer', 'label' => 'Header Short Location', 'type' => 'text']);

    $wp_customize->add_setting('dhamma_schedule_link', ['transport' => 'refresh', 'sanitize_callback' => 'dhara_sanitize_uri']);
    $wp_customize->add_control('dhamma_schedule_link', ['section' => 'dhara_customizer', 'label' => 'Schedule Link (for mobile menu)', 'type' => 'text']);

    $wp_customize->add_setting('dhamma_picture_icon', ['transport' => 'refresh', 'sanitize_callback' => 'dhara_sanitize_uri']);
    $wp_customize->add_control('dhamma_picture_icon', ['section' => 'dhara_customizer', 'label' => 'Picture Icon URL (Top Left)', 'type' => 'text']);

    $wp_customize->add_setting('dhamma_news_menu_item_id', ['transport' => 'refresh']);
    $wp_customize->add_control('dhamma_news_menu_item_id', ['section' => 'dhara_customizer', 'label' => 'News Menu Item Post ID (For Current Page Menu Highlighting)', 'type' => 'text']);

    $wp_customize->add_setting('dhamma_title_separator', ['transport' => 'refresh', 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('dhamma_title_separator', ['section' => 'dhara_customizer', 'label' => 'Separator for Browser Title', 'type' => 'text']);
}

add_action('customize_register', 'dhara_customize_register');

function dhara_sanitize_uri($uri) {
    if ('' === $uri) {
        return '';
    }
    return esc_url_raw($uri);
}

function show_404() {
    ?>
    <div id="page-<?php echo get_the_ID(); ?>-content" class="page-content">
        <?php 
        if (is_restricted()) {
            if (wp_get_current_user()->ID == 6) {
                ?>
                <h1>This Page is for Dhamma Workers Only</h1>
                <!--If we're logged in as old student and the content is still restricted-->
                <p>This page is only available to dhamma workers and/or trustees, so those students can provide dhamma service to other students. If you are a dhamma worker and require access to this page, please <a href="mailto:info@dhara.dhamma.org">email us</a> to receive the appropriate login information.</p>
        <?php } else { ?>
                <h1>You must login to see this page.</h1>
                <p>The Old Student website contains information regarding group-sittings with other meditators in your area, old-student courses, dhamma service, dāna, and center development. Please feel free to <a href="mailto:info@dhara.dhamma.org">email us</a> or <a href="/contact/">contact us by another method</a> if you’re an old student and have forgotten the login information.</p>
                <p>If you haven't yet attended a ten-day course in this tradition, please view <a href="/">our home page</a> or the <a target="_blank" href="https://www.dhamma.org/">International Vipassana page</a> for more information on sitting a ten-day course.
                </p>
        <?php } ?>
            <div id="login-section-404">
                <h2>Login</h2>
            <?php wp_login_form() ?>
            </div>
    <?php } else { ?>
            <h1>Page Not Found</h1>
            We're sorry, there is no page at this address.  We have been doing some construction lately, so the page may have been moved.
            <br />
            <br />
            <p>
                <strong>You could</strong>:
            <ul id="options-404">
                <li>Go to the <a href="<?php get_home_url(); ?>/">home page</a>.</li>
                <li><a href="/about/the-center/">Read about Dhamma Dharā</a>.</li>
                <li><a href="/courses/what-to-expect/">Read What to Expect on a Course at Dhamma Dharā</a>.</li>
                <li><a href="/about/vipassana/">Read About Vipassana</a>.</li>
                <li><a href="<?php echo get_theme_mod('dhamma_schedule_link'); ?>">Apply</a> to sit or serve a course.</li>
        <?php if (is_user_logged_in()) { ?>
                    <li>Find a regular <a href="<?php get_home_url(); ?>/os/regions/group-sittings/">Group Sitting</a> in your area. </li>
                    <li>Read some <a href="<?php get_home_url(); ?>/category/all-news/">Recent News</a>.</li>
                    <li>Read about the <a href="<?php get_home_url(); ?>/center-development/master-plan/">development history</a> of the center.
        <?php } ?>
                <li>Use the Navigation Menu above to browse.</li>
                <li>Search to find the page you came looking for:<span id="search-404"><?php get_search_form(); ?></span></li>
            </ul>
        </p>

    <?php } ?>
    </div>
    <?php
}
