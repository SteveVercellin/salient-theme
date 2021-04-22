<!DOCTYPE html>

<html <?php language_attributes(); ?> class="no-js">
<head>
	

	
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	
	<?php
	
	$nectar_options = get_nectar_theme_options();
	
	if ( ! empty( $nectar_options['responsive'] ) && '1' === $nectar_options['responsive'] ) { 
		echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />';
	} else { 
		echo '<meta name="viewport" content="width=1200" />';
	} 
	
	// Shortcut icon fallback.
	if ( ! empty( $nectar_options['favicon'] ) && ! empty( $nectar_options['favicon']['url'] ) ) {
		echo '<link rel="shortcut icon" href="'. esc_url( nectar_options_img( $nectar_options['favicon'] ) ) .'" />';
	}
	
	wp_head();
	
	?>
	
	
</head>

<?php

$nectar_header_options = nectar_get_header_variables();

?>

<body <?php body_class(); ?> <?php nectar_body_attributes(); ?>>
	
<?php if ( !is_front_page() ) : ?>
	<style>.icon-bar{display:none}</style>
    <?php endif; ?>

<audio src="http://reinnovame.com/wp-content/uploads/2020/11/audio_new.mp3" loop></audio>
	
<div class="spread-bar">	
	<button class="btn-icon sound-toggle spread-bar-sound" type="button">
		<span class="off">AUDIO OFF</span>
		<span class="on">AUDIO ON</span>

    <i class="sound-toggle-bar"></i>
    <i class="sound-toggle-bar"></i>
    <i class="sound-toggle-bar"></i>
    <i class="sound-toggle-bar"></i>
    <i class="sound-toggle-bar"></i>
    <i class="sound-toggle-bar"></i>
    <i class="sound-toggle-bar"></i>
</button>
	</div>
	
	
	
       <div class="icon-bar">
<a href="https://www.linkedin.com/company/reinnovame/" target="blank" class="linkedin"><i class="fa fa-linkedin"></i></a>
  <a href="https://www.facebook.com/ReInnovaMe.Consulting/ "  target="blank"  class="facebook"><i class="fa fa-facebook"></i></a> 
  <a href="https://www.instagram.com/reinnovame/"  target="blank"  class="instagram"><i class="fa fa-instagram"></i></a> 
  <a href="https://www.youtube.com/channel/UC3XwEsM0tjYy-YnDIYzz26Q " class="youtube"><i class="fa fa-youtube-play"></i></a> 
  <a href="mailto:info@reinnovame.com"  target="blank"  class="youtube"><i class="fa fa-envelope"></i></a> 

  </div>
		
	
	
	<?php
	
	nectar_hook_after_body_open();
	
	nectar_hook_before_header_nav();
	
	// Boxed theme option opening div.
	if ( $nectar_header_options['n_boxed_style'] ) {
		echo '<div id="boxed">';
	}
	
	get_template_part( 'includes/partials/header/header-space' );
	
	?>
	
	<div id="header-outer" <?php nectar_header_nav_attributes(); ?>>
		
		<?php
		
		get_template_part( 'includes/partials/header/secondary-navigation' );
		
		if ('ascend' !== $nectar_header_options['theme_skin'] && 
			'left-header' !== $nectar_header_options['header_format']) {
			get_template_part( 'includes/header-search' );
		}
		
		get_template_part( 'includes/partials/header/header-menu' );
		
		
		?>
		
	</div>
	
	<?php
	
	if ( ! empty( $nectar_options['enable-cart'] ) && '1' === $nectar_options['enable-cart'] ) {
		get_template_part( 'includes/partials/header/woo-slide-in-cart' );
	}
	
	if ( 'ascend' === $nectar_header_options['theme_skin'] || 
		'left-header' === $nectar_header_options['header_format'] && 
		'false' !== $nectar_header_options['header_search'] ) {
		get_template_part( 'includes/header-search' ); 
	}
	
	?>
	
	<div id="ajax-content-wrap">
		
		<?php
		
		nectar_hook_after_outer_wrap_open();
