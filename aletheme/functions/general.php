<?php
/**
 * Get option wrapper
 * @param mixed $name
 * @param mixed $default
 * @return mixed 
 */
function ale_option($name, $default = false) {
	echo ale_get_option($name, $default);
}
function ale_filtered_option($name, $default = false, $filter = 'the_content') {
	echo apply_filters($filter, ale_get_option($name, $default));
}
function ale_get_option($name, $default = false) {
	$name = 'ale_' . $name;
	if (false === $default) {
		$options = aletheme_get_options();
		foreach ($options as $option) {
			if (isset($option['id']) && $option['id'] == $name) {
				$default = isset($option['std']) ? $option['std'] : false;
				break;
			}
		}
	}
	return of_get_option($name, $default);
}

/**
 * Echo meta for post
 * @param string $key
 * @param boolean $single
 * @param mixed $post_id 
 */
function ale_meta($key, $single = true, $post_id = null) {
	echo ale_get_meta($key, $single, $post_id);
}
/**
 * Find meta for post
 * @param string $key
 * @param boolean $single
 * @param mixed $post_id 
 */
function ale_get_meta($key, $single = true, $post_id = null) {
	if (null === $post_id) {
		$post_id = get_the_ID();
	}
	$key = 'ale_' . $key;
	return get_post_meta($post_id, $key, $single);
}
/**
 * Apply filters to post meta
 * @param string $key
 * @param string $filter
 * @param mixed $post_id 
 */
function ale_filtered_meta($key, $filter = 'the_content', $post_id = null) {
	echo apply_filters($filter, ale_get_meta($key, true, $post_id));
}

/**
 * Display permalink 
 * 
 * @param int|string $system
 * @param int $isCat 
 */
function ale_permalink($system, $isCat = false) {
    echo ale_get_permalink($system, $isCat);
}
/**
 * Get permalink for page, post or category
 * 
 * @param int|string $system
 * @param bool $isCat
 * @return string
 */
function ale_get_permalink($system, $isCat = 0)  {
    if ($isCat) {
        if (!is_numeric($system)) {
            $system = get_cat_ID($system);
        }
        return get_category_link($system);
    } else {
        $page = ale_get_page($system);
        
        return null === $page ? '' : get_permalink($page->ID);
    }
}

/**
 * Display custom excerpt
 */
function ale_excerpt() {
    echo ale_get_excerpt();
}
/**
 * Get only excerpt, without content.
 * 
 * @global object $post
 * @return string 
 */
function ale_get_excerpt() {
    global $post;
	$excerpt = trim($post->post_excerpt);
	$excerpt = $excerpt ? apply_filters('the_content', $excerpt) : '';
    return $excerpt;
}

/**
 * Display first category link
 */
function ale_first_category() {
    $cat = ale_get_first_category();
	if (!$cat) {
		echo '';
		return;
	}
    echo '<a href="' . ale_get_permalink($cat->cat_ID, true) . '">' . $cat->name . '</a>';
}
/**
 * Parse first post category
 */
function ale_get_first_category() {
    $cats = get_the_category();
    return isset($cats[0]) ? $cats[0] : null;
}

/**
 * Get page by name, id or slug. 
 * @global object $wpdb
 * @param mixed $name
 * @return object 
 */
function ale_get_page($slug) {
    global $wpdb;
    
    if (is_numeric($slug)) {
        $page = get_page($slug);
    } else {
        $page = $wpdb->get_row($wpdb->prepare("SELECT DISTINCT * FROM $wpdb->posts WHERE post_name=%s AND post_status=%s", $slug, 'publish'));
    }
    
    return $page;
}

/**
 * Find all subpages for page
 * @param int $id
 * @return array
 */
function ale_get_subpages($id) {
    $query = new WP_Query(array(
        'post_type'         => 'page',
        'orderby'           => 'menu_order',
        'order'             => 'ASC',
        'posts_per_page'    => -1,
        'post_parent'       => (int) $id,
    ));

    $entries = array();
    while ($query->have_posts()) : $query->the_post();
        $entry = array(
            'id' => get_the_ID(),
            'title' => get_the_title(),
            'link' => get_permalink(),
            'content' => get_the_content(),
        );
        $entries[] = $entry;
    endwhile;
    wp_reset_query();
    return $entries;
}

function ale_page_links() {
	global $wp_query, $wp_rewrite;
	$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
 
	$pagination = array(
		'base' => @add_query_arg('page','%#%'),
		'format' => '',
		'total' => $wp_query->max_num_pages,
		'current' => $current,
		'show_all' => true,
		'type' => 'list',
		'next_text' => 'Следующие посты',
		'prev_text' => 'Предыдущие посты'
		);
 
	if( $wp_rewrite->using_permalinks() )
		$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );
 
	if( !empty($wp_query->query_vars['s']) )
		$pagination['add_args'] = array( 's' => get_query_var( 's' ) );
 
	echo paginate_links($pagination);
}

function ale_page_links_custom($custom_query) {
        global $wp_query, $wp_rewrite;
        $custom_query->query_vars['paged'] > 1 ? $current = $custom_query->query_vars['paged'] : $current = 1;

        $pagination = array(
            'base' => @add_query_arg('page','%#%'),
            'format' => '',
            'total' => $custom_query->max_num_pages,
            'current' => $current,
            'show_all' => true,
            'type' => 'list',
            'next_text' => 'Следующие посты',
            'prev_text' => 'Предыдущие посты'
        );

        if( $wp_rewrite->using_permalinks() )
                $pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );

        if( !empty($custom_query->query_vars['s']) )
                $pagination['add_args'] = array( 's' => get_query_var( 's' ) );

        echo paginate_links($pagination);
}


/**
 * Generate random number
 *
 * Creates a 4 digit random number for used
 * mostly for unique ID creation. 
 * 
 * @return integer 
 */
function ale_get_random_number() {
	return substr( md5( uniqid( rand(), true) ), 0, 4 );
}

/**
 * Retreive Google Fonts List.
 * 
 * @return array 
 */
