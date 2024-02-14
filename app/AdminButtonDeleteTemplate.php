<?php

namespace ZMP\AIAssistant;

class AdminButtonDeleteTemplate extends \ZMP\Plugin\AdminButton {

  public function getActionButton( $label = 'Do!' ,$class = NULL, $template = NULL ) {

    return '<a title="Delete" href="'.
      wp_nonce_url( admin_url($this->getAdminUrl().'&template='.esc_attr( $template ).'&switcherpos=2' ), $this->getName(), 'zmnonce' )
      .'"'.\ZMP\Plugin\Helpers::getAttribute( $class, NULL, ' class="%s"' ).
    '><i uk-icon="close"></i>' .esc_html( $label ).'</a>';

  }

  public function doAction(){    

    if ( isset($_GET['template']) && !isset($_GET['settings-updated']) ) {

      //sanitizing & validation
      $san_template = sanitize_text_field( $_GET['template'] );

      $template = NULL;
      if( preg_match('/^[A-Za-z0-9_\-\s]+$/', $san_template) ){
        $template = $san_template;
      }

      if($template){

        global $zmpaiassistant;

        $zmpaiassistant['app']->deleteGPTTemplate($template);

        $this->SuccessNotification();

      }

    } /*else {

      return 'no zmaction defined';

    }*/

  }

}
