<?php get_header(); ?>
    <!-- Content -->
    <div class="blog-center-align top-blog-center">

        <!-- Blog Caption -->
        <div class="blog-caption">
            <div class="blogtitle"><?php _e('Search','aletheme'); ?></div>
        </div>

        <!-- Blog Line -->
        <div class="blog-line"></div>

        <!-- Filters Here -->
        <ul class="blog-filter-line">
            <li><?php _e('Filter By','aletheme'); ?>:</li>
            <li>
                <a class="filter-caption"><p><?php _e('Author','aletheme'); ?></p><span></span></a>
                <ul>

                    <?php
                    $args = array(
                        'orderby'       => 'name',
                        'order'         => 'ASC',
                        'number'        => null,
                        'optioncount'   => false,
                        'exclude_admin' => false,
                        'show_fullname' => false,
                        'hide_empty'    => true,
                        'echo'          => true,
                        'style'         => 'list',
                        'html'          => true );

                    wp_list_authors($args); ?>
                </ul>

            </li>

            <li>
                <a class="filter-caption"><p><?php _e('Category','aletheme'); ?></p><span></span></a>
                <ul>
                    <?php
                    $args = array(
                        'show_option_all'    => '',
                        'orderby'            => 'name',
                        'order'              => 'ASC',
                        'style'              => 'list',
                        'show_count'         => 0,
                        'hide_empty'         => 1,
                        'use_desc_for_title' => 1,
                        'child_of'           => 0,
                        'feed'               => '',
                        'feed_type'          => '',
                        'feed_image'         => '',
                        'exclude'            => '',
                        'exclude_tree'       => '',
                        'include'            => '',
                        'hierarchical'       => 1,
                        'title_li'           => '',
                        'show_option_none'   => __('No categories','aletheme'),
                        'number'             => null,
                        'echo'               => 1,
                        'depth'              => 0,
                        'current_category'   => 0,
                        'pad_counts'         => 0,
                        'taxonomy'           => 'category',
                        'walker'             => null
                    );
                    wp_list_categories($args); ?>
                </ul>
            </li>

            <li>
                <a class="filter-caption"><p><?php _e('Tags','aletheme'); ?></p><span></span></a>
                <?php
                $tags = get_tags();
                $html = '<ul>';
                foreach ( $tags as $tag ) {
                    $tag_link = get_tag_link( $tag->term_id );

                    $html .= "<li><a href='{$tag_link}' title='{$tag->name} Tag' class='{$tag->slug}'>";
                    $html .= "{$tag->name}</a></li>";
                }
                $html .= '</ul>';
                echo $html;
                ?>
            </li>

            <li class="search">
                <form role="search" method="get" id="searchform" action="<?php echo site_url()?>" >
                    <input type="search" class="searchinput" value="<?php echo get_search_query(); ?>" name="s" id="s" placeholder="<?php _e('SEARCH', 'aletheme')?>" />
                    <button type="submit" id="searchsubmit"></button>
                </form>
            </li>
        </ul>

        <!-- Blog Content -->
        <div class="blog-content">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <?php ale_part('postpreview' );?>
            <?php endwhile; else: ?>
                <?php ale_part('notfound')?>
            <?php endif; ?>
        </div>

        <!-- Blog Nav  -->
        <div class="blog-nav">
            <span class="left"><?php echo get_previous_posts_link(__('&lt; Newer Posts','aletheme')); ?></span>
            <span class="right"><?php echo get_next_posts_link(__('Older Posts &gt;','aletheme')); ?></span>
            <div class="center"><?php _e('page','aletheme'); ?> <?php echo $paged; ?> <?php _e('of','aletheme'); ?> <?php echo $wp_query->max_num_pages; ?></div>
        </div>

        <!-- Blog Nav -->
        <div class="blog-line"></div>

        <!-- Blog Footer  -->
        <div class="blog-footer">
            <?php ale_part('archives'); ?>
        </div>

    </div>
<?php get_footer(); ?>