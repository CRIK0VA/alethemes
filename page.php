<?php get_header(); ?>
    <!-- Content -->
    <div class="page-center">
        <div class="content">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <div class="h2" ><?php the_title(); ?></div>
                <div class="contact-content">
                    <?php ale_part('pagehead');?>
                    <?php the_content(); ?>
                    <?php ale_part('pagefooter');?>
                </div>
            <?php endwhile; else: ?>
            <?php ale_part('notfound')?>
            <?php endif; ?>
        </div>
    </div>
<?php get_footer(); ?>