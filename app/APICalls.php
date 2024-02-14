<?php

namespace ZMP\AIAssistant;

use WP_Error;

class APICalls {

  //https://stackoverflow.com/questions/43557755/how-to-call-ajax-in-wordpress
  function __construct( ){

    $this->addAJAX();

  }

  public function addAJAX(){

    //get_gpt_data
    add_action( 'wp_ajax_nopriv_get_gpt_data', array($this,'AjaxResponse_get_gpt_data') );
    add_action( 'wp_ajax_get_gpt_data', array($this,'AjaxResponse_get_gpt_data') );
    
    //get_gpt_templates
    add_action( 'wp_ajax_nopriv_get_gpt_templates', array($this,'AjaxResponse_get_gpt_templates') );
    add_action( 'wp_ajax_get_gpt_templates', array($this,'AjaxResponse_get_gpt_templates') );

    //save_gpt_template
    add_action( 'wp_ajax_nopriv_save_gpt_template', array($this,'AjaxResponse_save_gpt_template') );
    add_action( 'wp_ajax_save_gpt_template', array($this,'AjaxResponse_save_gpt_template') );

    //save_gpt_image
    add_action( 'wp_ajax_nopriv_save_gpt_image', array($this,'AjaxResponse_save_gpt_image') );
    add_action( 'wp_ajax_save_gpt_image', array($this,'AjaxResponse_save_gpt_image') );

  }

