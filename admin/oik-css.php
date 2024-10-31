<?php // (C) Copyright Bobbing Wide 2014-2017

function oik_css_lazy_oik_menu_box() {
  BW_::oik_box( null, null, __( "oik-CSS options", "oik-css" ), "oik_css_options" );
}

function oik_css_options() {
  $option = "bw_css_options";
  $options = bw_form_start( $option, "oik_css_options" );
  bw_checkbox_arr( $option, __( "Disable automatic paragraph creation", "oik-css" ), $options, "bw_autop" ); 
  etag( "table" ); 			
  e( isubmit( "ok", __("Save changes", "oik-css" ), null, "button-secondary" ) ); 
  etag( "form" );
  BW_::p( __( "To enable automatic paragraph creation use the [bw_autop] shortcode.", "oik-css" ) );
  BW_::p( __( "To disable automatic paragraph creation use [bw_autop off].", "oik-css" ) );
  bw_flush();
}