function ale_get_google_webfonts()
{
	return array(
        'ABeeZee' => 'ABeeZee',
        'Abel' => 'Abel',
        'Abril+Fatface' => 'Abril Fatface',
        'Aclonica' => 'Aclonica',
        'Acme' => 'Acme',
        'Actor' => 'Actor',
        'Adamina' => 'Adamina',
        'Advent+Pro' => 'Advent Pro',
        'Aguafina+Script' => 'Aguafina Script',
        'Akronim' => 'Akronim',
        'Aladin' => 'Aladin',
        'Aldrich' => 'Aldrich',
        'Alegreya' => 'Alegreya',
        'Alegreya+SC' => 'Alegreya SC',
        'Alex+Brush' => 'Alex Brush',
        'Alfa+Slab+One' => 'Alfa Slab One',
        'Alice' => 'Alice',
        'Alike' => 'Alike',
        'Alike+Angular' => 'Alike Angular',
        'Allan' => 'Allan',
        'Allerta' => 'Allerta',
        'Allerta+Stencil' => 'Allerta Stencil',
        'Allura' => 'Allura',
        'Almendra' => 'Almendra',
        'Almendra+Display' => 'Almendra Display',
        'Almendra+SC' => 'Almendra SC',
        'Amarante' => 'Amarante',
        'Amaranth' => 'Amaranth',
        'Amatic+SC' => 'Amatic SC',
        'Amethysta' => 'Amethysta',
        'Anaheim' => 'Anaheim',
        'Andada' => 'Andada',
        'Andika' => 'Andika',
        'Angkor' => 'Angkor',
        'Annie+Use+Your+Telescope' => 'Annie Use Your Telescope',
        'Anonymous+Pro' => 'Anonymous Pro',
        'Antic' => 'Antic',
        'Antic+Didone' => 'Antic Didone',
        'Antic+Slab' => 'Antic Slab',
        'Anton' => 'Anton',
        'Arapey' => 'Arapey',
        'Arbutus' => 'Arbutus',
        'Arbutus+Slab' => 'Arbutus Slab',
        'Architects+Daughter' => 'Architects Daughter',
        'Archivo+Black' => 'Archivo Black',
        'Archivo+Narrow' => 'Archivo Narrow',
        'Arimo' => 'Arimo',
        'Arizonia' => 'Arizonia',
        'Armata' => 'Armata',
        'Artifika' => 'Artifika',
        'Arvo' => 'Arvo',
        'Asap' => 'Asap',
        'Asset' => 'Asset',
        'Astloch' => 'Astloch',
        'Asul' => 'Asul',
        'Atomic+Age' => 'Atomic Age',
        'Aubrey' => 'Aubrey',
        'Audiowide' => 'Audiowide',
        'Autour+One' => 'Autour One',
        'Average' => 'Average',
        'Average+Sans' => 'Average Sans',
        'Averia+Gruesa+Libre' => 'Averia Gruesa Libre',
        'Averia+Libre' => 'Averia Libre',
        'Averia+Sans+Libre' => 'Averia Sans Libre',
        'Averia+Serif+Libre' => 'Averia Serif Libre',
        'Bad+Script' => 'Bad Script',
        'Balthazar' => 'Balthazar',
        'Bangers' => 'Bangers',
        'Basic' => 'Basic',
        'Battambang' => 'Battambang',
        'Baumans' => 'Baumans',
        'Bayon' => 'Bayon',
        'Belgrano' => 'Belgrano',
        'Belleza' => 'Belleza',
        'BenchNine' => 'BenchNine',
        'Bentham' => 'Bentham',
        'Berkshire+Swash' => 'Berkshire Swash',
        'Bevan' => 'Bevan',
        'Bigelow+Rules' => 'Bigelow Rules',
        'Bigshot+One' => 'Bigshot One',
        'Bilbo' => 'Bilbo',
        'Bilbo+Swash+Caps' => 'Bilbo Swash Caps',
        'Bitter' => 'Bitter',
        'Black+Ops+One' => 'Black Ops One',
        'Bokor' => 'Bokor',
        'Bonbon' => 'Bonbon',
        'Boogaloo' => 'Boogaloo',
        'Bowlby+One' => 'Bowlby One',
        'Bowlby+One+SC' => 'Bowlby One SC',
        'Brawler' => 'Brawler',
        'Bree+Serif' => 'Bree Serif',
        'Bubblegum+Sans' => 'Bubblegum Sans',
        'Bubbler+One' => 'Bubbler One',
        'Buda' => 'Buda',
        'Buenard' => 'Buenard',
        'Butcherman' => 'Butcherman',
        'Butterfly+Kids' => 'Butterfly Kids',
        'Cabin' => 'Cabin',
        'Cabin+Condensed' => 'Cabin Condensed',
        'Cabin+Sketch' => 'Cabin Sketch',
        'Caesar+Dressing' => 'Caesar Dressing',
        'Cagliostro' => 'Cagliostro',
        'Calligraffitti' => 'Calligraffitti',
        'Cambo' => 'Cambo',
        'Candal' => 'Candal',
        'Cantarell' => 'Cantarell',
        'Cantata+One' => 'Cantata One',
        'Cantora+One' => 'Cantora One',
        'Capriola' => 'Capriola',
        'Cardo' => 'Cardo',
        'Carme' => 'Carme',
        'Carrois+Gothic' => 'Carrois Gothic',
        'Carrois+Gothic+SC' => 'Carrois Gothic SC',
        'Carter+One' => 'Carter One',
        'Caudex' => 'Caudex',
        'Cedarville+Cursive' => 'Cedarville Cursive',
        'Ceviche+One' => 'Ceviche One',
        'Changa+One' => 'Changa One',
        'Chango' => 'Chango',
        'Chau+Philomene+One' => 'Chau Philomene One',
        'Chela+One' => 'Chela One',
        'Chelsea+Market' => 'Chelsea Market',
        'Chenla' => 'Chenla',
        'Cherry+Cream+Soda' => 'Cherry Cream Soda',
        'Cherry+Swash' => 'Cherry Swash',
        'Chewy' => 'Chewy',
        'Chicle' => 'Chicle',
        'Chivo' => 'Chivo',
        'Cinzel' => 'Cinzel',
        'Cinzel+Decorative' => 'Cinzel Decorative',
        'Clicker+Script' => 'Clicker Script',
        'Coda' => 'Coda',
        'Coda+Caption' => 'Coda Caption',
        'Codystar' => 'Codystar',
        'Combo' => 'Combo',
        'Comfortaa' => 'Comfortaa',
        'Coming+Soon' => 'Coming Soon',
        'Concert+One' => 'Concert One',
        'Condiment' => 'Condiment',
        'Content' => 'Content',
        'Contrail+One' => 'Contrail One',
        'Convergence' => 'Convergence',
        'Cookie' => 'Cookie',
        'Copse' => 'Copse',
        'Corben' => 'Corben',
        'Courgette' => 'Courgette',
        'Cousine' => 'Cousine',
        'Coustard' => 'Coustard',
        'Covered+By+Your+Grace' => 'Covered By Your Grace',
        'Crafty+Girls' => 'Crafty Girls',
        'Creepster' => 'Creepster',
        'Crete+Round' => 'Crete Round',
        'Crimson+Text' => 'Crimson Text',
        'Croissant+One' => 'Croissant One',
        'Crushed' => 'Crushed',
        'Cuprum' => 'Cuprum',
        'Cutive' => 'Cutive',
        'Cutive+Mono' => 'Cutive Mono',
        'Damion' => 'Damion',
        'Dancing+Script' => 'Dancing Script',
        'Dangrek' => 'Dangrek',
        'Dawning+of+a+New+Day' => 'Dawning of a New Day',
        'Days+One' => 'Days One',
        'Delius' => 'Delius',
        'Delius+Swash+Caps' => 'Delius Swash Caps',
        'Delius+Unicase' => 'Delius Unicase',
        'Della+Respira' => 'Della Respira',
        'Denk+One' => 'Denk One',
        'Devonshire' => 'Devonshire',
        'Didact+Gothic' => 'Didact Gothic',
        'Diplomata' => 'Diplomata',
        'Diplomata+SC' => 'Diplomata SC',
        'Domine' => 'Domine',
        'Donegal+One' => 'Donegal One',
        'Doppio+One' => 'Doppio One',
        'Dorsa' => 'Dorsa',
        'Dosis' => 'Dosis',
        'Dr+Sugiyama' => 'Dr Sugiyama',
        'Droid+Sans' => 'Droid Sans',
        'Droid+Sans+Mono' => 'Droid Sans Mono',
        'Droid+Serif' => 'Droid Serif',
        'Duru+Sans' => 'Duru Sans',
        'Dynalight' => 'Dynalight',
        'EB+Garamond' => 'EB Garamond',
        'Eagle+Lake' => 'Eagle Lake',
        'Eater' => 'Eater',
        'Economica' => 'Economica',
        'Electrolize' => 'Electrolize',
        'Elsie' => 'Elsie',
        'Elsie+Swash+Caps' => 'Elsie Swash Caps',
        'Emblema+One' => 'Emblema One',
        'Emilys+Candy' => 'Emilys Candy',
        'Engagement' => 'Engagement',
        'Englebert' => 'Englebert',
        'Enriqueta' => 'Enriqueta',
        'Erica+One' => 'Erica One',
        'Esteban' => 'Esteban',
        'Euphoria+Script' => 'Euphoria Script',
        'Ewert' => 'Ewert',
        'Exo' => 'Exo',
        'Expletus+Sans' => 'Expletus Sans',
        'Fanwood+Text' => 'Fanwood Text',
        'Fascinate' => 'Fascinate',
        'Fascinate+Inline' => 'Fascinate Inline',
        'Faster+One' => 'Faster One',
        'Fasthand' => 'Fasthand',
        'Federant' => 'Federant',
        'Federo' => 'Federo',
        'Felipa' => 'Felipa',
        'Fenix' => 'Fenix',
        'Finger+Paint' => 'Finger Paint',
        'Fjalla+One' => 'Fjalla One',
        'Fjord+One' => 'Fjord One',
        'Flamenco' => 'Flamenco',
        'Flavors' => 'Flavors',
        'Fondamento' => 'Fondamento',
        'Fontdiner+Swanky' => 'Fontdiner Swanky',
        'Forum' => 'Forum',
        'Francois+One' => 'Francois One',
        'Freckle+Face' => 'Freckle Face',
        'Fredericka+the+Great' => 'Fredericka the Great',
        'Fredoka+One' => 'Fredoka One',
        'Freehand' => 'Freehand',
        'Fresca' => 'Fresca',
        'Frijole' => 'Frijole',
        'Fruktur' => 'Fruktur',
        'Fugaz+One' => 'Fugaz One',
        'GFS+Didot' => 'GFS Didot',
        'GFS+Neohellenic' => 'GFS Neohellenic',
        'Gabriela' => 'Gabriela',
        'Gafata' => 'Gafata',
        'Galdeano' => 'Galdeano',
        'Galindo' => 'Galindo',
        'Gentium+Basic' => 'Gentium Basic',
        'Gentium+Book+Basic' => 'Gentium Book Basic',
        'Geo' => 'Geo',
        'Geostar' => 'Geostar',
        'Geostar+Fill' => 'Geostar Fill',
        'Germania+One' => 'Germania One',
        'Gilda+Display' => 'Gilda Display',
        'Give+You+Glory' => 'Give You Glory',
        'Glass+Antiqua' => 'Glass Antiqua',
        'Glegoo' => 'Glegoo',
        'Gloria+Hallelujah' => 'Gloria Hallelujah',
        'Goblin+One' => 'Goblin One',
        'Gochi+Hand' => 'Gochi Hand',
        'Gorditas' => 'Gorditas',
        'Goudy+Bookletter+1911' => 'Goudy Bookletter 1911',
        'Graduate' => 'Graduate',
        'Grand+Hotel' => 'Grand Hotel',
        'Gravitas+One' => 'Gravitas One',
        'Great+Vibes' => 'Great Vibes',
        'Griffy' => 'Griffy',
        'Gruppo' => 'Gruppo',
        'Gudea' => 'Gudea',
        'Habibi' => 'Habibi',
        'Hammersmith+One' => 'Hammersmith One',
        'Hanalei' => 'Hanalei',
        'Hanalei+Fill' => 'Hanalei Fill',
        'Handlee' => 'Handlee',
        'Hanuman' => 'Hanuman',
        'Happy+Monkey' => 'Happy Monkey',
        'Headland+One' => 'Headland One',
        'Henny+Penny' => 'Henny Penny',
        'Herr+Von+Muellerhoff' => 'Herr Von Muellerhoff',
        'Holtwood+One+SC' => 'Holtwood One SC',
        'Homemade+Apple' => 'Homemade Apple',
        'Homenaje' => 'Homenaje',
        'IM+Fell+DW+Pica' => 'IM Fell DW Pica',
        'IM+Fell+DW+Pica+SC' => 'IM Fell DW Pica SC',
        'IM+Fell+Double+Pica' => 'IM Fell Double Pica',
        'IM+Fell+Double+Pica+SC' => 'IM Fell Double Pica SC',
        'IM+Fell+English' => 'IM Fell English',
        'IM+Fell+English+SC' => 'IM Fell English SC',
        'IM+Fell+French+Canon' => 'IM Fell French Canon',
        'IM+Fell+French+Canon+SC' => 'IM Fell French Canon SC',
        'IM+Fell+Great+Primer' => 'IM Fell Great Primer',
        'IM+Fell+Great+Primer+SC' => 'IM Fell Great Primer SC',
        'Iceberg' => 'Iceberg',
        'Iceland' => 'Iceland',
        'Imprima' => 'Imprima',
        'Inconsolata' => 'Inconsolata',
        'Inder' => 'Inder',
        'Indie+Flower' => 'Indie Flower',
        'Inika' => 'Inika',
        'Irish+Grover' => 'Irish Grover',
        'Istok+Web' => 'Istok Web',
        'Italiana' => 'Italiana',
        'Italianno' => 'Italianno',
        'Jacques+Francois' => 'Jacques Francois',
        'Jacques+Francois+Shadow' => 'Jacques Francois Shadow',
        'Jim+Nightshade' => 'Jim Nightshade',
        'Jockey+One' => 'Jockey One',
        'Jolly+Lodger' => 'Jolly Lodger',
        'Josefin+Sans' => 'Josefin Sans',
        'Josefin+Slab' => 'Josefin Slab',
        'Joti+One' => 'Joti One',
        'Judson' => 'Judson',
        'Julee' => 'Julee',
        'Julius+Sans+One' => 'Julius Sans One',
        'Junge' => 'Junge',
        'Jura' => 'Jura',
        'Just+Another+Hand' => 'Just Another Hand',
        'Just+Me+Again+Down+Here' => 'Just Me Again Down Here',
        'Kameron' => 'Kameron',
        'Karla' => 'Karla',
        'Kaushan+Script' => 'Kaushan Script',
        'Kavoon' => 'Kavoon',
        'Keania+One' => 'Keania One',
        'Kelly+Slab' => 'Kelly Slab',
        'Kenia' => 'Kenia',
        'Khmer' => 'Khmer',
        'Kite+One' => 'Kite One',
        'Knewave' => 'Knewave',
        'Kotta+One' => 'Kotta One',
        'Koulen' => 'Koulen',
        'Kranky' => 'Kranky',
        'Kreon' => 'Kreon',
        'Kristi' => 'Kristi',
        'Krona+One' => 'Krona One',
        'La+Belle+Aurore' => 'La Belle Aurore',
        'Lancelot' => 'Lancelot',
        'Lato' => 'Lato',
        'League+Script' => 'League Script',
        'Leckerli+One' => 'Leckerli One',
        'Ledger' => 'Ledger',
        'Lekton' => 'Lekton',
        'Lemon' => 'Lemon',
        'Libre+Baskerville' => 'Libre Baskerville',
        'Life+Savers' => 'Life Savers',
        'Lilita+One' => 'Lilita One',
        'Limelight' => 'Limelight',
        'Linden+Hill' => 'Linden Hill',
        'Lobster' => 'Lobster',
        'Lobster+Two' => 'Lobster Two',
        'Londrina+Outline' => 'Londrina Outline',
        'Londrina+Shadow' => 'Londrina Shadow',
        'Londrina+Sketch' => 'Londrina Sketch',
        'Londrina+Solid' => 'Londrina Solid',
        'Lora' => 'Lora',
        'Love+Ya+Like+A+Sister' => 'Love Ya Like A Sister',
        'Loved+by+the+King' => 'Loved by the King',
        'Lovers+Quarrel' => 'Lovers Quarrel',
        'Luckiest+Guy' => 'Luckiest Guy',
        'Lusitana' => 'Lusitana',
        'Lustria' => 'Lustria',
        'Macondo' => 'Macondo',
        'Macondo+Swash+Caps' => 'Macondo Swash Caps',
        'Magra' => 'Magra',
        'Maiden+Orange' => 'Maiden Orange',
        'Mako' => 'Mako',
        'Marcellus' => 'Marcellus',
        'Marcellus+SC' => 'Marcellus SC',
        'Marck+Script' => 'Marck Script',
        'Margarine' => 'Margarine',
        'Marko+One' => 'Marko One',
        'Marmelad' => 'Marmelad',
        'Marvel' => 'Marvel',
        'Mate' => 'Mate',
        'Mate+SC' => 'Mate SC',
        'Maven+Pro' => 'Maven Pro',
        'McLaren' => 'McLaren',
        'Meddon' => 'Meddon',
        'MedievalSharp' => 'MedievalSharp',
        'Medula+One' => 'Medula One',
        'Megrim' => 'Megrim',
        'Meie+Script' => 'Meie Script',
        'Merienda' => 'Merienda',
        'Merienda+One' => 'Merienda One',
        'Merriweather' => 'Merriweather',
        'Merriweather+Sans' => 'Merriweather Sans',
        'Metal' => 'Metal',
        'Metal+Mania' => 'Metal Mania',
        'Metamorphous' => 'Metamorphous',
        'Metrophobic' => 'Metrophobic',
        'Michroma' => 'Michroma',
        'Milonga' => 'Milonga',
        'Miltonian' => 'Miltonian',
        'Miltonian+Tattoo' => 'Miltonian Tattoo',
        'Miniver' => 'Miniver',
        'Miss+Fajardose' => 'Miss Fajardose',
        'Modern+Antiqua' => 'Modern Antiqua',
        'Molengo' => 'Molengo',
        'Molle' => 'Molle',
        'Monda' => 'Monda',
        'Monofett' => 'Monofett',
        'Monoton' => 'Monoton',
        'Monsieur+La+Doulaise' => 'Monsieur La Doulaise',
        'Montaga' => 'Montaga',
        'Montez' => 'Montez',
        'Montserrat' => 'Montserrat',
        'Montserrat+Alternates' => 'Montserrat Alternates',
        'Montserrat+Subrayada' => 'Montserrat Subrayada',
        'Moul' => 'Moul',
        'Moulpali' => 'Moulpali',
        'Mountains+of+Christmas' => 'Mountains of Christmas',
        'Mouse+Memoirs' => 'Mouse Memoirs',
        'Mr+Bedfort' => 'Mr Bedfort',
        'Mr+Dafoe' => 'Mr Dafoe',
        'Mr+De+Haviland' => 'Mr De Haviland',
        'Mrs+Saint+Delafield' => 'Mrs Saint Delafield',
        'Mrs+Sheppards' => 'Mrs Sheppards',
        'Muli' => 'Muli',
        'Mystery+Quest' => 'Mystery Quest',
        'Neucha' => 'Neucha',
        'Neuton' => 'Neuton',
        'New+Rocker' => 'New Rocker',
        'News+Cycle' => 'News Cycle',
        'Niconne' => 'Niconne',
        'Nixie+One' => 'Nixie One',
        'Nobile' => 'Nobile',
        'Nokora' => 'Nokora',
        'Norican' => 'Norican',
        'Nosifer' => 'Nosifer',
        'Nothing+You+Could+Do' => 'Nothing You Could Do',
        'Noticia+Text' => 'Noticia Text',
        'Noto+Sans' => 'Noto Sans',
        'Nova+Cut' => 'Nova Cut',
        'Nova+Flat' => 'Nova Flat',
        'Nova+Mono' => 'Nova Mono',
        'Nova+Oval' => 'Nova Oval',
        'Nova+Round' => 'Nova Round',
        'Nova+Script' => 'Nova Script',
        'Nova+Slim' => 'Nova Slim',
        'Nova+Square' => 'Nova Square',
        'Numans' => 'Numans',
        'Nunito' => 'Nunito',
        'Odor+Mean+Chey' => 'Odor Mean Chey',
        'Offside' => 'Offside',
        'Old+Standard+TT' => 'Old Standard TT',
        'Oldenburg' => 'Oldenburg',
        'Oleo+Script' => 'Oleo Script',
        'Oleo+Script+Swash+Caps' => 'Oleo Script Swash Caps',
        'Open+Sans' => 'Open Sans',
        'Open+Sans+Condensed' => 'Open Sans Condensed',
        'Oranienbaum' => 'Oranienbaum',
        'Orbitron' => 'Orbitron',
        'Oregano' => 'Oregano',
        'Orienta' => 'Orienta',
        'Original+Surfer' => 'Original Surfer',
        'Oswald' => 'Oswald',
        'Over+the+Rainbow' => 'Over the Rainbow',
        'Overlock' => 'Overlock',
        'Overlock+SC' => 'Overlock SC',
        'Ovo' => 'Ovo',
        'Oxygen' => 'Oxygen',
        'Oxygen+Mono' => 'Oxygen Mono',
        'PT+Mono' => 'PT Mono',
        'PT+Sans' => 'PT Sans',
        'PT+Sans+Caption' => 'PT Sans Caption',
        'PT+Sans+Narrow' => 'PT Sans Narrow',
        'PT+Serif' => 'PT Serif',
        'PT+Serif+Caption' => 'PT Serif Caption',
        'Pacifico' => 'Pacifico',
        'Paprika' => 'Paprika',
        'Parisienne' => 'Parisienne',
        'Passero+One' => 'Passero One',
        'Passion+One' => 'Passion One',
        'Patrick+Hand' => 'Patrick Hand',
        'Patrick+Hand+SC' => 'Patrick Hand SC',
        'Patua+One' => 'Patua One',
        'Paytone+One' => 'Paytone One',
        'Peralta' => 'Peralta',
        'Permanent+Marker' => 'Permanent Marker',
        'Petit+Formal+Script' => 'Petit Formal Script',
        'Petrona' => 'Petrona',
        'Philosopher' => 'Philosopher',
        'Piedra' => 'Piedra',
        'Pinyon+Script' => 'Pinyon Script',
        'Pirata+One' => 'Pirata One',
        'Plaster' => 'Plaster',
        'Play' => 'Play',
        'Playball' => 'Playball',
        'Playfair+Display' => 'Playfair Display',
        'Playfair+Display+SC' => 'Playfair Display SC',
        'Podkova' => 'Podkova',
        'Poiret+One' => 'Poiret One',
        'Poller+One' => 'Poller One',
        'Poly' => 'Poly',
        'Pompiere' => 'Pompiere',
        'Pontano+Sans' => 'Pontano Sans',
        'Port+Lligat+Sans' => 'Port Lligat Sans',
        'Port+Lligat+Slab' => 'Port Lligat Slab',
        'Prata' => 'Prata',
        'Preahvihear' => 'Preahvihear',
        'Press+Start+2P' => 'Press Start 2P',
        'Princess+Sofia' => 'Princess Sofia',
        'Prociono' => 'Prociono',
        'Prosto+One' => 'Prosto One',
        'Puritan' => 'Puritan',
        'Purple+Purse' => 'Purple Purse',
        'Quando' => 'Quando',
        'Quantico' => 'Quantico',
        'Quattrocento' => 'Quattrocento',
        'Quattrocento+Sans' => 'Quattrocento Sans',
        'Questrial' => 'Questrial',
        'Quicksand' => 'Quicksand',
        'Quintessential' => 'Quintessential',
        'Qwigley' => 'Qwigley',
        'Racing+Sans+One' => 'Racing Sans One',
        'Radley' => 'Radley',
        'Raleway' => 'Raleway',
        'Raleway+Dots' => 'Raleway Dots',
        'Rambla' => 'Rambla',
        'Rammetto+One' => 'Rammetto One',
        'Ranchers' => 'Ranchers',
        'Rancho' => 'Rancho',
        'Rationale' => 'Rationale',
        'Redressed' => 'Redressed',
        'Reenie+Beanie' => 'Reenie Beanie',
        'Revalia' => 'Revalia',
        'Ribeye' => 'Ribeye',
        'Ribeye+Marrow' => 'Ribeye Marrow',
        'Righteous' => 'Righteous',
        'Risque' => 'Risque',
        'Roboto' => 'Roboto',
        'Roboto+Condensed' => 'Roboto Condensed',
        'Rochester' => 'Rochester',
        'Rock+Salt' => 'Rock Salt',
        'Rokkitt' => 'Rokkitt',
        'Romanesco' => 'Romanesco',
        'Ropa+Sans' => 'Ropa Sans',
        'Rosario' => 'Rosario',
        'Rosarivo' => 'Rosarivo',
        'Rouge+Script' => 'Rouge Script',
        'Ruda' => 'Ruda',
        'Rufina' => 'Rufina',
        'Ruge+Boogie' => 'Ruge Boogie',
        'Ruluko' => 'Ruluko',
        'Rum+Raisin' => 'Rum Raisin',
        'Ruslan+Display' => 'Ruslan Display',
        'Russo+One' => 'Russo One',
        'Ruthie' => 'Ruthie',
        'Rye' => 'Rye',
        'Sacramento' => 'Sacramento',
        'Sail' => 'Sail',
        'Salsa' => 'Salsa',
        'Sanchez' => 'Sanchez',
        'Sancreek' => 'Sancreek',
        'Sansita+One' => 'Sansita One',
        'Sarina' => 'Sarina',
        'Satisfy' => 'Satisfy',
        'Scada' => 'Scada',
        'Schoolbell' => 'Schoolbell',
        'Seaweed+Script' => 'Seaweed Script',
        'Sevillana' => 'Sevillana',
        'Seymour+One' => 'Seymour One',
        'Shadows+Into+Light' => 'Shadows Into Light',
        'Shadows+Into+Light+Two' => 'Shadows Into Light Two',
        'Shanti' => 'Shanti',
        'Share' => 'Share',
        'Share+Tech' => 'Share Tech',
        'Share+Tech+Mono' => 'Share Tech Mono',
        'Shojumaru' => 'Shojumaru',
        'Short+Stack' => 'Short Stack',
        'Siemreap' => 'Siemreap',
        'Sigmar+One' => 'Sigmar One',
        'Signika' => 'Signika',
        'Signika+Negative' => 'Signika Negative',
        'Simonetta' => 'Simonetta',
        'Sintony' => 'Sintony',
        'Sirin+Stencil' => 'Sirin Stencil',
        'Six+Caps' => 'Six Caps',
        'Skranji' => 'Skranji',
        'Slackey' => 'Slackey',
        'Smokum' => 'Smokum',
        'Smythe' => 'Smythe',
        'Sniglet' => 'Sniglet',
        'Snippet' => 'Snippet',
        'Snowburst+One' => 'Snowburst One',
        'Sofadi+One' => 'Sofadi One',
        'Sofia' => 'Sofia',
        'Sonsie+One' => 'Sonsie One',
        'Sorts+Mill+Goudy' => 'Sorts Mill Goudy',
        'Source+Code+Pro' => 'Source Code Pro',
        'Source+Sans+Pro' => 'Source Sans Pro',
        'Special+Elite' => 'Special Elite',
        'Spicy+Rice' => 'Spicy Rice',
        'Spinnaker' => 'Spinnaker',
        'Spirax' => 'Spirax',
        'Squada+One' => 'Squada One',
        'Stalemate' => 'Stalemate',
        'Stalinist+One' => 'Stalinist One',
        'Stardos+Stencil' => 'Stardos Stencil',
        'Stint+Ultra+Condensed' => 'Stint Ultra Condensed',
        'Stint+Ultra+Expanded' => 'Stint Ultra Expanded',
        'Stoke' => 'Stoke',
        'Strait' => 'Strait',
        'Sue+Ellen+Francisco' => 'Sue Ellen Francisco',
        'Sunshiney' => 'Sunshiney',
        'Supermercado+One' => 'Supermercado One',
        'Suwannaphum' => 'Suwannaphum',
        'Swanky+and+Moo+Moo' => 'Swanky and Moo Moo',
        'Syncopate' => 'Syncopate',
        'Tangerine' => 'Tangerine',
        'Taprom' => 'Taprom',
        'Tauri' => 'Tauri',
        'Telex' => 'Telex',
        'Tenor+Sans' => 'Tenor Sans',
        'Text+Me+One' => 'Text Me One',
        'The+Girl+Next+Door' => 'The Girl Next Door',
        'Tienne' => 'Tienne',
        'Tinos' => 'Tinos',
        'Titan+One' => 'Titan One',
        'Titillium+Web' => 'Titillium Web',
        'Trade+Winds' => 'Trade Winds',
        'Trocchi' => 'Trocchi',
        'Trochut' => 'Trochut',
        'Trykker' => 'Trykker',
        'Tulpen+One' => 'Tulpen One',
        'Ubuntu' => 'Ubuntu',
        'Ubuntu+Condensed' => 'Ubuntu Condensed',
        'Ubuntu+Mono' => 'Ubuntu Mono',
        'Ultra' => 'Ultra',
        'Uncial+Antiqua' => 'Uncial Antiqua',
        'Underdog' => 'Underdog',
        'Unica+One' => 'Unica One',
        'UnifrakturCook' => 'UnifrakturCook',
        'UnifrakturMaguntia' => 'UnifrakturMaguntia',
        'Unkempt' => 'Unkempt',
        'Unlock' => 'Unlock',
        'Unna' => 'Unna',
        'VT323' => 'VT323',
        'Vampiro+One' => 'Vampiro One',
        'Varela' => 'Varela',
        'Varela+Round' => 'Varela Round',
        'Vast+Shadow' => 'Vast Shadow',
        'Vibur' => 'Vibur',
        'Vidaloka' => 'Vidaloka',
        'Viga' => 'Viga',
        'Voces' => 'Voces',
        'Volkhov' => 'Volkhov',
        'Vollkorn' => 'Vollkorn',
        'Voltaire' => 'Voltaire',
        'Waiting+for+the+Sunrise' => 'Waiting for the Sunrise',
        'Wallpoet' => 'Wallpoet',
        'Walter+Turncoat' => 'Walter Turncoat',
        'Warnes' => 'Warnes',
        'Wellfleet' => 'Wellfleet',
        'Wendy+One' => 'Wendy One',
        'Wire+One' => 'Wire One',
        'Yanone+Kaffeesatz' => 'Yanone Kaffeesatz',
        'Yellowtail' => 'Yellowtail',
        'Yeseva+One' => 'Yeseva One',
        'Yesteryear' => 'Yesteryear',
        'Zeyada' => 'Zeyada'
	);
}

