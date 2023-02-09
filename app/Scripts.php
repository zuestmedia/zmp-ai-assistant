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

    }

  }


}
