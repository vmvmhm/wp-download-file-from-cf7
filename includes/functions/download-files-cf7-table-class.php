<?php
if ( ! defined('ABSPATH') ) {
    die('Direct access not permitted.');
}


function dfcf7_post_type() {
    register_post_type( 'dfcf7',
        array(
            'labels' => array(
                'name' => __( 'DownloadFiles CF7' ),
                'singular_name' => __( 'DownloadFile CF7' )
            ),
            'public' => true,
            'show_in_rest' => true,
        'supports' => array('title'),
        'has_archive' => true,
        'rewrite'   => array( 'slug' => 'download-files-cf7' ),
        //    'menu_position' => 5,
        'menu_icon' => 'dashicons-download',
        // 'taxonomies' => array('cuisines', 'post_tag') // this is IMPORTANT
         'register_meta_box_cb' => 'add_url_metaboxes',
          //Registramos el Metabox

        )
    );
}


//// Add cuisines taxonomy
function create_groups_dfcf7_taxonomy() {
    register_taxonomy('dfcf7-categories','dfcf7',array(
        'hierarchical' => false,
        'labels' => array(
            'name' => _x( 'Groups', 'taxonomy general name' ),
            'singular_name' => _x( 'Group', 'taxonomy singular name' ),
            'menu_name' => __( 'Groups' ),
            'all_items' => __( 'All Groups' ),
            'edit_item' => __( 'Edit Group' ), 
            'update_item' => __( 'Update Group' ),
            'add_new_item' => __( 'Add Group' ),
            'new_item_name' => __( 'New Group' ),
        ),
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    ));

}


function add_url_metaboxes() {
	add_meta_box('url_live_template', 'URL Downloadable file', 'url_live_template', 'dfcf7', 'normal', 'default');
	add_meta_box('acceptance_fields', 'Acceptance fields', 'acceptance_fields', 'dfcf7', 'normal', 'default');

}


function url_live_template() {
	global $post;
	$url_template = get_post_meta($post->ID, 'url_template', true);
	echo '<input style= "width:50%" type="url" name="url_template" value="' . $url_template  . '" />';
}

function acceptance_fields() {

	global $post;

    wp_nonce_field( basename(__FILE__), 'mam_nonce' );
    $postmeta = maybe_unserialize( get_post_meta( $post->ID, '_acceptance_boxes', true ) );
        if (!is_array($postmeta)) {
            $postmeta = array();
        }

/*Gets all cf7 forms ID*/
    $cf7_id_array = array();
    if (post_type_exists('wpcf7_contact_form')) {
        $args = array('post_type' => 'wpcf7_contact_form', 'post_per_page' => -1);
        $the_query = new WP_Query($args);
        if ($the_query->have_posts()) {
            while ($the_query->have_posts()) {
                $the_query->the_post();
                $cf7_id_array[] = get_the_ID();
            }
            wp_reset_postdata();
        }
    }

foreach ($cf7_id_array as $form_id) {

    $ContactForm = WPCF7_ContactForm::get_instance( $form_id );
    $form_fields = $ContactForm->scan_form_tags();

    echo "<h3>Form: " . get_the_title($form_id) . "</h3>";

    foreach ($form_fields as $key) {
    	if ($key->type == "acceptance" or $key->type == "acceptance*") {
    		

    		if (is_array( $postmeta ) && in_array( $key->raw_name, $postmeta )) {
    			$checked = 'checked="checked"';
    		}else{
    			$checked = '';
    		}

            $used = get_all_used_checkbox();

            if (is_array($used) && in_array_recursive($key->raw_name, $used) and !in_array($key->raw_name, $postmeta)) {
                $disabled = 'disabled';
                $checked = 'checked="checked"';

            }else{
                $disabled = '';
            }

    		echo "<div>";
    		echo " <input type = 'checkbox' name = '_acceptance_boxes[]' value = '".$key->raw_name."' $checked $disabled/>" .$key->raw_name;
            if ($disabled) {
                echo "<span style = 'color: gray; font-size: 12px; font-style: italic;'> - (Used for another download)</span>";
            }
    		echo "</div>";
    	}
    }

}

}




function in_array_recursive($needle, $haystack) { 
    $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($haystack)); 
    foreach($it AS $element) { 
        if($element == $needle) { 
            return true; 
        } 
    } 
    return false; 
} 


function save_url_data($post_id){
	global $post;
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'mam_nonce' ] ) && wp_verify_nonce( $_POST[ 'mam_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }

    // If the checkbox was not empty, save it as array in post meta
    if ( ! empty( $_POST['_acceptance_boxes'] ) ) {
        update_post_meta( $post_id, '_acceptance_boxes', $_POST['_acceptance_boxes'] );
    // Otherwise just delete it if its blank value.
    } else {
        delete_post_meta( $post_id, '_acceptance_boxes' );
    }

    // If the url was not empty, save it
    if ( ! empty($_POST['url_template']) and wp_http_validate_url($_POST['url_template'])) {
        update_post_meta( $post_id, 'url_template', $_POST['url_template'] );
    // Otherwise just delete it if its blank value.
    } else {
        delete_post_meta( $post_id, 'url_template' );
    }

}



function get_all_used_checkbox(){
    $posts = get_posts([
      'post_type' => 'dfcf7',
      'post_status' => 'publish',
      'numberposts' => -1
      // 'order'    => 'ASC'
    ]);


    $checkbox_used = array();
    foreach($posts as $p){
        $checkbox = get_post_meta($p->ID,"_acceptance_boxes",true);
        if( ! in_array( $checkbox, $checkbox_used) )
            $checkbox_used[] = array($checkbox, $p->ID);
    }
    return $checkbox_used;
}