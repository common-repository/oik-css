<?php

/**
 * @copyright (C) Copyright Bobbing Wide 2013-2019
 * @package oik-css
 */

/**
 * Enqueue the internal CSS styling
 *
 * This code COULD be improved to accumulate ALL the appended CSS into one block and then produced during footer processing.
 * Note: we also support media="print"
 *
 * @param array $atts - shortcode parameters - currently unused
 * @param string $content - the CSS to enqueue
 */
function bw_enqueue_style( $atts, $content ) {
  stag( "style", null, null, kv( "type", "text/css" ) . kv( "media", "screen,print")  );
  e( $content );
  etag( "style" );
}

/**
 * Format the CSS as part of the content, if required
 * 
 * @param array $atts - array of parameters. The formal parameter name is "text" but ANY value will do the job
 * @param string $content - the CSS to be displayed
 */
function bw_format_style( $atts, $content ) {
	if ( function_exists( 'bw_array_get_from') ) {
		$text=bw_array_get_from( $atts, "text,0", null );
	} else {
		$text = bw_array_get( $atts, 'text', null );
	}
  if ( $text ) {
    sdiv( "bw_css" ); 
    if ( $text <> "." ) {
      e( $text );
    }
    e( bw_geshi_it( $content ) );
    ediv();
  }
}

/**
 * Our version of geshi_highlight
 *
 * Disables links for html, mysql etc
 *
 * @param string $string   The code to highlight
 * @param string $language The language to highlight the code in
 * @param string $path     The path to the language files. You can leave this blank if you need
 *                         as from version 1.0.7 the path should be automatically detected
 * @param boolean $return  Whether to return the result or to echo
 * @return string The code highlighted
 */
function bw_geshi_highlight($string, $language) {
		if ( !class_exists('GeSHi') ) {
			oik_require( "geshi/geshi.php", "oik-css" );
		}
	$geshi = new GeSHi($string, $language);
	$geshi->set_header_type(GESHI_HEADER_NONE);
	$geshi->enable_keyword_links( false );
	return '<code>' . $geshi->parse_code() . '</code>';
}

/**
 * Perform GeSHi - Generic Syntax Highlighter processing
 * 
 * If geshi_highlight() is already available then we don't need to load our version
 *
 * After highlighting convert any remaining '[' to &#091; to stop plugins such as NextGen from expanding the shortcodes.
 * Note: It shouldn't matter if we do this to CSS 
 * 
 * @param string $content - the code to be put through GeSHi highlighting
 * @param string $language - the language to use.
 * @return string the highlighted code
 */
function bw_geshi_it( $content, $language="CSS" ) {
	if ( $language != "none" ) {
		$geshid = bw_geshi_highlight( $content, $language, null, true );
	} else {
		$content = esc_html( $content );
		$geshid = "<pre>" . $content . "</pre>";
	}
	bw_trace2( $geshid );
	$geshid = str_replace( "[", "&#091;", $geshid );
	return( $geshid );
}

/**
 * Remove unwanted tags introduced by other filters
 * 
 * The $content may contain all sorts of nastys that WordPress filters have added to the plain text so we need to strip it out.
 * @link http://www.ascii.cl/htmlcodes.htm
 *
 * @param string $content 
 * @return string - content with the unwanted HTML removed
 */ 
function bw_remove_unwanted_tags( $content ) {
  $dec = $content;
  $dec = str_replace( "<br />", "", $dec );
  $dec = str_replace( "<p>", "", $dec );
  $dec = str_replace( "</p>", "", $dec );
	return $dec;
}

/**
 * Detexturize content
 *
 * Reverse the texturizing that may have been performed against the content.
 *
 * @param string $content
 * @return string Detexturized content
 */
function bw_detexturize( $content ) {
  $dec = $content;
  $dec = str_replace( "&#8216;", "'", $dec );  // Left single quotation mark
  $dec = str_replace( "&#8217;", "'", $dec );  // Right single quotation mark
  $dec = str_replace( "&#8220;", '"', $dec );  // Left double quotation mark
  $dec = str_replace( "&#8221;", '"', $dec );  // Right double quotation mark
  $dec = str_replace( "&#038;", '&', $dec );   // Ampersand
  $dec = str_replace( "&#8211;", "-", $dec );  // en dash
  bw_trace2( $dec, "de-tagged content" );
  return $dec;
}

/**
 * Implement [bw_css] shortcode
 * 
 * @param array $atts - array of shortcode parameters
 * @param string $content - the CSS to be put into the page
 * @param string $tag - the shortcode tag - not expected
 * @return string any text to be put onto the page
 * 
 */
function oik_css( $atts=null, $content=null, $tag=null ) {
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
  	sdiv();
    $dec = bw_remove_unwanted_tags( $content );
		$dec = bw_detexturize( $dec );
    bw_enqueue_style( $atts, $dec );
    bw_format_style( $atts, $dec );
    ediv();
  }
  return( bw_ret() );
}

/**
 * Help hook for [bw_css] shortcode
 *
 * @param string $shortcode shortcode name
 * @return string Short description of the shortcode
 */
function bw_css__help( $shortcode="bw_css" ) {
  return( __( "Add internal CSS styling", "oik-css" ) );
}

/**
 * Implement syntax hook for the bw_css shortcode
 *
 * The shortcode is expected to be coded as 
 * [bw_css]<i>Internal CSS rules</i>[/bw_css]
 *
 * If you want the CSS to be passed through GeSHi and output to the page then this is indicated using a parameter.
 * A simple . will cause the CSS to be echoed. 
 * Anything other than .  which could be as text="echo this text" or just "echo this text" will be echoed before the CSS. 
 */
function bw_css__syntax( $shortcode="bw_css" ) {
  $syntax = array( "." => BW_::bw_skv( null, "<i>". __( "any", "oik-css" ) . "</i>", __( "Display the CSS", "oik-css" ) )
                 , "text" => BW_::bw_skv( null, "<i>". __( "any", "oik-css" ) . "</i>", __( "Display the CSS with this annotation", "oik-css" ) )
                 );
  return( $syntax );
}

/**
 * Implement example hook for the bw_css shortcode
 */
function bw_css__example( $shortcode="bw_css" ) {
  $text = __( "When the &lt;code&gt; tag follows a &lt;p&gt; tag use a 14px font and different colours", "oik-css" );
  $example = " ]p> code { font-size: 14px; color: white; background: #333; }[/bw_css";
  bw_invoke_shortcode( $shortcode, $example, $text );
  $text = __( "Elsewhere display &lt;code&gt; in blue.", "oik-css" );
  $example = " ]code { color: blue; }[/bw_css";
  bw_invoke_shortcode( $shortcode, $example, $text );
  $text = __( "Use a parameter to cause the CSS to be shown.", "oik-css" );
  $example = " .]td code b { color: darkblue; }[/bw_css";
  bw_invoke_shortcode( $shortcode, $example, $text );
}
