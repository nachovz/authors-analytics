<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/nachovz
 * @since      1.0.0
 *
 * @package    Authors_Analytics
 * @subpackage Authors_Analytics/admin/partials
 */
 
?>
<div class="wrap">
	<h2>Welcome To My Plugin</h2>
</div>
<?php

if ( is_user_logged_in() ):

    global $current_user;
    wp_get_current_user();
    $author_query = array('posts_per_page' => '-1','author' => $current_user->ID);
    $author_posts = new WP_Query($author_query);
    while($author_posts->have_posts()) : $author_posts->the_post();
    ?>
        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>       
    <?php           
    endwhile;

else :

    echo "not logged in";

endif;
?>


<!-- This file should primarily consist of HTML with a little bit of PHP. -->