/**
 * Get Save Web Fonts
 * @return array
 */
function ale_get_safe_webfonts() {
	return array(
		'Arial'				=> 'Arial',
		'Verdana'			=> 'Verdana, Geneva',
		'Trebuchet'			=> 'Trebuchet',
		'Georgia'			=> 'Georgia',
		'Times New Roman'   => 'Times New Roman',
		'Tahoma'			=> 'Tahoma, Geneva',
		'Palatino'			=> 'Palatino',
		'Helvetica'			=> 'Helvetica',
		'Gill Sans'			=> 'Gill Sans',
	);
}

function ale_get_typo_styles() {
	return array(
		'normal'      => 'Normal',
		'italic'      => 'Italic',
	);
}

function ale_get_typo_weights() {
	return array(
		'normal'      => 'Normal',
		'bold'      => 'Bold',
	);
}

function ale_get_typo_transforms() {
	return array(
		'none'      => 'None',
		'uppercase'	=> 'UPPERCASE',
		'lowercase'	=> 'lowercase',
		'capitalize'=> 'Capitalize',
	);
}

function ale_get_typo_variants() {
	return array(
		'normal'      => 'normal',
		'small-caps'  => 'Small Caps',
	);
}

/**
 * Get default font styles
 * @return array
 */
function ale_get_font_styles() {
	return array(
		'normal'      => 'Normal',
		'italic'      => 'Italic',
		'bold'        => 'Bold',
		'bold italic' => 'Bold Italic'
	);
}

