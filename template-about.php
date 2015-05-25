<?php
/**
 * Template Name: Template About (team)
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

            <ul class="peoples">
                <?php if(ale_get_meta('memname1')){ ?>
                    <li>
                        <div style="background-image: url('<?php echo ale_get_meta('memava1'); ?>');" class="avatar"></div>
                        <h2><a class="click"><?php echo ale_get_meta('memname1'); ?></a></h2>
                        <p><?php echo ale_get_meta('memprof1'); ?></p>

                        <ul class="social">
                            <?php if(ale_get_meta('memfb1')){ ?><li class="facebook"><a href="<?php echo ale_get_meta('memfb1'); ?>" ></a></li><?php } ?>
                            <?php if(ale_get_meta('memtw1')){ ?><li class="twitter"><a href="<?php echo ale_get_meta('memtw1'); ?>" ></a></li><?php } ?>
                            <?php if(ale_get_meta('memem1')){ ?><li class="mail"><a href="mainlto:<?php echo ale_get_meta('memem1'); ?>" ></a></li><?php } ?>
                        </ul>

                        <div class="dynamic-text" style="display: none">
                            <div class="h2"><?php _e('About','aletheme'); ?> <?php echo ale_get_meta('memname1'); ?></div>
                            <?php ale_filtered_meta('memdesc1'); ?>
                        </div>
                    </li>
                <?php } ?>
                <?php if(ale_get_meta('memname2')){ ?>
                    <li>
                        <div style="background-image: url('<?php echo ale_get_meta('memava2'); ?>');" class="avatar"></div>
                        <h2><a class="click"><?php echo ale_get_meta('memname2'); ?></a></h2>
                        <p><?php echo ale_get_meta('memprof2'); ?></p>

                        <ul class="social">
                            <?php if(ale_get_meta('memfb2')){ ?><li class="facebook"><a href="<?php echo ale_get_meta('memfb2'); ?>" ></a></li><?php } ?>
                            <?php if(ale_get_meta('memtw2')){ ?><li class="twitter"><a href="<?php echo ale_get_meta('memtw2'); ?>" ></a></li><?php } ?>
                            <?php if(ale_get_meta('memem2')){ ?><li class="mail"><a href="mainlto:<?php echo ale_get_meta('memem2'); ?>" ></a></li><?php } ?>
                        </ul>

                        <div class="dynamic-text" style="display: none">
                            <div class="h2"><?php _e('About','aletheme'); ?> <?php echo ale_get_meta('memname2'); ?></div>
                            <?php ale_filtered_meta('memdesc2'); ?>
                        </div>
                    </li>
                <?php } ?>
                <?php if(ale_get_meta('memname3')){ ?>
                    <li>
                        <div style="background-image: url('<?php echo ale_get_meta('memava3'); ?>');" class="avatar"></div>
                        <h2><a class="click"><?php echo ale_get_meta('memname3'); ?></a></h2>
                        <p><?php echo ale_get_meta('memprof3'); ?></p>

                        <ul class="social">
                            <?php if(ale_get_meta('memfb3')){ ?><li class="facebook"><a href="<?php echo ale_get_meta('memfb3'); ?>" ></a></li><?php } ?>
                            <?php if(ale_get_meta('memtw3')){ ?><li class="twitter"><a href="<?php echo ale_get_meta('memtw3'); ?>" ></a></li><?php } ?>
                            <?php if(ale_get_meta('memem3')){ ?><li class="mail"><a href="mainlto:<?php echo ale_get_meta('memem3'); ?>" ></a></li><?php } ?>
                        </ul>

                        <div class="dynamic-text" style="display: none">
                            <div class="h2"><?php _e('About','aletheme'); ?> <?php echo ale_get_meta('memname3'); ?></div>
                            <?php ale_filtered_meta('memdesc3'); ?>
                        </div>
                    </li>
                <?php } ?>
                <?php if(ale_get_meta('memname4')){ ?>
                    <li>
                        <div style="background-image: url('<?php echo ale_get_meta('memava4'); ?>');" class="avatar"></div>
                        <h2><a class="click"><?php echo ale_get_meta('memname4'); ?></a></h2>
                        <p><?php echo ale_get_meta('memprof4'); ?></p>

                        <ul class="social">
                            <?php if(ale_get_meta('memfb4')){ ?><li class="facebook"><a href="<?php echo ale_get_meta('memfb4'); ?>" ></a></li><?php } ?>
                            <?php if(ale_get_meta('memtw4')){ ?><li class="twitter"><a href="<?php echo ale_get_meta('memtw4'); ?>" ></a></li><?php } ?>
                            <?php if(ale_get_meta('memem4')){ ?><li class="mail"><a href="mainlto:<?php echo ale_get_meta('memem4'); ?>" ></a></li><?php } ?>
                        </ul>

                        <div class="dynamic-text" style="display: none">
                            <div class="h2"><?php _e('About','aletheme'); ?> <?php echo ale_get_meta('memname4'); ?></div>
                            <?php ale_filtered_meta('memdesc4'); ?>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <div class="content-right">
            <!-- BY DEFAULT HERE ELENE MARLENE -->
            <div class="text">
                <div class="h2"><?php the_title(); ?></div>
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <?php the_content(); ?>
                <?php endwhile; endif; ?>
            </div>
        </div>
    </div>
<?php get_footer(); ?>