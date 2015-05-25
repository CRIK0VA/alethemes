    <!-- Footer -->
    <nav class="footer-menu <?php if($post->post_type == "gallery" and !is_single()) { echo""; } elseif(is_page_template('template-contact.php') or is_page_template('template-about.php') or is_404() or is_page_template('template-award.php') or is_page_template('page-home.php')  or is_page_template('template-press.php')){  echo ""; } else { echo "no-fixed";}    ?>">

        <!-- Social -->
        <ul class="left">
            <?php if(ale_get_option('fb')){ echo '<li class="facebook"><a href="'.ale_get_option('fb').'" rel="external"></a></li>'; } ?>
            <?php if(ale_get_option('twi')){ echo '<li class="twitter"><a href="'.ale_get_option('twi').'" rel="external"></a></li>'; } ?>
            <?php if(ale_get_option('gog')){ echo '<li class="google"><a href="'.ale_get_option('gog').'" rel="external"></a></li>'; } ?>
            <?php if(ale_get_option('pint')){ echo '<li class="pinterest"><a href="'.ale_get_option('pint').'" rel="external"></a></li>'; } ?>
            <?php if(ale_get_option('flickr')){ echo '<li class="flickr"><a href="'.ale_get_option('flickr').'" rel="external"></a></li>'; } ?>
            <?php if(ale_get_option('linked')){ echo '<li class="linkedin"><a href="'.ale_get_option('linked').'" rel="external"></a></li>'; } ?>
            <?php if(ale_get_option('insta')){ echo '<li class="instagram"><a href="'.ale_get_option('insta').'" rel="external"></a></li>'; } ?>
            <?php if(ale_get_option('emailcont')){ echo '<li class="mail"><a href="mailto:'.ale_get_option('emailcont').'" rel="external"></a></li>'; } ?>
            <?php if(ale_get_option('rssicon')){?><li class="rss"><a href="<?php echo home_url(); ?>/feed" rel="external"></a></li><?php } ?>
        </ul>

        <?php if(is_page_template('page-home.php')){ ?>
        <!-- Footer Menu -->
        <div class="center">
            <ul class="nav">
                <li><span><?php echo ale_get_option('footermenutitle'); ?></span>
                    <?php
                    if ( has_nav_menu( 'footer_menu' ) ) {
                        wp_nav_menu(array(
                            'theme_location'=> 'footer_menu',
                            'menu'			=> 'Footer Menu',
                            'menu_class'	=> 'footermenu cf',
                            'walker'		=> new Aletheme_Nav_Walker(),
                            'container'		=> '',
                        ));
                    }
                    ?>
                </li>
            </ul>
        </div>
        <?php } ?>

        <!-- Copy -->
        <?php if (ale_get_option('copyrights')) : ?>
            <p class="right"><?php echo ale_option('copyrights'); ?></p>
        <?php else: ?>
            <p class="right">&copy; <?php _e('2013 ALL RIGHTS RESERVED', 'aletheme')?></p>
        <?php endif; ?>

    </nav>
    <!-- Scripts -->
    <?php wp_footer(); ?>
</body>
</html>