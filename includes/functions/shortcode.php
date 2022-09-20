<?php 

function get_downloads_from_checkbox() {
		$links_to_be_downloaded = array();
		$all_get_params = array_keys($_GET);

		$boxes_with_links = get_all_download_links_by_checkbox();
			foreach ($boxes_with_links as $boxes_with_linksArray) {
				foreach ($all_get_params as $get_param) {
					if (in_array($get_param,$boxes_with_linksArray[0])) {
						$links_to_be_downloaded[] = $boxes_with_linksArray[1];
					}
				}
			}



	return $links_to_be_downloaded;
}



function get_all_download_links_by_checkbox(){
	$posts = get_posts([
  'post_type' => 'dfcf7',
  'post_status' => 'publish',
  'numberposts' => -1
  // 'order'    => 'ASC'
]);
$boxes_with_url = array();
	foreach($posts as $p){
	    $url = get_post_meta($p->ID,"url_template",true);
		$checkboxs = maybe_unserialize(get_post_meta($p->ID, '_acceptance_boxes', true));
		$boxes_with_url[] = array($checkboxs, $url);

	}
return $boxes_with_url;

}



function make_download(){
$list_files = get_downloads_from_checkbox();

	foreach ($list_files as $file_url) {
		header('Content-Type: application/octet-stream');
		header("Content-Transfer-Encoding: Binary"); 
		header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
		readfile($file_url);
		echo $file_url;
	}

}