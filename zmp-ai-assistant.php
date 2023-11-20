<?php
  /*
  Plugin Name: ZMP AI Assistant
  Plugin URI: https://zuestmedia.com/plugins/
  Description: This plugin helps webmasters to create content and images with artificial intelligence
  Author: zuestmedia
  Author URI: https://zuestmedia.com/
  Version: 1.0.0
  Text Domain: zmp-ai-assistant
  Domain Path: /languages
  ZMDLID: jfwo0j320ivjvn0432nfg0sweri92fhvbnws
  ZMUPDAPI: zm
  */
  defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


if ( ! function_exists( 'zmp_ai_assistant_init' ) ) {

/**
  * init plugin after zmplugin_loaded is loaded
  */
  add_action('zmplugin_loaded', 'zmp_ai_assistant_init');
  function zmp_ai_assistant_init() {

  /**
    * This is the global var of this plugin!
    * --> use for each plugin a new one! or at least new keys, but this is crap!
    * Contains: array of Objects from Plugin classes
    * @var array
    * @access public
    */
    global $zmpaiassistant;

  /**
    * load plugin.php if not loaded to get plugin basename
    */
    if ( ! function_exists( 'plugin_basename' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

  /**
    * add pugin basename to global var for later use
    */
    $zmpaiassistant['plugin_basename'] = plugin_basename( __FILE__ );

    if(class_exists('ZMPluginPsr4AutoloaderClass')){

      $zmp_ai_assistant_psr4autoloader = new ZMPluginPsr4AutoloaderClass;
      $zmp_ai_assistant_psr4autoloader->register();

      $zmp_ai_assistant_psr4autoloader->addNamespace('ZMP\AIAssistant', __DIR__ . '/app/');

      $zmp_ai_assistant_psr4autoloader->addNamespace('ZMP\AIAssistant\Config', __DIR__ . '/config/');

      //add alternative namespace to wp uploads folder
      $zmp_ai_assistant_psr4autoloader->addNamespace('ZMP\AIAssistant\ExtendClass', wp_get_upload_dir()['basedir'] . '/' . basename(__FILE__, ".php") . '/' );

      new \ZMP\AIAssistant\Init();

    } else {

    /**
      * Simple Errorpage! Not translated...
      * --> add for each plugin a new Class BaseName, to errorpage class and autoloader!
      */
      require_once 'app/ErrorMenu.php';//because autoloader not available!
      $errorpage = new \ZMP\AIAssistant\ErrorMenu( $zmpaiassistant['plugin_basename'] );
      $errorpage->setDisplayName( 'ZMP AI Assistant' );
      $errorpage->setMenuPage('<div class="wrap"><h1>'.$errorpage->getDisplayName().'</h1><div class="error notice"><p><strong>Error:</strong> '.$errorpage->getDisplayName().' is an addon of <a>ZMPlugin</a>. Please install ZMPlugin to use this addon!</p></div></div>');
      $errorpage->addMenuPage();

      return;

    }

  }

}
