<?php

$timebeforerevote = 1440;

add_action('wp_ajax_nopriv_post-like', 'post_like');
add_action('wp_ajax_post-like', 'post_like');



function post_like()
{
    $nonce = $_POST['nonce'];

    if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
        die ( 'Busted!');

    if(isset($_POST['post_like']))
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $post_id = $_POST['post_id'];

        $meta_IP = get_post_meta($post_id, "voted_IP");

        $voted_IP = '';

        if(!is_array($voted_IP))
            $voted_IP = array();

        $meta_count = get_post_meta($post_id, "votes_count", true);

        if(!hasAlreadyVoted($post_id))
        {
            $voted_IP[$ip] = time();

            update_post_meta($post_id, "voted_IP", $voted_IP);
            update_post_meta($post_id, "votes_count", ++$meta_count);

            echo $meta_count;
        }
        else
            echo "already";
    }
    exit;
}

function hasAlreadyVoted($post_id)
{
    global $timebeforerevote;

    $meta_IP = get_post_meta($post_id, "voted_IP");
    if($meta_IP) {
        $voted_IP = $meta_IP['0'];
    } else {
        $voted_IP = "0";
    }
    if(!is_array($voted_IP))
        $voted_IP = array();
        $ip = $_SERVER['REMOTE_ADDR'];

    if(in_array($ip, array_keys($voted_IP)))
    {
        //check if ip is defined
        if(!isset($ip)){$ip = '0';}

        $time = $voted_IP[$ip];
        $now = time();

        if(round(($now - $time) / 60) > $timebeforerevote)
            return false;

        return true;
    }

    return false;
}

function getPostLikeLink($post_id)
{

    $vote_count = get_post_meta($post_id, "votes_count", true);
    if($vote_count == ''){$vote_count=0;}

    $output = '<p class="post-like">';
    if(hasAlreadyVoted($post_id))
        $output .= ' <span title="I like this article" class="qtip like alreadyvoted"></span>';
    else
        $output .= '<a href="#" data-post_id="'.$post_id.'">
					<span  title="I like this article" class="qtip like"></span>
				</a>';
    $output .= '<span class="count">'.$vote_count.'</span></p>';

    return $output;
}

function getLikeCount($post_id)
{

    $vote_count = get_post_meta($post_id, "votes_count", true);
    if($vote_count == ''){$vote_count=0;}


    $output = $vote_count;

    return $output;
}