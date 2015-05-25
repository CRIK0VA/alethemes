<?php if (ale_get_option('social_sharing')) : ?>
<div class="social-buttons">
    <div class="addthis_toolbox addthis_default_style">
        <div class="soc1">
            <div class="tweet"><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php the_permalink()?>" data-count="horizontal">Tweet</a></div>
        </div>
        <div class="soc2">
            <div class="fb-like" data-href="<?php the_permalink()?>" data-send="false" data-layout="button_count" data-width="90" data-show-faces="false"></div>
            <div id="fb-root"></div>
            <script>(function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) {return;}
                    js = d.createElement(s); js.id = id;
                    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));</script>
        </div>
        <div class="soc3">
            <div class="plus1"><g:plusone size="medium" href="<?php the_permalink()?>"></g:plusone></div>
        </div>
        <div class="soc4">
            <div class="pin"><a href="http://pinterest.com/pin/create/button/?url=<?php the_permalink() ?>&media=<?php ale_og_meta_image() ?>" class="pin-it-button" count-layout="horizontal" onclick="window.open(this.href, 'Share on Pinterest', 'width=600,height=300'); return false"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a></div>
        </div>
    </div>
    <div class="cf"></div>
</div>
<?php endif; ?>









