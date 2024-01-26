<?php

namespace ZMP\AIAssistant;

class Scripts {

  //https://wordpress.stackexchange.com/questions/114898/loading-scripts-to-the-post-edit-page-only
  function __construct( ){
    
    add_action( 'admin_enqueue_scripts',  array($this,'EnqueueOnEditScreens') );

  }
  
  public function EnqueueOnEditScreens($hook_suffix) {

    if( 'post.php' == $hook_suffix || 'post-new.php' == $hook_suffix ) {

      global $zmplugin;

      $zmplugin['admin_scripts']->AdminAssets();
      //$zmplugin['admin_scripts']->EnqueueJsArray();

      wp_enqueue_script( 'zmp-aia-script', $zmplugin['zmp-ai-assistant']->getPluginUrl() . '/app/js/aia.js', array('zmp-js') );

      wp_localize_script( 
        'zmp-aia-script', 
        'zmp_aia_ajax',
        array( 
            'url' => admin_url( 'admin-ajax.php' ), 
            'zmp_aia_nonce_get_gpt_data' => wp_create_nonce( 'zmp_aia_nonce_get_gpt_data' ),
            'zmp_aia_nonce_save_gpt_template' => wp_create_nonce( 'zmp_aia_nonce_save_gpt_template' ),
            'zmp_aia_nonce_get_gpt_templates' => wp_create_nonce( 'zmp_aia_nonce_get_gpt_templates' ),
            'zmp_aia_nonce_save_gpt_image' => wp_create_nonce( 'zmp_aia_nonce_save_gpt_image' )
        )
      );

    }

  }


}
