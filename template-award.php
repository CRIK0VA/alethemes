<?php
/**
 * Template Name: Template About (awards)
 */
get_header(); ?>
    <!-- Content -->
    <div class="about-center-align">

        <div class="content-left">
            <?php if ( has_nav_menu( 'aboutpage_menu' ) ) {
                wp_nav_menu(array(
                    'theme_location'=> 'aboutpage_menu',
                    'menu'			=> 'About Page Menu',
                    'menu_class'	=> 'nav cf',
                    'walker'		=> new Aletheme_Nav_Walker(),
                    'container'		=> '',
                ));
            } ?>
        </div>

        <div class="content-right">
            <div class="text">
                <?php echo get_the_post_thumbnail($post->ID,'full'); ?>
                <div class="h2 about-3"><?php the_title(); ?></div>
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <?php the_content(); ?>
                <?php endwhile; endif; ?>
            </div>
        </div>
    </div>
<?php get_footer(); ?>