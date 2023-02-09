<?php

namespace ZMP\AIAssistant\Config;

class config {

  public $pluginname;
  public $version;

  function __construct(){

    $this->pluginname = __( 'ZMP AI Assistant', 'zmp-ai-assistant' );

    $this->version = '0.9.3';    

  }

}
