<?php if ( ! defined( 'ABSPATH' )  ) { die; } // Cannot access directly.
/**
 * Theme Options.
 *
 * @since   1.0.0
 * @package Kalles
 */



// Typography config
CSF::createSection( $prefix, array(
  'id'     => 'portfolio',
  'title'  => esc_html__( 'Typography', 'kalles' ),
  'icon'   => 'fas fa-font',
  'fields' => array(
    array(
      'id'      => 'font_face_type',
      'type'    => 'button_set',
      'title'   => esc_html__( 'Font face type', 'kalles' ),
      'options'    => array(
        'default' => 'default',
        'custom' => 'Kalles font',
        'google' => 'Google font',
      ),
      'default'    => 'default',
    ),
    array(
      'id'    => 'font_face_option',
      'type'  => 'select',
      'title' => esc_html__( 'Kalles Font', 'kalles' ),
      'options' => array(
          'futura' => esc_html__( 'Font Futura', 'kalles' ),
          'jost' => esc_html__( 'Font Jost', 'kalles' ),

      ),
      'dependency' => array( 'font_face_type', '==', 'custom' ),
      'default' => 'futura'

    ),
    array(
      'type'    => 'subheading',
      'content' => esc_html__( 'Body Font Settings', 'kalles' ),
    ),
    array(
      'id'        => 'body-font',
      'type'      => 'typography',
      'title'     => esc_html__( 'Font Family', 'kalles' ),
      'default'   => array(
        'font-family'  => 'Poppins',
        'type'    => 'google',
        'font-weight' => 'regular',
      ),
      'dependency' => array( 'font_face_type', '==', 'google' ),
    ),
    array(
      'id'      => 'head-top-font-size',
      'type'    => 'number',
      'title'   => esc_html__( 'Header Top Font Size', 'kalles' ),
      'unit'    => 'px',
      'default' => '12'
    ),
    array(
      'id'      => 'menu-font-size',
      'type'    => 'number',
      'title'   => esc_html__( 'Menu Font Size', 'kalles' ),
      'unit'    => 'px',
      'default' => '14'
    ),
    array(
      'type'    => 'subheading',
      'content' => esc_html__( 'Heading Font Settings', 'kalles' ),
    ),
    array(
      'id'        => 'heading-font',
      'type'      => 'typography',
      'title'     => esc_html__( 'Font Family', 'kalles' ),
      'font_size'      => false,
      'default'   => array(
        'font-family'  => 'Poppins',
        'type'    => 'google',
        'font-weight' => '600',
      ),
      'dependency' => array( 'font_face_type', '==', 'google' ),
    ),
    array(
      'id'      => 'h1-font-size',
      'type'    => 'number',
      'title'   => esc_html__( 'H1 Font Size', 'kalles' ),
      'unit'    => 'px',
      'default' => '48'
    ),
    array(
      'id'      => 'h2-font-size',
      'type'    => 'number',
      'title'   => esc_html__( 'H2 Font Size', 'kalles' ),
      'unit'    => 'px',
      'default' => '36'
    ),
    array(
      'id'      => 'h3-font-size',
      'type'    => 'number',
      'title'   => esc_html__( 'H3 Font Size', 'kalles' ),
      'unit'    => 'px',
      'default' => '24'
    ),
    array(
      'id'      => 'h4-font-size',
      'type'    => 'number',
      'title'   => esc_html__( 'H4 Font Size', 'kalles' ),
      'unit'    => 'px',
      'default' => '21'
    ),
    array(
      'id'      => 'h5-font-size',
      'type'    => 'number',
      'title'   => esc_html__( 'H5 Font Size', 'kalles' ),
      'unit'    => 'px',
      'default' => '18'
    ),
    array(
      'id'      => 'h6-font-size',
      'type'    => 'number',
      'title'   => esc_html__( 'H6 Font Size', 'kalles' ),
      'unit'    => 'px',
      'default' => '16'
    ),
  ),
) );
