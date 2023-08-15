<?php

function kh_register_blocks () {
  $dir = KH_PLUGIN_DIR . 'build/blocks/';

  $blocks = [
    [
      'name' => 'khatam-form',
      'options' => ['render_callback' => 'kh_khatam_form_render_cb']
    ],
    [
      'name' => 'khatam-table',
      'options' => ['render_callback' => 'kh_khatam_table_render_cb']
    ],
  ];

  forEach ($blocks as $block) {
    register_block_type(
      $dir . $block['name'],
      isset($block['options']) ? $block['options']: []
    );
  }
}
