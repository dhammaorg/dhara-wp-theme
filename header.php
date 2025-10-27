<?php
//added by JCH 9/24/25
global $login_error;

if ( isset($_GET['login']) ) {
    switch ($_GET['login']) {
        case 'failed':
            $login_error = __('Invalid username or password. Please try again.');
            break;
        case 'empty':
            $login_error = __('Username and password cannot be empty.');
            break;
    }
}
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <?php if (is_restricted()) { ?>
            <title> <?php echo "Login Required " . get_theme_mod('dhamma_title_separator') . " " . get_bloginfo('name'); ?></title>
        <?php } else { ?>
            <title><?php wp_title(get_theme_mod('dhamma_title_separator'), true, "right");
            bloginfo('name'); ?></title>
        <?php } ?>
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans|Raleway" rel="stylesheet">
        <link rel="stylesheet" title="default" href="<?php echo get_stylesheet_uri(); ?>?v=1.3" type="text/css" media="screen">
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style-print.css" type="text/css" media="print">
        <?php
        $custom_page_description = get_post_meta(get_the_ID(), "page_description", true);
        if (!empty($custom_page_description)) {
            echo '<meta name="Description" content="' . $custom_page_description . '">';
        } else {
            echo '<meta name="description" content="' . get_bloginfo("description") . '">';
        }
        ?>
        <!--[if gte IE 9]><!-->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link id="responsive-stylesheet" rel="stylesheet" title="default" href="<?php echo get_template_directory_uri(); ?>/style-responsive.css?v=1.2" type="text/css" media="screen">
        <!--<![endif]-->
        <!--[if IE 9]>
                <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style-ie9.css" type="text/css" media="screen">
        <![endif]-->

        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style-color.css?v=1.1" type="text/css" media="screen">
        <!--[if gte IE 9]><!-->
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style-responsive-color.css" type="text/css" media="screen">
        <!--<![endif]-->
        <!--This used to work, but the link died: script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script--><!--helps old browers render new html5 elements-->
        <?php if (is_singular() && get_option('thread_comments')) wp_enqueue_script('comment-reply'); ?>
        <?php wp_head(); ?>
    </head>
    <body>
        <?php // JJD 2/28/25 factor out common variables to improve readability and performance
        $userLoggedIn = is_user_logged_in();
        $showOSMenu = show_os_menu($wp);
        $isOSUser = is_os_user();
        ?>
        <header id="page-header">
        <?php if (is_dev()) { ?>
            <div id="search-login-bar-color-dev">
        <?php } else { ?>
            <div id="search-login-bar-color">
        <?php } ?>
                <div id="search-login-bar" class="constrained-to-mid">
                    <div id="search-login-bar-row">
                    <?php if ($userLoggedIn) { ?>
                        <div id="login-form-cell" class="logged-in">
                            <div id='search-login-bar-logged-in'>Welcome <a href="/os/"><?php echo wp_get_current_user()->display_name; ?></a></div>
                            <div id='search-login-bar-logout-link'><a href='<?php global $wp;
                            echo wp_logout_url(url_after_logout($wp)); ?>'>Logout</a></div>
                        </div>
                    <?php } else { ?>
                        <div id="login-form-cell" class="logged-out">
                            <?php //added by JCH 9/24/25 ?> 
                            <?php if ( !empty($login_error) ) : ?>
                                 <div class="login-error" style="color:red; margin-bottom:8px; font-size:16px;">
                                     <?php echo esc_html($login_error); ?>
                                 </div>
                             <?php endif; ?>

                            <?php
                            $args = [
                                'id_remember' => 'search-login-bar-remember-me',
                                'label_username' => __('Username'),
                                'value_remember' => true,
                            ];

                            global $wp;
                            if (! $showOSMenu) {
                                // we're NOT logged in, and we're NOT currently showing OS menu; so after we login show the OS menu
                                $args ['redirect'] = site_url("/os/");
                            }

                            wp_login_form($args);
                            ?>
                        </div>
                    <?php } ?>
                        <div id="search-login-bar-search-cell">
                            <form role="search" method="get" id="searchform" class="searchform" action="/">
                                <label class="screen-reader-text" for="s">Search for:</label>
                                <input type="text" value="" name="s" id="topbarsearchbox">
                                <input type="submit" id="searchsubmit" value="Search">
                            </form>
                        </div>
                        <div id="schedule-cell">
                            <a target="_blank" href="<?php echo get_theme_mod('dhamma_schedule_link'); ?>"><img alt="CALENDAR" src="/wp-content/uploads/calendar-blue-32.png"></a>
                        </div>
                    </div>
                </div>
            </div>
            <div id="header-titles">
                <div id="header-background" class="constrained-to-mid">
                    <a id="header-image-link" href="<?php echo home_url(); ?>">
                        <img id="header-image" src="<?php echo get_theme_mod('dhamma_picture_icon'); ?>" alt="">
                    </a>
                    <h1 id="header-site-name">
                        <a id="header-site-name-link" href="<?php echo home_url(""); ?>"><?php bloginfo('name'); ?></a>
                    </h1>
                    <h2 id="header-site-description"><?php bloginfo('description'); ?></h2> <h2 id="header-site-location">
                        <?php
                        global $wp;
                        echo $isOSUser ? 'Old Students' : get_theme_mod('dhamma_short_location');
                        ?>
                    </h2>
                </div>
            </div>
            <ul id="mobile-menu">
                <li><a class="mobile-nav-toggle" href="#"><span>
                    <img alt="" src="<?php bloginfo('template_directory'); ?>/images/mobile-menu-icon.png"><br>Menu
                </span></a></li>
                <li id="mobile-schedule"><a href="<?php echo get_theme_mod('dhamma_schedule_link'); ?>" target="_blank"><span>
                    <img alt="" src="<?php bloginfo('template_directory'); ?>/images/mobile-schedule-icon.png"><br>Schedule
                </span></a></li>
                <li><a class="mobile-search-toggle" href="#"><span>
                    <img alt="" src="<?php bloginfo('template_directory'); ?>/images/mobile-search-icon.png"><br>Search
                </span></a></li>
                <li>
                    <?php if ($userLoggedIn) { ?>
                        <a href="<?php global $wp;
                            echo wp_logout_url(url_after_logout($wp)); ?>"><span>
                                <img alt="" src="<?php bloginfo('template_directory'); ?>/images/mobile-logout-icon.png"><br>
                                Logout
                            </span></a>
                    <?php } else { ?>
                        <a class="mobile-login-toggle" href="#"><span>
                                <img alt="" src="<?php bloginfo('template_directory'); ?>/images/mobile-login-icon.png"><br>
                                Login
                            </span></a>
                    <?php } ?>
                </li>
            </ul>
            <div id="mobile-search">
                <form role="search" method="get" id="mobilesearchform" class="searchform" action="/">
                    <div>
                        <label class="screen-reader-text" for="s">Search for:</label>
                        <input type="text" value="" name="s" id="s">
                        <input type="submit" id="mobilesearchsubmit" value="Search">
                    </div>
                </form>
            </div>
            <div id="mobile-login">
                <?php if (! $userLoggedIn) { ?>
                    <div id="mobile-login-forgot-info">Please feel free to <a href="mailto:info@dhara.dhamma.org">email us</a> if you’re an old student and have forgotten the login information.</div>
                            <?php //added by JCH 9/24/25 ?>
                            <?php if ( !empty($login_error) ) : ?>
                                 <div class="login-error" style="color:red; margin-bottom:8px;">
                                     <?php echo esc_html($login_error); ?>
                                 </div>
                             <?php endif; ?>
                    <?php
                    $args = [
                        'echo' => true,
                        'redirect' => site_url($_SERVER['REQUEST_URI']),
                        'form_id' => 'mobileloginform',
                        'label_username' => __('Username'),
                        'label_password' => __('Password'),
                        'label_remember' => __('Remember Me'),
                        'label_log_in' => __('Log In'),
                        'id_username' => 'mobile_user_login',
                        'id_password' => 'mobile_user_pass',
                        'id_remember' => 'rememberme',
                        'id_submit' => 'wp-submit',
                        'remember' => true,
                        'value_username' => 'oldstudent',
                        'value_remember' => true,
                    ];
                    if (! $showOSMenu) {
                        // we're NOT logged in, and we're NOT currently showing OS menu; so after we login show the OS menu
                        // mobile version of the same logic above
                        $args ['redirect'] = site_url("/os/");
                    }
                    wp_login_form($args);
                    ?>
                    <?php } ?>
            </div>
            <div id="nav-color">
                <div class="constrained-to-mid">
                    <?php
                    global $wp;

                    // If on an OS page, user can always see the (New Students) sub-menu;
                    // Otherwise, they can only see the (Old Students) sub-menu if they are logged in:
                    $sub_menu_accessible = $isOSUser || $userLoggedIn;
                    $sub_menu_class = $sub_menu_accessible ? 'menu-item-has-children' : '';

                    if ($isOSUser) {
                        $wrap_code_pre = '<ul id="%1$s" class="%2$s"><li class="' . $sub_menu_class . '">';
                        $wrap_code_post = '</li>%3$s</ul>';
                        /*
                          $wrap_code_pre = '<ul id="%1$s" class="%2$s">%3$s<li class="' . $sub_menu_class . '">';
                          $wrap_code_post = '</li></ul>';
                         */
                        $sub_menu_label = "ns-menu";
                        $menu_label = "os-menu";
                        $sub_menu_link = '<a href="/">New Students</a>';
                    } else {
                        $wrap_code_pre = '<ul id="%1$s" class="%2$s">%3$s<li class="' . $sub_menu_class . '">';
                        $wrap_code_post = '</li></ul>';
                        $sub_menu_label = "os-menu";
                        $menu_label = "ns-menu";
                        $sub_menu_link = '<a href="/os/">Old Students</a>';
                    }

                    if ($sub_menu_accessible) {
                        $sub_menu = '<ul class="sub-menu">' . wp_nav_menu([
                                    'echo' => false,
                                    'theme_location' => $sub_menu_label,
                                    'items_wrap' => '%3$s',
                                    'container' => '',
                                ]) . '</ul>';
                    } else {
                        $sub_menu = '';
                    }

                    if ($isOSUser) {
                        wp_nav_menu([
                            'echo' => true,
                            'theme_location' => $menu_label,
                            'items_wrap' => $wrap_code_pre . $sub_menu_link . $sub_menu . $wrap_code_post,
                            'container_class' => 'header-nav-menu',
                            'menu_class' => "nav",
                        ]);
                    } else {
                        wp_nav_menu([
                            'echo' => true,
                            'theme_location' => $menu_label,
                            'items_wrap' => $wrap_code_pre . $sub_menu_link . $sub_menu . $wrap_code_post,
                            'container_class' => 'header-nav-menu',
                            'menu_class' => "nav",
                        ]);
                    }
                    ?>
                </div>
            </div>
        </header>
        <div id="page-content-matting">
        <div class="constrained-to-mid">
