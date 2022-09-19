<?php
if ( ! defined('ABSPATH') ) {
    die('Direct access not permitted.');
}


function recipes_post_type() {
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
function create_recipes_taxonomy() {
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
	add_meta_box('url_live_template2', 'Acceptance fields', 'url_live_template2', 'dfcf7', 'normal', 'default');

}


function url_live_template() {
	global $post;
	
	// Añadimos un 'noncename' que necesitaremos para verificar los datos y de dónde vienen.
	echo '<input type="hidden" name="urltemplate_noncename" id="urltemplate_noncename" value="' . 
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
	// Recuperar los datos existentes, si es que hay datos existentes.
	$url_template = get_post_meta($post->ID, 'url_template', true);
	
	// El input que aparecerá en administración donde introducir/mostrar los datos
	echo '<input type="text" name="url_template" value="' . $url_template  . '" />';

}

function url_live_template2() {
$form_ID     = 93; # change the 1538 to your CF7 form ID
$ContactForm = WPCF7_ContactForm::get_instance( $form_ID );
$form_fields = $ContactForm->scan_form_tags();
echo "<pre>";
//var_dump( $form_fields[0] );
echo "</pre>";


foreach ($form_fields as $key) {
	//echo $key->type;

	if ($key->type == "acceptance" or $key->type == "acceptance*") {
		//echo $key->raw_name . "<br />";
		echo "<div>";
		echo $key->raw_name ." <input type = 'checkbox' value = '".$key->raw_name."' />";
		echo "</div>";
	}
}


}

/*----------------------------------------------*/
//		ESTA ES LA FUNCION QUE GRABA LOS DATOS, PARA REVISAR

/*
function save_url_live_template($post_id, $post) {
	
	// Verificamos que los datos vienen del metabox donde se encuentra el noncename que hemos establecido anteriormente
	if ( !wp_verify_nonce( $_POST['urltemplate_noncename'], plugin_basename(__FILE__) )) {
	return $post->ID;
	}

	// Verificamos si el usuario tiene autorización para editar el post
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;

	// Incluimos los datos en un array para poder hacer el foreach de más abajo.
	
	$url_meta['url_template'] = $_POST['url_template'];
	
	foreach ($url_meta as $key => $value) { 
		if( $post->post_type == 'revision' ) return; // Verificamos que no se trate de una revisión.
		if(get_post_meta($post->ID, $key, false)) { // Si ya tiene un valor, lo actualizamos.
			update_post_meta($post->ID, $key, $value);
		} else { // Si no tiene un valor, lo creamos.
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key); // Si está en blanco, lo borramos.
	}

}

add_action('save_post', 'save_url_live_template', 1, 2); // Guardamos los datos

*/






/*
function prefix_teammembers_metaboxes( ) {
   global $wp_meta_boxes;
   add_meta_box('postfunctiondiv', __('FILE URL'), 'prefix_teammembers_metaboxes_html', 'dfcf7', 'normal', 'high');
}
add_action( 'add_meta_boxes_dfcf7', 'prefix_teammembers_metaboxes' );

function prefix_teammembers_metaboxes_html()
{
    global $post;
    $custom = get_post_custom($post->ID);
    $downloadurlcf7 = isset($custom["downloadurlcf7"][0])?$custom["downloadurlcf7"][0]:'';
?>
    <input style = "width: 100%;" name="downloadurlcf7" value="<?php echo $downloadurlcf7; ?>">
<?php
}

function prefix_teammembers_save_post()
{
    if(empty($_POST)) return; //why is prefix_teammembers_save_post triggered by add new? 
    global $post;
    update_post_meta($post->ID, "downloadurlcf7", $_POST["downloadurlcf7"]);
}   

add_action( 'save_post_dfcf7', 'prefix_teammembers_save_post' ); 


*/