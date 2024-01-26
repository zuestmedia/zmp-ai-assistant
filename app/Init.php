<?php

namespace ZMP\AIAssistant;

use ZMP\Plugin\PluginHelper;

class Init {

    function __construct( ){

      /**
      * define global var
      */
      global $zmpaiassistant;

      $zmpaiassistant['default_config'] = new \ZMP\AIAssistant\Config\config();

    /**
      * Register Plugin w Check
      */
      if( PluginHelper::registerExtensionCheck( $zmpaiassistant['plugin_basename'] ) ){

        $this->initPlugin();
        $this->initPluginApp();
        $this->initPluginSettings();

      }

    }

    public function initPlugin(){

      if (class_exists ('\ZMP\AIAssistant\Config')) {

        new \ZMP\AIAssistant\Config();

      }

    }

    public function initPluginApp(){

      if(is_admin()) {

        add_action('init', array( $this, 'PluginAppStart' ));

      }

    }

    public function PluginAppStart(){

      global $zmplugin;
      global $zmpaiassistant;

      $zmpaiassistant['app'] = new \ZMP\AIAssistant\App($zmplugin['zmp-ai-assistant']->getOptGroup());

      new \ZMP\AIAssistant\Scripts();

      $zmpaiassistant['apicalls'] = new \ZMP\AIAssistant\APICalls();
      
      new \ZMP\AIAssistant\Metabox();

    }

    public function initPluginSettings(){
      add_action('init', array( $this, 'PluginSettingsStart' ));
    }

    public function PluginSettingsStart(){

      if(is_admin()) {

        if (class_exists ('\ZMP\AIAssistant\SettingsInit')) {

          new \ZMP\AIAssistant\SettingsInit();

        }

      }

    }

}
