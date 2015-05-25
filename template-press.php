<?php
/**
 * Template Name: Template About (press)
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

        <div class="content-bottom">

            <!-- Root element for Scrollable -->
            <div class="scrollable" id="scrollable">

                <!-- All Items -->
                <div class="items">

                    <?php if(ale_get_meta('presstit1')){ ?>
                    <div class="item">
                        <a class="<?php if(ale_get_meta('pressimage1')){ echo "img"; } elseif(ale_get_meta('pressvideo1')){ echo "video iframe"; } ?> fancybox" data-rel="group" href="<?php if(ale_get_meta('pressimage1')){ echo ale_get_meta('pressimage1'); } elseif(ale_get_meta('pressvideo1')){ echo ale_get_meta('pressvideo1'); } ?>" data-title="<?php echo ale_get_meta('presstit1'); ?>">
                            <span class="hover <?php if(ale_get_meta('pressvideo1')) { echo "play"; } ?>"></span>
                            <img src="<?php echo ale_get_meta('pressthumb1'); ?>" alt="image" />
                        </a>
                    </div>
                    <?php } ?>
                    <?php if(ale_get_meta('presstit2')){ ?>
                        <div class="item">
                            <a class="<?php if(ale_get_meta('pressimage2')){ echo "img"; } elseif(ale_get_meta('pressvideo2')){ echo "video iframe"; } ?> fancybox" data-rel="group" href="<?php if(ale_get_meta('pressimage2')){ echo ale_get_meta('pressimage2'); } elseif(ale_get_meta('pressvideo2')){ echo ale_get_meta('pressvideo2'); } ?>" data-title="<?php echo ale_get_meta('presstit2'); ?>">
                                <span class="hover <?php if(ale_get_meta('pressvideo2')) { echo "play"; } ?>"></span>
                                <img src="<?php echo ale_get_meta('pressthumb2'); ?>" alt="image" />
                            </a>
                        </div>
                    <?php } ?>
                    <?php if(ale_get_meta('presstit3')){ ?>
                        <div class="item">
                            <a class="<?php if(ale_get_meta('pressimage3')){ echo "img"; } elseif(ale_get_meta('pressvideo3')){ echo "video iframe"; } ?> fancybox" data-rel="group" href="<?php if(ale_get_meta('pressimage3')){ echo ale_get_meta('pressimage3'); } elseif(ale_get_meta('pressvideo3')){ echo ale_get_meta('pressvideo3'); } ?>" data-title="<?php echo ale_get_meta('presstit3'); ?>">
                                <span class="hover <?php if(ale_get_meta('pressvideo3')) { echo "play"; } ?>"></span>
                                <img src="<?php echo ale_get_meta('pressthumb3'); ?>" alt="image" />
                            </a>
                        </div>
                    <?php } ?>
                    <?php if(ale_get_meta('presstit4')){ ?>
                        <div class="item">
                            <a class="<?php if(ale_get_meta('pressimage4')){ echo "img"; } elseif(ale_get_meta('pressvideo4')){ echo "video iframe"; } ?> fancybox" data-rel="group" href="<?php if(ale_get_meta('pressimage4')){ echo ale_get_meta('pressimage4'); } elseif(ale_get_meta('pressvideo4')){ echo ale_get_meta('pressvideo4'); } ?>" data-title="<?php echo ale_get_meta('presstit4'); ?>">
                                <span class="hover <?php if(ale_get_meta('pressvideo4')) { echo "play"; } ?>"></span>
                                <img src="<?php echo ale_get_meta('pressthumb4'); ?>" alt="image" />
                            </a>
                        </div>
                    <?php } ?>
                    <?php if(ale_get_meta('presstit5')){ ?>
                        <div class="item">
                            <a class="<?php if(ale_get_meta('pressimage5')){ echo "img"; } elseif(ale_get_meta('pressvideo5')){ echo "video iframe"; } ?> fancybox" data-rel="group" href="<?php if(ale_get_meta('pressimage5')){ echo ale_get_meta('pressimage5'); } elseif(ale_get_meta('pressvideo5')){ echo ale_get_meta('pressvideo5'); } ?>" data-title="<?php echo ale_get_meta('presstit5'); ?>">
                                <span class="hover <?php if(ale_get_meta('pressvideo5')) { echo "play"; } ?>"></span>
                                <img src="<?php echo ale_get_meta('pressthumb5'); ?>" alt="image" />
                            </a>
                        </div>
                    <?php } ?>
                    <?php if(ale_get_meta('presstit6')){ ?>
                        <div class="item">
                            <a class="<?php if(ale_get_meta('pressimage6')){ echo "img"; } elseif(ale_get_meta('pressvideo6')){ echo "video iframe"; } ?> fancybox" data-rel="group" href="<?php if(ale_get_meta('pressimage6')){ echo ale_get_meta('pressimage6'); } elseif(ale_get_meta('pressvideo6')){ echo ale_get_meta('pressvideo6'); } ?>" data-title="<?php echo ale_get_meta('presstit6'); ?>">
                                <span class="hover <?php if(ale_get_meta('pressvideo6')) { echo "play"; } ?>"></span>
                                <img src="<?php echo ale_get_meta('pressthumb6'); ?>" alt="image" />
                            </a>
                        </div>
                    <?php } ?>
                    <?php if(ale_get_meta('presstit7')){ ?>
                        <div class="item">
                            <a class="<?php if(ale_get_meta('pressimage7')){ echo "img"; } elseif(ale_get_meta('pressvideo7')){ echo "video iframe"; } ?> fancybox" data-rel="group" href="<?php if(ale_get_meta('pressimage7')){ echo ale_get_meta('pressimage7'); } elseif(ale_get_meta('pressvideo7')){ echo ale_get_meta('pressvideo7'); } ?>" data-title="<?php echo ale_get_meta('presstit7'); ?>">
                                <span class="hover <?php if(ale_get_meta('pressvideo7')) { echo "play"; } ?>"></span>
                                <img src="<?php echo ale_get_meta('pressthumb7'); ?>" alt="image" />
                            </a>
                        </div>
                    <?php } ?>
                    <?php if(ale_get_meta('presstit8')){ ?>
                        <div class="item">
                            <a class="<?php if(ale_get_meta('pressimage8')){ echo "img"; } elseif(ale_get_meta('pressvideo8')){ echo "video iframe"; } ?> fancybox" data-rel="group" href="<?php if(ale_get_meta('pressimage8')){ echo ale_get_meta('pressimage8'); } elseif(ale_get_meta('pressvideo8')){ echo ale_get_meta('pressvideo8'); } ?>" data-title="<?php echo ale_get_meta('presstit8'); ?>">
                                <span class="hover <?php if(ale_get_meta('pressvideo8')) { echo "play"; } ?>"></span>
                                <img src="<?php echo ale_get_meta('pressthumb8'); ?>" alt="image" />
                            </a>
                        </div>
                    <?php } ?>
                    <?php if(ale_get_meta('presstit9')){ ?>
                        <div class="item">
                            <a class="<?php if(ale_get_meta('pressimage9')){ echo "img"; } elseif(ale_get_meta('pressvideo9')){ echo "video iframe"; } ?> fancybox" data-rel="group" href="<?php if(ale_get_meta('pressimage9')){ echo ale_get_meta('pressimage9'); } elseif(ale_get_meta('pressvideo9')){ echo ale_get_meta('pressvideo9'); } ?>" data-title="<?php echo ale_get_meta('presstit9'); ?>">
                                <span class="hover <?php if(ale_get_meta('pressvideo9')) { echo "play"; } ?>"></span>
                                <img src="<?php echo ale_get_meta('pressthumb9'); ?>" alt="image" />
                            </a>
                        </div>
                    <?php } ?>
                    <?php if(ale_get_meta('presstit10')){ ?>
                        <div class="item">
                            <a class="<?php if(ale_get_meta('pressimage10')){ echo "img"; } elseif(ale_get_meta('pressvideo10')){ echo "video iframe"; } ?> fancybox" data-rel="group" href="<?php if(ale_get_meta('pressimage10')){ echo ale_get_meta('pressimage10'); } elseif(ale_get_meta('pressvideo10')){ echo ale_get_meta('pressvideo10'); } ?>" data-title="<?php echo ale_get_meta('presstit10'); ?>">
                                <span class="hover <?php if(ale_get_meta('pressvideo10')) { echo "play"; } ?>"></span>
                                <img src="<?php echo ale_get_meta('pressthumb10'); ?>" alt="image" />
                            </a>
                        </div>
                    <?php } ?>
                </div>

            </div>

            <!-- Nav -->
            <div class="nav-line-left"></div>

            <a class="prev browse left"></a>
            <a class="next browse right"></a>

            <div class="nav-line-right"><p id="item-title"></p></div>

        </div>
    </div>
<?php get_footer(); ?>