/**
 * Display custom RSS url
 */
function ale_rss() {
    echo ale_get_rss();
}

/**
 * Get custom RSS url
 */
function ale_get_rss() {
    $rss_url = ale_get_option('feedburner');
    return $rss_url ? $rss_url : get_bloginfo('rss2_url');
}

/**
 * Display custom RSS url
 */
function ale_favicon() {
    echo ale_get_favicon();
}

/**
 * Get custom RSS url
 */
function ale_get_favicon() {
    $favicon = ale_get_option('favicon');
    return $favicon ? $favicon : THEME_URL . '/aletheme/assets/favicon.ico';
}

/**
 * Get template part
 * 
 * @param string $slug
 * @param string $name
 */
function ale_part($slug, $name = null) {
	get_template_part('partials/' . $slug, $name);
}

/**
 * Page Title Wrapper
 * @param type $title 
 */
function ale_page_title($title) {
	echo ale_get_page_title($title);
}
function ale_get_page_title($title) {
	return '<header class="page-title"><h2 class="a">' . $title . '</h2></header>';
}

/**
 * Find if the current browser is on mobile device
 * @return boolean 
 */
function is_mobile() {
	if(preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipad|ipaq|ipod|j2me|java|midp|mini|mmp|mobi|motorola|nec-|nokia|palm|panasonic|philips|phone|sagem|sharp|sie-|smartphone|sony|symbian|t-mobile|telus|up\.browser|up\.link|vodafone|wap|webos|wireless|xda|xoom|zte)/i', $_SERVER['HTTP_USER_AGENT'])) {
		return true;
	} else {
		return false;
	}
}

