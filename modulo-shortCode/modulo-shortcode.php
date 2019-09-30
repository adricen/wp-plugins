<?php
/**
 * Plugin Name: Modulo
 * Plugin URI: https://love-open-design.com/modulo-bois/
 * Description: Display the Modulo Tool thanks to [modulo-tool] shortcode
 * Version: 1
 * Author: Adrien Centonze
 * Author URI: https://www.love-open-design.com
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package Modulo Tool
 * @version 1
 * @author Adricen
 * @copyright Copyright (c) 2019, Adrien Centonze
 * @link https://love-open-design.com/modulo-bois/
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

// Loading js and style files
// if



if ( !function_exists( 'modulo_scripts' ) ):
  function modulo_scripts() {
    wp_enqueue_style( 'modulo-style', plugins_url() . '/modulo-shortCode/assets/css/modulo-style.css', array() );
    wp_enqueue_script( 'svg-lib', plugins_url() . '/modulo-shortCode/assets/js/svg.js', array('jquery'), false, true );
    wp_enqueue_script( 'modulo', plugins_url() . '/modulo-shortCode/assets/js/modulo.js', array('jquery'), false, true );

  }
endif;
add_action( 'wp_enqueue_scripts', 'modulo_scripts', 15 );

add_shortcode( 'modulo-tool', 'display_modulo_tool' );


function display_modulo_tool(){
  $champs = array(
    ['name'=>'width', 'type'=>'number', 'min'=>'1', 'step'=>'0.1', 'value'=>'500', 'title'=>'largeur en mm de votre document SVG'],
    ['name'=>'height', 'type'=>'number', 'min'=>'1', 'step'=>'0.1', 'value'=>'500', 'title' => 'hauteur en mm de votre document SVG'],
    ['name'=>'quantity', 'type'=>'number', 'min'=>'1', 'step'=>'1', 'value'=>'5', 'title'=>'nombre d\'elements'],
    ['name'=>'heightMaterial', 'type'=>'number', 'min'=>'0.1', 'step'=>'0.1', 'value'=>'3', 'title'=>'epaisseur en mm de votre materiaux'],
    ['name'=>'curf', 'type'=>'number', 'min'=>'0.01', 'step'=>'0.01', 'value'=>'0.1', 'title'=>'"Curf" ou epaisseur de votre laser'],
    ['name'=>'weight', 'type'=>'number', 'min'=>'0', 'step'=>'0.1', 'value'=>'0', 'title'=>'largeur de votre modulo'],
    ['name'=>'centerMesure', 'type'=>'number', 'min'=>'0', 'step'=>'0.1', 'value'=>'0', 'title'=>'largeur entre les deux ouvertures ajouté a l\'epaisseur materiaux'],
    ['name'=>'outerMesure', 'type'=>'number', 'min'=>'0', 'step'=>'0.1', 'value'=>'0','title'=>'distance en bout du Modulo']
  );
  // init
  $drawFrame = '<form id="modulaForm" class="modulaForm" name="modulaForm">';

  for ($i=0; $i <count($champs) ; $i++) {
    // generating each inputs
    $drawFrame.= '<label for="'.$champs[$i]['name'].'">'.ucfirst($champs[$i]['name']).' : <input ';
    // getting all attributs
    foreach($champs[$i] as $key => $value){
      $drawFrame .= $key . '="' . $value.'" ' ;
    }
    $drawFrame .= '></label>';
    if($i%2 == 1) {
      $drawFrame .= '</br>';
    }
  }
  $drawFrame .= '<input type="button" value="Download Svg file" onclick="download(\'modulaKit.svg\', $(\'#modulaDrawing\'))"></form>';
  // div for svg frame
  $drawFrame .= '<div id="modulaDrawing" class="modulaDrawing"></div>';
  return $drawFrame;
}
