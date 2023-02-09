<?php

namespace ZMP\AIAssistant;

class Metabox {

  private $apicalls;

  function __construct( ){

    $this->addAIAssistanttoPostEditScreen();

  }
  
  public function addAIAssistanttoPostEditScreen(){
  
    //holds only the button for modal
    add_action( 'add_meta_boxes', array( $this, 'createMetaBox' ) );
  
    //no form tags allowed in metabox!!! so form goes to admin-notice
    add_action('admin_notices', array( $this, 'addModalOutsideOfPostEditForm' ) );
  
  }

  public function createMetaBox() {

    $post_types_array = get_post_types( array('public' => true) );

    //remove attachment from array
    unset($post_types_array['attachment']);

    add_meta_box(
        'zmp-ai-assistant-metabox',
        __( 'AI Assistant', 'zmp-ai-assistant' ),
        array($this, 'getMetaBoxContent'),
        $post_types_array
    );
    
  }

  //no forms in metabox direct, because its inside a form of post (poststuff!)
  public function getMetaBoxContent($post){    

    global $zmpaiassistant;
    $credentials = $zmpaiassistant['app']->getCredentials('api_key');

    if(!$credentials){

      global $zmplugin;
      $admin_menu_url = admin_url( 'admin.php?page='.$zmplugin['zmp-ai-assistant']->getSlug() );

      ?>

      <div class="uk-text-center uk-margin-top">
        <a class="uk-button uk-button-primary uk-border-rounded" href="<?php echo esc_attr( $admin_menu_url ); ?>"><?php echo esc_html__( 'Install AI Assistant', 'zmp-ai-assistant' ); ?></a>
        <p><?php echo esc_html__( 'Open AI API key missing', 'zmp-ai-assistant' ); ?></p>
      </div>
  
      <?php

    } else {

      ?>

      <div class="uk-text-center uk-margin-top">
        <a class="uk-button uk-button-primary uk-border-rounded" href="#zmp-aia-modal" uk-toggle><?php echo esc_html__( 'Open AI Assistant', 'zmp-ai-assistant' ); ?></a>
        <p><?php echo esc_html__( 'Click "Start" to create content using GPT-3 s artificial intelligence.', 'zmp-ai-assistant' ); ?></p>
      </div>
  
      <?php

    }

  }
  
  public function addModalOutsideOfPostEditForm() {

    global $zmpaiassistant;
    $credentials = $zmpaiassistant['app']->getCredentials('api_key');

    if($credentials){

      $screen = get_current_screen();

      if( 'post' == $screen->base || 'post-new' == $screen->base ) {

        $aiform = new \ZMP\AIAssistant\AIForm();

        $aiform->getModalFormPostEditScreen();   

      }

    }

  }


}