function array_put_to_position(&$array, $object, $position, $name = null) {
	$count = 0;
	$return = array();
	foreach ($array as $k => $v) {  
			// insert new object
			if ($count == $position) {  
					if (!$name) $name = $count;
					$return[$name] = $object;
					$inserted = true;
			}  
			// insert old object
			$return[$k] = $v;
			$count++;
	}  
	if (!$name) $name = $count;
	if (!$inserted) $return[$name];
	$array = $return;
	return $array;
}


/**
 * Get archives by year
 * 
 * @global object $wpdb
 * @param string $year
 * @return array 
 */
function ale_archives_get_by_year($year = "") {
	global $wpdb;
	
	$where = "";
	if (!empty($year)) {
		$where = "AND YEAR(post_date) = " . ((int) $year);
	}
	$query = "SELECT DISTINCT YEAR(post_date) AS `year`, MONTH(post_date) AS `month`, DATE_FORMAT(post_date, '%b') AS `abmonth`, DATE_FORMAT(post_date, '%M') AS `fmonth`, count(ID) as posts
									FROM $wpdb->posts
							WHERE post_type = 'post' AND post_status = 'publish' $where
									GROUP BY YEAR(post_date), MONTH(post_date)
									ORDER BY post_date DESC";

	return $wpdb->get_results($query);
}

