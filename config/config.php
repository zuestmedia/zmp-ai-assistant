<?php

namespace ZMP\AIAssistant\Config;

class config {

  public $pluginname;
  public $version;

  function __construct(){

    $this->pluginname = __( 'ZMP AI Assistant', 'zmp-ai-assistant' );

    $this->version = '1.0.0';    

  }

}
