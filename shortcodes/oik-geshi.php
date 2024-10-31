<?php // (C) Copyright Bobbing Wide 2013-2024

/**
 * Validate the language for GeSHi
 *
 * Use 'none' when you want the output to be displayed ASIS; GeSHi is not used for this.
 * 'html' is an alias for 'html5'. It uses the same GeSHi code.
 *
 * @param string $lang - the required languange ( case insensitive )
 * @param string $text - alternative parameter for language ( case sensitive )
 * @return string - the validated language, or null
 *
 */
function oik_css_validate_lang( $lang, &$text ) {
	if ( null !== $lang ) {
		$lang=strtolower( $lang );
	}
  $valid = bw_assoc( bw_as_array( "css,html,javascript,jquery,php,html5,none,mysql" ));
  $vlang = bw_array_get( $valid, $lang, null );
  if ( !$vlang ) {
    $vlang = bw_array_get( $valid, $text, null );
    if ( $vlang ) {
      $text = $lang;
    }
  }
  if ( !$vlang ) {
	/* translators: %1: Hardcoded string lang=, not translatable, %2: language parameter passed */
    BW_::p( sprintf( __( 'Invalid %1$s parameter for GeSHi. %2$s', "oik-css" ), "lang=", $lang ) );
    BW_::p( "$vlang,$text" );
  }
  return( $vlang );
}

/**
 * Format the content for the chosen language
 *
 * - For 'none' and 'html' we shouldn't strip p and br tags.
 * - For other languages we have to remove them.
 * - Detexturize undoes any unwanted texturizing.
 *
 * @param array $atts - array of parameters. The formal parameter name is "text" but ANY value will do the job
 * @param string $content - the CSS to be displayed
 */
function bw_format_content( $atts, $content ) {
	if ( function_exists( 'bw_array_get_from') ) {
		$lang=bw_array_get_from( $atts, "lang,0", "none" );
		$text=bw_array_get_from( $atts, "text,1", null );
	} else {
		$lang=bw_array_get( $atts, "lang", "none" );
		$text=bw_array_get( $atts, "text", null );
		oik_require_lib( 'class-BW-');

	}
  $lang = oik_css_validate_lang( $lang, $text );
  if ( $lang ) {
		switch ( $lang ) {
			case 'html':
			case 'html5':
				$lang = 'html5';

			case 'none':
				$content = bw_detexturize( $content );
				break;

			default: // css, php, javascript, jquery, mysql
				$content = bw_remove_unwanted_tags( $content );
				$content = bw_detexturize( $content );
		}
    sdiv( "bw_geshi $lang" );
    if ( $text <> "." ) {
      e( $text );
    }
    e( bw_geshi_it( $content, $lang ) );
    ediv();
  }
}

/**
 * Implement the [bw_geshi] shortcode for source code syntax highlighting
 *
 * @param array $atts shortcode parameters
 * @param string $content code to syntax highlight
 * @param string $tag
 * @return string syntax highlighted content
 */
function oik_geshi( $atts=null, $content=null, $tag=null ) {
	if ( !$content ) {
		oik_require_lib( 'class-oik-attachment-contents');
		if ( class_exists( 'Oik_attachment_contents') ) {
			$oik_attachment_contents=new Oik_attachment_contents();
			$content=$oik_attachment_contents->get_content( $atts, $content );
		} else {
			e( "Oik_attachment_contents not loaded");
		}
	}
  if ( $content ) {
    oik_require( "shortcodes/oik-css.php", "oik-css" );
    bw_format_content( $atts, $content );
  }
	$ret = bw_ret();
  return $ret;
}

/**
 * Help hook for the bw_geshi shortcode
 */
function bw_geshi__help( $shortcode="bw_geshi" ) {
  return( __( "Generic Syntax Highlighting", "oik-css" ) );
}

/**
 * Syntax hook for the bw_geshi shortcode
 *
 * Added "content" for shortcode UI
 * Removed "content" for shortcode UI (shortcake) - since it now uses "inner_content"
 * Added "none" language for no GeSHi processing
 */
function bw_geshi__syntax( $shortcode="bw_geshi" ) {
  $syntax = array( "lang" => BW_::bw_skv( null, "html|css|javascript|jquery|php|none|mysql", __( "Programming language", "oik-css" ) )
                 , "text" => BW_::bw_skv( null, "<i>" . __( "text", "oik-css" ) .  "</i>", __( "Descriptive text to display", "oik-css" ) )
                 );
  return( $syntax );
}

/**
 * Implement example hook for the bw_geshi shortcode
 *
 * We can't use bw_invoke_shortcode() since we need to call esc_html() against the sample HTML code
 * otherwise it just gets processed as normal output
 *
 * @param string $shortcode
 */
function bw_geshi__example( $shortcode="bw_geshi" ) {
  $text = __( "Demonstrating the HTML to create a link to oik-plugins.com", "oik-css" );
  BW_::p( $text );
  $example = "[$shortcode";
  $example .= ' lang=html]<a href="https://www.oik-plugins.com">' . __( "Visit oik-plugins.com", "oik-css" ) . '</a>[/bw_geshi';
  $example .= ']';
  sp();
  stag( "code" );
  e( esc_html( $example ) );
  etag( "code" );
  bw_echo( '</p>' );
  $expanded = apply_filters( 'the_content', $example );
  e( $expanded );
}