/**
 * Get archives years list
 * 
 * @global object $wpdb
 * @return array 
 */
function ale_archives_get_years() {
	global $wpdb;

	$query = "SELECT DISTINCT YEAR(post_date) AS `year`
									FROM $wpdb->posts
							WHERE post_type = 'post' AND post_status = 'publish'
									GROUP BY YEAR(post_date) ORDER BY post_date DESC";

	return $wpdb->get_results($query);
}

/**
 * Get archives months list
 * 
 * @return type 
 */
function ale_archives_get_months() {
	return array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
}

/**
 * Display Archives 
 */
function ale_archives($delim = '&nbsp;/&nbsp;') {
    $year = null;
    ?>
    <div class="ale-archives cf">
        <ul class="left">
            <li><?php _e('Archives', 'aletheme');?></li>
            <li>
                <ul>
                    <li><a href="#" class="down">&lt;</a></li>
                    <li><span id="archives-active-year"></span></li>
                    <li><a href="#" class="up">&gt;</a></li>
                </ul>
            </li>
        </ul>
        <?php
            $months = ale_archives_get_months();
            $archives = ale_archives_get_by_year();
        ?>
        <div class="right">
            <div class="months">
                <?php foreach ($archives as $archive) : ?>
                    <?php
                        if ($year == $archive->year) {
                            continue;
                        }
                        $year = $archive->year;
                        $y_archives = ale_archives_get_by_year($archive->year);
                    ?>
                    <div class="year-months" id="archive-year-<?php echo $year?>">
                    <?php foreach ($months as $key => $month) :?>
                        <?php foreach ($y_archives as $y_archive) :?>
                            <?php if (($key == ($y_archive->month-1)) && $y_archive->posts):?>
                                <a href="<?php echo get_month_link($year, $y_archive->month)?>"><?php echo $month; ?></a>
                                <?php if ($key != 11 && $delim):?>
                                    <span class="delim"><?php echo $delim; ?></span>
                                <?php endif;?>
                                <?php break;?>
                            <?php endif;?>
                        <?php endforeach;?>
                        <?php if ($key != $y_archive->month-1):?>
                            <span><?php echo $month; ?></span>
                            <?php if ($key != 11 && $delim):?>
                                <span class="delim"><?php echo $delim; ?></span>
                            <?php endif;?>
                        <?php endif;?>
                    <?php endforeach;?>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
<?php
}

