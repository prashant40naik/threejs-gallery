<?php
/*
Plugin Name: WPThreeJS
Description: Get all the image urls on WP and store it on S3 for Three JS  Camera Cinematic.
Version: 0.1.0
Author: Prashant Naik
Author URI: https://www.naikonpixels.com
Text Domain: naikonpixels
 */
$abspath = plugin_dir_path( __DIR__ );
//require $abspath . 'amazon-s3-and-cloudfront/vendor/Aws3/aws-autoloader.php';
require $abspath .'/vendor/autoload.php';
use Aws\S3\S3Client;

add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'feed_links_show_comments_feed', '__return_false' );

add_action( 'init', 'wpthreejs_cleanup' );

function wpthreejs_cleanup() {
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'rest_output_link_wp_head', 10);
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10);
	remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0);
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'rel_canonical' );
}

add_filter( 'aws_get_client_args', 'wpthreejs_filter_aws_args' );

function wpthreejs_filter_aws_args($args) {
	if (defined('AWS_SESSION_TOKEN')) {
		$args['token'] = AWS_SESSION_TOKEN;
	}
	return $args;
}

add_action('admin_menu', 'wpthreejs_admin_menu', 100);

function wpthreejs_admin_menu($admin_bar) {
add_options_page('Three Js','ThreeJS','manage_options','three-js','three_image_urls');

}

//add_filter( 'the_content', 'three_image_urls' );
function three_image_urls() {


	echo "Three JS Image Urls";
	// Create check box for all the available image size
	$sizes = array();
    echo '<div class="imagesize">';	
	$img_sizes = get_intermediate_image_sizes();
	echo '<form action="options-general.php?page=three-js" method="post">';
	foreach ( $img_sizes as $key => $size ) {
		echo '<input name=threejs_options[] type = "checkbox" value ='. $size .' /> '. $size .' ';
		echo '<br>';
	}
	submit_button('Save changes');
	echo '</form>';
	echo '</div>';


// Pass checked image size to get url
	$sizes = array();
	        if(isset($_POST['threejs_options'])) {
				    foreach($_POST['threejs_options'] as $checkbox) {
						array_push($sizes, $checkbox);
					}
    get_three_images($sizes);		
			}

}

function get_three_images($sizes)
{

	$query_images_args = array(
		'post_type'      => 'attachment',
		'post_mime_type' => 'image/jpeg,image/jpg',
		'post_status'    => 'inherit',
		'posts_per_page' => -1,
		);

//	$size = 'medium';
//	echo json_encode($sizes, JSON_PRETTY_PRINT);
	echo "<br>";
	    $query_images = new WP_Query ( $query_images_args );

	$images = array();

			echo "<div id='three-images' class='three-images-js' style='display:block'>";
		    if ( $query_images->have_posts() ){

				while ($query_images->have_posts()){
					$query_images->the_post();
					foreach ( $sizes as $key => $size ) {
					//	$thumbnails[$key] = wp_get_attachment_image_src( get_the_ID(), $size)[0];
						
						$thumbnails = wp_get_attachment_image_src( get_the_ID(), $size)[0];
						array_push( $images,$thumbnails);
						echo $thumbnails;
						echo "<br>";
					}
				}
			}
			//        echo "<div id='three-images' class='three-images-js' style='display:block'>";
			//        echo '<script>var three_images=' .json_encode($images) .';</script>';
					echo sizeof($images);
			//		echo "<br>";
			//		echo json_encode($images, JSON_PRETTY_PRINT);
			//		echo "</div>";


			$credentials = new Aws\Credentials\Credentials(AWS_ACCESS_KEY_ID ,AWS_SECRET_ACCESS_KEY);
			$s3 = new S3Client([
				    'version' => 'latest',
					'region'  => 'us-west-2',
					'credentials' => $credentials
					]);

     			$three_images = json_encode($images);
			$result = $s3->putObject(array(
				    'Bucket' => 'naikonpixels.com',
				    'Key'    => 'three_urls.json',
				    'Body'   => $three_images,
						));
			$url = $result['ObjectURL'];
			echo "Url's upload to S3:  ";
			echo $url;
}
