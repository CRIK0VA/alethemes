<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> >

    <?php if(is_page_template('page-home.php') or is_blog()){ ?>
    <!-- Page Loading Animation #ID -->
    <div id="loading"></div>

    <!-- Background Home FlexSlider -->
    <div id="background-slider" <?php if(is_blog()){echo 'class="blog-slider"';}?>>
        <ul class="slides">
            <?php
                if(is_page_template('page-home.php')){ $slider = ale_sliders_get_slider(ale_get_option('homeslugfull')); }
                if(is_blog()){ $slider = ale_sliders_get_slider(ale_get_option('blogslugfull')); }
            ?>
            <?php if($slider):?>
                <?php foreach ($slider['slides'] as $slide) : ?>
                    <li style="background-image: url('<?php echo $slide['image'] ?>'); background-size: cover;">
                        <section>
                            <div class="section-content">
                                <p class="category"><?php echo $slide['html']; ?></p>
                                <h2 class="caption"><?php echo $slide['title']; ?></h2>
                                <p class="text">
                                    <?php echo $slide['description']; ?>
                                </p>
                                <em class="href"> <a href="<?php echo $slide['url']; ?>"><?php _e('Read Full Post','aletheme'); ?></a> </em>
                            </div>
                        </section>
                    </li>
                <?php endforeach; ?>
            <?php endif;?>
        </ul>
    </div>
    <?php } if(is_blog()){ ?>

    <?php } ?>

    <!-- Header -->
    <nav class="main-menu">
        <div class="menu-align">
            <!-- Main Menu Left -->
            <?php
            if ( has_nav_menu( 'header_left_menu' ) ) {
                wp_nav_menu(array(
                    'theme_location'=> 'header_left_menu',
                    'menu'			=> 'Header Left Menu',
                    'menu_class'	=> 'menu menu-left cf',
                    'walker'		=> new Aletheme_Nav_Walker(),
                    'container'		=> '',
                ));
            }
            ?>

            <!-- Logo -->
            <ul class="logo">
                <?php if(ale_get_option('sitelogo')){ ?>
                    <a href="<?php echo home_url(); ?>/" class="customlogo"><img src="<?php echo ale_get_option('sitelogo'); ?>" /></a>
                <?php } else { ?>
                    <a href="<?php echo home_url(); ?>/" class="alelogo"><?php echo bloginfo('name'); ?></a>
                <?php } ?>
            </ul>



            <!-- Main Menu Right -->
            <?php
            if ( has_nav_menu( 'header_right_menu' ) ) {
                wp_nav_menu(array(
                    'theme_location'=> 'header_right_menu',
                    'menu'			=> 'Header Right Menu',
                    'menu_class'	=> 'menu menu-right cf',
                    'walker'		=> new Aletheme_Nav_Walker(),
                    'container'		=> '',
                ));
            }
            ?>

            <!-- DropDown -->
            <div class="menu-click-drop">
                <a><?php _e('MENU','aletheme'); ?></a>

                <?php
                if ( has_nav_menu( 'mobile_menu' ) ) {
                    wp_nav_menu(array(
                        'theme_location'=> 'mobile_menu',
                        'menu'			=> 'Mobile Menu',
                        'menu_class'	=> 'dropdown-menu cf',
                        'walker'		=> new Aletheme_Nav_Walker(),
                        'container'		=> '',
                    ));
                }
                ?>
            </div>
        </div>
    </nav>