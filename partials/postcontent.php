<section class="story cf">
    <?php echo get_the_post_thumbnail($post->ID,'large'); ?>
    <?php the_content(); ?>
    <?php if(get_the_tags()){ ?>
        <p class="tagsphar"><?php the_tags(); ?></p>
    <?php } ?>

    <?php wp_link_pages(array(
        'before' => '<p>' . __('Pages:', 'aletheme'),
        'after'	 => '</p>',
    )) ?>
</section>
