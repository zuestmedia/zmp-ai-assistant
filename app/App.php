<?php

namespace ZMP\AIAssistant;

use ZMP\Plugin\Helpers;

class App extends \ZMP\Plugin\App {

  /**
   * API Headers key and organization
   * - api_key 
   * - org_id
   */
  private $credentials = NULL;
  public function getCredentials($sub_field_name){
    return Helpers::getOption(
      $this->getCredentialsFieldName($sub_field_name),
      $this->getCredentialsDefaultValue(),
      '2',//always checks option if >= 2
      'option_mod',
      $this->getOptPra().'_api_credentials'
    );
  }
  public function getCredentialsFieldName($sub_field_name) {
    return $sub_field_name;
  }
  public function getCredentialsDefaultValue(){
    return $this->credentials;
  }

  /**
   * API Settings
   */

  private $settings = array(
    "prompt" => 'Say "hi".',
    "suffix" => NULL,
    "input" => 'This is a gramar exerzise.',
    "instruction" => 'Fix the grammar.',
    "imageprompt" => 'A black cat',
    "model" => 'text-davinci-003',
    "max_tokens" => 16,
    "n" => 1,
    "temperature" => 1,
    "top_p" => 1,
    "best_of" => 1,
    "stop" => NULL,
    "presence_penalty" => 0,
    "frequency_penalty" => 0,
    "size" => '1024x1024',
    "response_format" => 'url',
  );
  public function getSettings(){
    return Helpers::getOption(
      $this->getOptPra().'_api_settings',
      $this->getSettingsDefaultValues(),
      '2',//always checks option if >= 2
      'option'        
    );
  }
  public function getSettingsDefaultValues(){
    return $this->settings;
  }      
  //single setting
  public function getSetting($sub_field_name){
    return Helpers::getOption(
      $this->getSettingFieldName($sub_field_name),
      $this->getSettingDefaultValue($sub_field_name),
      '2',//always checks option if >= 2
      'option_mod',
      $this->getOptPra().'_api_settings'
    );
  }
  public function getSettingFieldName($sub_field_name) {
    return $sub_field_name;
  } 
  public function getSettingDefaultValue($key){
    return $this->settings[$key];
  }

  public function getModelsOrderedArray(){

    global $zmpaiassistant;

    $data = $zmpaiassistant['apicalls']->getModels();

    $models_ordered = array();
    if(is_array(($data))){
    
      foreach($data as $model_u){

        $models_ordered[$model_u->id] = array(
          'id' => $model_u->id,
          'created' => $model_u->created,
        );
  
      }
  
      //sort by key
      ksort($models_ordered);

    }
    return $models_ordered;

  }

  public function getModelsOptionsChoices(){

    $models_ordered = $this->getModelsOrderedArray();

    $date_format = get_option( 'date_format' );

    $choices = array();

    foreach($models_ordered as $model){

      $date = date($date_format,$model['created']);

      $choices[] = array('option'=>esc_html( $model['id'] ).' ('.esc_html( $date ).')','value'=> esc_attr( $model['id'] ));

    }

    return $choices;    

  }



  //default template
  private $default_template = 'default';
  public function getDefaultTemplate(){
    return Helpers::getOption(
      $this->getDefaultTemplateFieldName(),
      $this->getDefaultTemplateDefaultValue(),
      '2',//always checks option if >= 2
      'option'        
    );
  }
  public function getDefaultTemplateFieldName(){
    return $this->getOptPra().'_default_template';
  }
  public function getDefaultTemplateDefaultValue(){
    return $this->default_template;
  }




  public function getGPTTemplates(){
    return Helpers::getOption(
      $this->getGPTTemplatesFieldName(),
      array(),
      '2',//always checks option if >= 2
      'option'        
    );
  }
  public function getGPTTemplatesFieldName() {
    return $this->getOptPra().'_gpt_templates';
  }
  
  public function saveGPTTemplate($template_name,$form_data){

    $templates = $this->getGPTTemplates();

    //save and return true if save
    if(!array_key_exists($template_name,$templates)){

      $templates[$template_name] = $form_data;

      update_option( $this->getGPTTemplatesFieldName(), $templates );

      return true;

    } 

    return false;
    

  }

  public function deleteGPTTemplate($template_name){

    $templates = $this->getGPTTemplates();

    //save and return true if save
    if(array_key_exists($template_name,$templates)){

      unset($templates[$template_name]);

      update_option( $this->getGPTTemplatesFieldName(), $templates );

      return true;

    } 

    return false;
    

  }

  public function getTemplatesOrderedArray(){

    $templates = $this->getGPTTemplates();

    //sort by key
    ksort($templates);

    return $templates;

  }

  public function getTemplatesOptionsChoices(){

    $templates_ordered = $this->getTemplatesOrderedArray();

    $choices = array();

    $choices[] = array('option'=>__('Default settings','zmp-ai-assistant'),'value'=> 'default');

    foreach($templates_ordered as $key => $template ){

      $choices[] = array('option'=>esc_html( $key ),'value'=> esc_attr( $key ));

    }

    return $choices;    

  }

}