/**
 * Add combined actions for AJAX.
 * 
 * @param string $tag
 * @param string $function_to_add
 * @param integer $priority
 * @param integer $accepted_args 
 */
function ale_add_ajax_action($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
	add_action('wp_ajax_' . $tag, $function_to_add, $priority, $accepted_args);
	add_action('wp_ajax_nopriv_' . $tag, $function_to_add, $priority, $accepted_args);
}

/**
 * Get contact form 7 from content
 * @param string $content
 * @return string 
 */
function ale_contact7_form($content) {
	$matches = array();
	preg_match('~(\[contact\-form\-7.*\])~simU', $content, $matches);
	return $matches[1];
}

/**
 * Remove contact form from content
 * @param string $content
 * @return string
 */
function ale_remove_contact7_form($content) {
	$content = preg_replace('~(\[contact\-form\-7.*\])~simU', '', $content);
	return $content;
}

/**
 * Check if it's a blog page
 * @global object $post
 * @return boolean 
 */
function ale_is_blog () {
	global  $post;
	$posttype = get_post_type($post);
	return ( ((is_archive()) || (is_author()) || (is_category()) || (is_home()) || (is_single()) || (is_tag())) && ($posttype == 'post')) ? true : false ;
}

if ( function_exists('register_sidebar') ) {

        register_sidebar(array(
            'name' => 'Main Sidebar',
            'id' => 'main-sidebar',
            'description' => 'Appears as the left sidebar on Blog pages',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<p class="caption">',
            'after_title' => '</p><div class="line"></div>',
        ));

}

//Support automatic-feed-links
add_theme_support( 'automatic-feed-links' );

//Unreal construction to passed/hide "Theme Checker Plugin" recommendation about Header nad Background
if('Theme Checke' == 'Hide') {
    add_theme_support( 'custom-header');
    add_theme_support( 'custom-background');
}

//Comment Reply script
function aletheme_enqueue_comment_reply() {
    // on single blog post pages with comments open and threaded comments
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        // enqueue the javascript that performs in-link comment reply fanciness
        wp_enqueue_script( 'comment-reply' );
    }
}
// Hook into wp_enqueue_scripts
add_action( 'wp_enqueue_scripts', 'aletheme_enqueue_comment_reply' );

/**
 * Remove HTML attributes from comments if is Socha Comments Selected
 */
if(ale_get_option('comments_style') == 'wp'){
    add_filter( 'comment_text', 'wp_filter_nohtml_kses' );
    add_filter( 'comment_text_rss', 'wp_filter_nohtml_kses' );
    add_filter( 'comment_excerpt', 'wp_filter_nohtml_kses' );
}

function ale_trim_excerpt($length) {
    global $post;
    $explicit_excerpt = $post->post_excerpt;
    if ( '' == $explicit_excerpt ) {
        $text = get_the_content('');
        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]>', $text);
    }
    else {
        $text = apply_filters('the_content', $explicit_excerpt);
    }
    $text = strip_shortcodes( $text ); // optional
    $text = strip_tags($text);
    $excerpt_length = $length;
    $words = explode(' ', $text, $excerpt_length + 1);
    if (count($words)> $excerpt_length) {
        array_pop($words);
        array_push($words, '[&hellip;]');
        $text = implode(' ', $words);
        $text = apply_filters('the_excerpt',$text);
    }
    return $text;
}





// Breadcrumbs Custom Function

