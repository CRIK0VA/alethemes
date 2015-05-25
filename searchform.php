<form role="search" method="get" id="searchform" action="<?php echo site_url()?>" >
    <fieldset>
        <input type="text" class="searchinput" value="<?php echo get_search_query(); ?>" name="s" id="s" placeholder="<?php _e('Type here...', 'aletheme')?>" />
        <input type="submit" id="searchsubmit" class="headerfont" value="<?php _e('Search', 'aletheme')?>" />
    </fieldset>
</form>