  public function AjaxResponse_save_gpt_image(){

    if( isset($_POST['security']) && wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['security'] ) ), 'zmp_aia_nonce_save_gpt_image' ) ){

      if( isset($_POST['imageurl']) && !empty($_POST['imageurl']) && filter_var($_POST['imageurl'], FILTER_VALIDATE_URL) !== false){

        $imageurl = sanitize_url($_POST['imageurl']);
            
        // it allows us to use download_url() and wp_handle_sideload() functions
        require_once( ABSPATH . 'wp-admin/includes/file.php' );

        // download to temp dir
        $temp_file = download_url( $imageurl );

        if( ! is_wp_error( $temp_file ) ) {

          // move the temp file into the uploads directory
          $file = array(
            //'name'     => basename( $imageurl ),
            'name'     => 'gpt-dall-e-'.date('Y-m-d-H-i-s').'.png',
            //'type'     => mime_content_type( $temp_file ),
            'type'     => 'image/png',
            'tmp_name' => $temp_file,
            'size'     => filesize( $temp_file ),
          );

          $sideload = wp_handle_sideload(
            $file,
            array(
              'test_form'   => false // no needs to check 'action' parameter
            )
          );

          if( !isset( $sideload[ 'error' ] ) ) {

            // it is time to add our uploaded image into WordPress media library
            $attachment_id = wp_insert_attachment(
              array(
                'guid'           => $sideload[ 'url' ],
                'post_mime_type' => $sideload[ 'type' ],
                //'post_title'     => basename( $sideload[ 'file' ] ),
                'post_title'     => 'gpt-dall-e-'.date('Y-m-d-H-i-s').'.png',
                'post_content'   => '',
                'post_status'    => 'inherit',
              ),
              $sideload[ 'file' ]
            );

            if( ! is_wp_error( $attachment_id ) && $attachment_id ) {
              
              // update medatata, regenerate image sizes
              require_once( ABSPATH . 'wp-admin/includes/image.php' );

              wp_update_attachment_metadata(
                $attachment_id,
                wp_generate_attachment_metadata( $attachment_id, $sideload[ 'file' ] )
              );

              //return $attachment_id;
              wp_send_json_success($attachment_id);

            }

          }

        }

      }

    }

    wp_send_json_error();

  }

  public function AjaxResponse_save_gpt_template(){

    if( isset($_POST['security']) && wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['security'] ) ), 'zmp_aia_nonce_save_gpt_template' ) ){

      if( isset($_POST['template_data']) && !empty($_POST['template_data']) ){

        //sanitize post array values
        $template_data = $this->sanitizeAndreorderFormInputs($_POST['template_data']);

        $template_name = $template_data['zmp-aia-save-template-name'];

        if( preg_match('/^[A-Za-z0-9_\-\s]+$/', $template_name) ){

          if( isset($_POST['form_data']) && !empty($_POST['form_data']) ){

            //sanitize post array values
            $form_data = $this->sanitizeAndreorderFormInputs($_POST['form_data']);

            global $zmpaiassistant;
            $save = $zmpaiassistant['app']->saveGPTTemplate($template_name,$form_data);
    
            if($save == true){
    
              wp_send_json_success($template_name);
    
            }
    
          }  

        }        

      } 

    }

    wp_send_json_error();

  }

  public function AjaxResponse_get_gpt_templates(){

    if( isset($_POST['security']) && wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['security'] ) ), 'zmp_aia_nonce_get_gpt_templates' ) ){

      $template_name = false;
      if( isset($_POST['template_name']) && !empty($_POST['template_name']) ){

        $san_template_name = sanitize_text_field($_POST['template_name']);

        if( preg_match('/^[A-Za-z0-9_\-\s]+$/', $san_template_name) ){

          $template_name = $san_template_name;

        }

      } 
      
      $id = 0;
      if( isset($_POST['id']) && is_numeric($_POST['id']) == true ){

        $san_id = sanitize_text_field($_POST['id']);

        $id = $san_id;

      } 

      if( $template_name == false ){//works with false or 0 --> gets template from default settings

        global $zmpaiassistant;

        $template_name = $zmpaiassistant['app']->getDefaultTemplate();

      }

      if( $template_name != 'default' ){

        global $zmpaiassistant;

        $templates_array = $zmpaiassistant['app']->getGPTTemplates();

        if(array_key_exists( $template_name, $templates_array )){

          $output = $templates_array[$template_name];

          //old templates are saved as strings
          if(!is_array($output)){

            $output = array();

            parse_str( $templates_array[$template_name], $output );

          }

          $new_output = array();
          foreach($output as $key => $value){

            if($key == 'zmp-aia-input-prompt' || $key == 'zmp-aia-input-imageprompt'){

              $value = stripslashes($value);

            }

            $key = str_replace( 'zmp-aia-input-', '', $key );
            $new_output[$key] = $value;

            
          }

          $result = $new_output;

        } else {

          //get default settings
          $result = $this->getDefaultSettingsArray();

        }      

      } else {

        //get default settings
        $result = $this->getDefaultSettingsArray();

      }

      if(!empty($result)){

        $result = $this->prepareTemplateData($result,$id,$template_name);

        $json = json_encode( $result );

        wp_send_json_success( $json );

      }

    }

    wp_send_json_error();

  }

  public function prepareTemplateData($data,$id,$template_name){

    if(class_exists('\ZMP\AIAssistant\ExtendClass\PrepareTemplate')){

      //  add file PrepareTemplate.php with class PrepareTemplate 
      //  (opt. extends \ZMP\AIAssistant\PrepareTemplate) and 
      //  namespace ZMP\AIAssistant\ExtendClass to wp-uploads 
      //  directory /uploads/zmp-ai-assistant
      $prepare = new \ZMP\AIAssistant\ExtendClass\PrepareTemplate();

    } else {

      $prepare = new \ZMP\AIAssistant\PrepareTemplate();

    }    

    $data = $prepare->prepareTemplateData($data,$id,$template_name);

    return $data;

  }

  public function getDefaultSettingsArray(){

    global $zmpaiassistant;

    $settings_array = $zmpaiassistant['app']->getSettings();

    $result = array();
    if(!empty($settings_array)){

      $result = $settings_array;

      return $result;

    }

  }

  public function AjaxResponse_get_gpt_data() {

    $api_response = NULL; //returns error if stays null or has wp_error check at the end...
    $body = NULL;
    $type = NULL;
    $chatid = NULL;

    if( isset($_POST['security']) && wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['security'] ) ), 'zmp_aia_nonce_get_gpt_data' ) ){

      if( isset($_POST['values']) && is_array($_POST['values']) ){

        //sanitize post array values
        $output = $this->sanitizeAndreorderFormInputs($_POST['values']);
        
        if( isset($_POST['chatid']) && is_numeric($_POST['chatid']) ) {

          $chatid = sanitize_text_field($_POST['chatid']);

        }
        
        if($output['zmp-aia-input-mode'] == 'completion'){
    
          $type = 'completion';

          $body = array(
            'model' => $output['zmp-aia-input-model'],//required
            'max_tokens' => intval( $output['zmp-aia-input-max_tokens'] ),      
            'temperature' => intval( $output['zmp-aia-input-temperature']),      
            'top_p' => intval( $output['zmp-aia-input-top_p']),        
            'presence_penalty' => floatval($output['zmp-aia-input-presence_penalty']),      
            'frequency_penalty' => floatval($output['zmp-aia-input-frequency_penalty']),      
          );
    
          // only set if not '' 
          if($output['zmp-aia-input-prompt']){

            $message = array(
              'role' => 'user',
              'content' => $output['zmp-aia-input-prompt']
            );

            //save conversation to db (temporary)
            if($chatid){
              global $zmpaiassistant;
              $messages = $zmpaiassistant['app']->saveGPTConversation($chatid,$message);
            }

            //prepends the system_message to every request if there is one
            global $zmpaiassistant;
            $system_message = $zmpaiassistant['app']->getCredentials('system_message');
            if($system_message){

              $system_message_array = array(
                'role' => 'system',
                'content' => $system_message
              );
              
              array_unshift($messages, $system_message_array);

            }

            $body['messages'] = $messages;

          }
          if($output['zmp-aia-input-stop']){
            $body['stop'] = $output['zmp-aia-input-stop'];
          }
    
          $api_response = $this->postrequest( $body, 'https://api.openai.com/v1/chat/completions' );
    
        }  elseif($output['zmp-aia-input-mode'] == 'image'){
    
          $type = 'image';
    
          $body = array(
            'prompt' => $output['zmp-aia-input-imageprompt'],//required
            'model' => 'dall-e-3',   //always uses dall-e-3
            'size' => $output['zmp-aia-input-size'],   
            'quality' => $output['zmp-aia-input-quality'],   
            'style' => $output['zmp-aia-input-style'],   
          );
    
          $api_response = $this->postrequest( $body, 'https://api.openai.com/v1/images/generations' );
    
        }    

      } 
      
    }

    $this->checkPostRequest($api_response,$type,$body,$chatid);

  }

  public function postrequest( $body, $url ){

    $headers = $this->getHeaders();
    if( empty( $headers ) ){
      return new WP_Error( '999', 'headers not set - missing api_key' );
    }

    $response = wp_remote_post( $url, 
      array(
        'method'      => 'POST',
        'timeout'     => 60, 
        'redirection' => 5,  
        'blocking'    => true,
        'sslverify'   => false,
        'user-agent'  => 'curl/7.64.1',
        'headers'     => $this->getHeaders(),
        'body'        => json_encode( $body )
      )
    );

    return $response;

  }

  public function checkPostRequest($response,$type,$body,$chatid){

    if( is_wp_error( $response ) || empty($response) ){

      wp_send_json_error();

    } else {

      $response_body = wp_remote_retrieve_body( $response );

      //make from json string array! and encode whole array then to json      
      $api_response_obj = json_decode($response_body,true); 

      //save conversation to db (temporary)
      if(array_key_exists('choices',$api_response_obj) && $type == 'completion' && $chatid){
        global $zmpaiassistant;
        $messages = $zmpaiassistant['app']->saveGPTConversation($chatid,$api_response_obj['choices'][0]['message']);     
      }

      $json = json_encode(array(
        'type' => $type,
        'body' => $body,
        'api_respons_obj' => $api_response_obj
      ));    

      wp_send_json_success( $json );

    }

  }  

  public function getModels() {

    $headers = $this->getHeaders();
    if( empty( $headers ) ){
      return NULL;
    }

    $transient = get_transient('zmp-aia-models');
    if($transient){
      return $transient;
    }

    //after return of transient, functions are only executed every 86400

    //clean conversations once daily (all messages older than 24 hours)
    global $zmpaiassistant;
    $zmpaiassistant['app']->cleanGPTConversations();

    $response = wp_remote_get( 'https://api.openai.com/v1/models', 
      array(
        'method'      => 'GET',
        'timeout'     => 45,
        'user-agent'  =>  'curl/7.64.1',
        'headers'     => $headers,
        'body'        => NULL
      )
    );

    if( is_wp_error( $response ) ){

      return NULL;

    }

    $body = wp_remote_retrieve_body( $response );

    //only for testing if has data!
    $models = json_decode($body);
    if (!isset($models->data)){
      return NULL;
    }

    set_transient('zmp-aia-models',$models->data, 86400);

    return $models->data;

  }

  public function getHeaders(){

    global $zmpaiassistant;
    $credentials = $zmpaiassistant['app']->getCredentials('api_key');

    $headers = array();

    if($credentials){

      $headers = array(
        'Authorization'  => "Bearer $credentials",
        'Content-Type'  => 'application/json',
      );

      $org_id = $zmpaiassistant['app']->getCredentials('org_id');
      if($org_id){
        $headers['OpenAI-Organization'] = $org_id;
      }

    }

    return $headers;

  }  

  public function sanitizeAndreorderFormInputs($values_array){

    $sanitized_array = array();

    foreach($values_array as $row){

      if($row['name'] == 'zmp-aia-input-prompt' || $row['name'] == 'zmp-aia-input-imageprompt'){

        $sanitized_array[sanitize_key($row['name'])] = sanitize_textarea_field($row['value']);

      } else {

        $sanitized_array[sanitize_key($row['name'])] = sanitize_text_field($row['value']);

      }

    }

    return $sanitized_array;

  }

}