function get_breadcrumbs() {

    $text['home']     = __('Home','aletheme');
    $text['category'] = __('Archive','aletheme').' "%s"';
    $text['search']   = __('Search results','aletheme').' "%s"';
    $text['tag']      = __('Tag','aletheme').' "%s"';
    $text['author']   = __('Author','aletheme').' %s';
    $text['404']      = __('Error 404','aletheme');

    $show_current   = 1;
    $show_on_home   = 0;
    $show_home_link = 1;
    $show_title     = 1;
    $delimiter      = '&nbsp; › &nbsp;';
    $before         = '<span class="current">';
    $after          = '</span>';

    global $post;
    $home_link    = home_url('/');
    $link_before  = '<span typeof="v:Breadcrumb">';
    $link_after   = '</span>';
    $link_attr    = ' rel="v:url" property="v:title"';
    $link         = $link_before . '<a' . $link_attr . ' href="%1$s">%2$s</a>' . $link_after;
    $parent_id    = $parent_id_2 = $post->post_parent;
    $frontpage_id = get_option('page_on_front');

    if (is_home() || is_front_page()) {

        if ($show_on_home == 1) echo '<div class="breadcrumbs"><a href="' . $home_link . '">' . $text['home'] . '</a></div>';

    }
    else {

        echo '<div class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">';
        if ($show_home_link == 1) {
            echo sprintf($link, $home_link, $text['home']);
            if ($frontpage_id == 0 || $parent_id != $frontpage_id) echo $delimiter;
        }

        if ( is_category() ) {
            $this_cat = get_category(get_query_var('cat'), false);
            if ($this_cat->parent != 0) {
                $cats = get_category_parents($this_cat->parent, TRUE, $delimiter);
                if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
                $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
                $cats = str_replace('</a>', '</a>' . $link_after, $cats);
                if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
                echo $cats;
            }
            if ($show_current == 1) echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;

        } elseif ( is_search() ) {
            echo $before . sprintf($text['search'], get_search_query()) . $after;

        } elseif ( is_day() ) {
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
            echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;
            echo $before . get_the_time('d') . $after;

        } elseif ( is_month() ) {
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
            echo $before . get_the_time('F') . $after;

        } elseif ( is_year() ) {
            echo $before . get_the_time('Y') . $after;

        } elseif ( is_single() && !is_attachment() ) {
            if ( get_post_type() != 'post' ) {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                printf($link, $home_link . '/' . $slug['slug'] . '/', $post_type->labels->singular_name);
                if ($show_current == 1) echo $delimiter . $before . get_the_title() . $after;
            } else {
                $cat = get_the_category(); $cat = $cat[0];
                $cats = get_category_parents($cat, TRUE, $delimiter);
                if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
                $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
                $cats = str_replace('</a>', '</a>' . $link_after, $cats);
                if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
                echo $cats;
                if ($show_current == 1) echo $before . get_the_title() . $after;
            }

        } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
            $post_type = get_post_type_object(get_post_type());
            echo $before . $post_type->labels->singular_name . $after;

        } elseif ( is_attachment() ) {
            $parent = get_post($parent_id);
            $cat = get_the_category($parent->ID); $cat = $cat[0];
            $cats = get_category_parents($cat, TRUE, $delimiter);
            $cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
            $cats = str_replace('</a>', '</a>' . $link_after, $cats);
            if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
            echo $cats;
            printf($link, get_permalink($parent), $parent->post_title);
            if ($show_current == 1) echo $delimiter . $before . get_the_title() . $after;

        } elseif ( is_page() && !$parent_id ) {
            if ($show_current == 1) echo $before . get_the_title() . $after;

        } elseif ( is_page() && $parent_id ) {
            if ($parent_id != $frontpage_id) {
                $breadcrumbs = array();
                while ($parent_id) {
                    $page = get_page($parent_id);
                    if ($parent_id != $frontpage_id) {
                        $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
                    }
                    $parent_id = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                for ($i = 0; $i < count($breadcrumbs); $i++) {
                    echo $breadcrumbs[$i];
                    if ($i != count($breadcrumbs)-1) echo $delimiter;
                }
            }
            if ($show_current == 1) {
                if ($show_home_link == 1 || ($parent_id_2 != 0 && $parent_id_2 != $frontpage_id)) echo $delimiter;
                echo $before . get_the_title() . $after;
            }

        } elseif ( is_tag() ) {
            echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;

        } elseif ( is_author() ) {
            global $author;
            $userdata = get_userdata($author);
            echo $before . sprintf($text['author'], $userdata->display_name) . $after;

        } elseif ( is_404() ) {
            echo $before . $text['404'] . $after;
        }

        if ( get_query_var('paged') ) {
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
            echo __('Page') . ' ' . get_query_var('paged');
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
        }

        echo '</div><!-- .breadcrumbs -->';

    }
}


// TGM Script code

add_action( 'tgmpa_register', 'aletheme_register_required_plugins' );
function aletheme_register_required_plugins() {

    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        //Example Init
        array(
            'name'      => 'Akismet',
            'slug'      => 'akismet',
            'required'  => false,
        ),
    );

    // Change this to your theme text domain, used for internationalising strings
    $theme_text_domain = 'aletheme';

    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'domain'         => $theme_text_domain,          // Text domain - likely want to be the same as your theme.
        'default_path'   => '',                          // Default absolute path to pre-packaged plugins
        'parent_menu_slug'  => 'themes.php',     // Default parent menu slug
        'parent_url_slug'  => 'themes.php',     // Default parent URL slug
        'menu'           => 'install-required-plugins',  // Menu slug
        'has_notices'       => true,                        // Show admin notices or not
        'is_automatic'     => false,         // Automatically activate plugins after installation or not
        'message'    => '',       // Message to output right before the plugins table
        'strings'        => array(
            'page_title'                          => __( 'Install Required Plugins', $theme_text_domain ),
            'menu_title'                          => __( 'Install Plugins', $theme_text_domain ),
            'installing'                          => __( 'Installing Plugin: %s', $theme_text_domain ), // %1$s = plugin name
            'oops'                                => __( 'Something went wrong with the plugin API.', $theme_text_domain ),
            'notice_can_install_required'        => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
            'notice_can_install_recommended'   => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
            'notice_cannot_install'       => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
            'notice_can_activate_required'       => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
            'notice_can_activate_recommended'   => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
            'notice_cannot_activate'      => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
            'notice_ask_to_update'       => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
            'notice_cannot_update'       => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
            'install_link'           => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
            'activate_link'          => _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
            'return'                              => __( 'Return to Required Plugins Installer', $theme_text_domain ),
            'plugin_activated'                    => __( 'Plugin activated successfully.', $theme_text_domain ),
            'complete'          => __( 'All plugins installed and activated successfully. %s', $theme_text_domain ), // %1$s = dashboard link
            'nag_type'         => 'updated' // Determines admin notice type - can only be 'updated' or 'error'
        )
    );

    tgmpa( $plugins, $config );

}