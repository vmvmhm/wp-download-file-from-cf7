<?php

add_action( 'init', 'dfcf7_post_type' );
add_action( 'init', 'create_groups_dfcf7_taxonomy', 0 );
add_action( 'save_post', 'save_url_data');

add_shortcode('get-download-checkbox', 'make_download');
