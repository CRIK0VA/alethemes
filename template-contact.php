<?php 
/**
 * Template Name: Template Contact
 */
// send contact
if (isset($_POST['contact'])) {
	$error = ale_send_contact($_POST['contact']);
}
get_header();
?>
    <!-- Content -->
    <div class="contacts-center">
        <div class="content">

            <div class="h2" ><?php the_title(); ?></div>

            <div class="contact-content">
                <div class="left">
                    <div class="contacts">
                        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                            <?php the_content(); ?>
                        <?php endwhile; endif; ?>
                    </div>

                    <p class="info">
                        <?php echo ale_get_meta('descr1'); ?>
                    </p>
                </div>

                <div class="right">
                    <form method="post" action="<?php the_permalink();?>">
                        <?php if (isset($_GET['success'])) : ?>
                            <p class="success"><?php _e('Thank you for your message!', 'aletheme')?></p>
                        <?php endif; ?>
                        <?php if (isset($error) && isset($error['msg'])) : ?>
                            <p class="error"><?php echo $error['msg']?></p>
                        <?php endif; ?>
                    <form method="post" id="contact-form" action="contacts.html" onsubmit="">
                        <div class="form-left">
                            <input name="contact[name]" type="text" placeholder="Your Name (required)" value="<?php echo isset($_POST['contact']['name']) ? $_POST['contact']['name'] : ''?>" required="required" id="contact-form-name" />
                            <input name="contact[email]" type="email" placeholder="Email (required)" value="<?php echo isset($_POST['contact']['email']) ? $_POST['contact']['email'] : ''?>" required="required" id="contact-form-email" />
                            <input name="contact[phone]" type="text" placeholder="Phone" value="<?php echo isset($_POST['contact']['phone']) ? $_POST['contact']['phone'] : ''?>" id="contact-form-phone" />
                            <input name="contact[location]" type="text" placeholder="Event location" value="<?php echo isset($_POST['contact']['location']) ? $_POST['contact']['location'] : ''?>" id="contact-form-location" />
                            <input name="contact[date]" type="text" placeholder="Event date" value="<?php echo isset($_POST['contact']['date']) ? $_POST['contact']['date'] : ''?>" id="contact-form-date" />
                        </div>

                        <div class="form-right">
                            <input name="contact[how]" type="text" placeholder="How did you hear about us?" value="<?php echo isset($_POST['contact']['how']) ? $_POST['contact']['how'] : ''?>" id="contact-form-how" />
                            <textarea name="contact[message]"  placeholder="Your Message (required)"id="contact-form-message" required="required"><?php echo isset($_POST['contact']['message']) ? $_POST['contact']['message'] : ''?></textarea>
                            <input type="submit" class="submit" value="<?php _e('Submit', 'aletheme')?>"/>
                        </div>
                        <?php wp_nonce_field() ?>
                    </form>
                </div>
            </div>


            <div class="line"></div>

            <div class="contact-footer">
                <p>
                    <?php echo ale_get_meta('descr2'); ?>
                </p>
            </div>

        </div>
    </div>
<?php get_footer(); ?>