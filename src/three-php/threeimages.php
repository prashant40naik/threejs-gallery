<?php

add_action( 'init', 'three_image_urls' );

function three_image_urls() {
$query_images_args = array(
	            'post_type'      => 'attachment',
				'post_mime_type' => 'image/jpeg,image/jpg',
				'post_status'    => 'inherit',
				'posts_per_page' => -1,
				);

    $sizes = get_intermediate_image_sizes();
    $query_images = new WP_Query ( $query_images_args );

	$images = array();
	if ( $query_images->have_posts() ){

			while ($query_images->have_posts()){
				$query_images->the_post();
				foreach ( $sizes as $key => $size ) {
					$thumbnails[$key] = wp_get_attachment_image_src( get_the_ID(), $size)[0];
				}
				$images = array_merge( $thumbnails , $images );
			 }
		}
		echo "<div id='three-images' class='three-images-js' style='display:block'>";
		echo '<script>var threeimages=' .json_encode($images) .';</script>';
		echo print_r($image);
		echo "</div>";

}
?>
