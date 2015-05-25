<?php get_header(); ?>

    <!-- Content -->
    <div class="portfolio-single-center-align">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="portfolio-single-title">
        <h2><?php the_title(); ?></h2>
        <p><?php _e('by','aletheme'); ?> <a href=""><?php echo the_author_posts_link(); ?></a> <?php _e('on','aletheme'); ?> <?php echo get_the_date(); ?></p>
        <a href="<?php echo home_url();?>/gallery" class="back"><?php _e('&lt; back to the gallery','aletheme'); ?></a>
    </div>

    <section class="slider">
    <div id="slider" class="flexslider portfolioslider">
    <ul class="slides">
        <?php $args = array(
            'post_type' => 'attachment',
            'numberposts' => -1,
            'post_status' => null,
            'order'				=> 'ASC',
            'orderby'			=> 'menu_order ID',
            'meta_query'		=> array(
                array(
                    'key'		=> '_ale_hide_from_gallery',
                    'value'		=> 0,
                    'type'		=> 'DECIMAL',
                ),
            ),
            'post_parent' => $post->ID
        );
        $attachments = get_posts( $args );
        if ( $attachments ) {
            foreach ( $attachments as $attachment ) { ?>
                <li>
                    <!-- Story -->
                    <div class="hover">
                        <div class="share">
                            <p><?php _e('SHARE','aletheme'); ?>:</p>
                            <ul>
                                <li class="facebook"><a href="<?php echo ale_get_share('fb'); ?>" onclick="window.open(this.href, 'Share on Facebook', 'width=600,height=300'); return false"></a></li>
                                <li class="twitter"><a href="<?php echo ale_get_share('twi'); ?>" onclick="window.open(this.href, 'Share on Twitter', 'width=600,height=300'); return false"></a></li>
                                <li class="mail"><a href="mailto:?subject=<?php the_title(); ?>&body=<?php the_content(); ?>" ></a></li>
                            </ul>
                        </div>
                        <div class="content">
                            <p class="read"><?php _e('CLICK HERE TO READ STORY','aletheme'); ?></p>
                        </div>
                    </div>
                    <div class="story">
                        <div class="content">
                            <span class="close"></span>
                            <p class="text">
                                <?php echo $attachment->post_content; ?>
                            </p>
                        </div>
                    </div>

                    <!-- IMG -->
                    <?php echo wp_get_attachment_image( $attachment->ID, 'gallery-big' ); ?>
                </li>
            <?php }
        } ?>
    </ul>
    </div>
    <div id="carousel" class="flexslider">
        <ul class="slides">
            <?php
            if ( $attachments ) {
                foreach ( $attachments as $attachment ) { ?>
                    <li>
                        <div class="hover"></div>
                        <?php echo wp_get_attachment_image( $attachment->ID, 'gallery-mini' ); ?>
                    </li>
                <?php }
            } ?>
        </ul>
    </div>

    <!-- Scroll -->
    <div class="pseudo-scroll">
        <div class="scrollbar"></div>
    </div>

    <div class="gallerydescriptionbox">
        <?php the_content(); ?>
    </div>
    </section>
    <?php endwhile;  endif;  ?>
    </div>
<?php get_footer(); ?>