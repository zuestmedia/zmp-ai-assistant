<?php

namespace ZMP\AIAssistant;

class AIForm {

  /**
   *                                    
   *   -- Main Form Inputs --           Chat     Image
   * - zmp-aia-input-mode                x         x    (hidden input!)
   * - zmp-aia-input-prompt              x                         
   * - zmp-aia-input-imageprompt                   x
   * 
   *   -- Advanced Settings --          Chat     Image
   * - zmp-aia-input-model                x                  
   * - zmp-aia-input-max_tokens           x        
   * - zmp-aia-input-temperature          x                 
   * - zmp-aia-input-top_p                x               
   * - zmp-aia-input-stop                 x                (only string till now... but array possible up to 4 values mostly used in chat apps)
   * - zmp-aia-input-presence_penalty     x        
   * - zmp-aia-input-frequency_penalty    x       
   * - zmp-aia-input-size                          x
   * - zmp-aia-input-quality                       x
   * - zmp-aia-input-style                         x
   * 
   */
  public function getForm() {

    global $zmpaiassistant;

    ?>      
              
      <form id="zmp-aia-form" name="zmp-aia-form" class="uk-form-stacked">
        <fieldset class="uk-fieldset">
          <div id="zmp-aia-filtered" uk-filter="target: .js-filter"> 
            <div uk-grid class="uk-grid-row-collapse">
              <div class="uk-width-1-1">
                <div>
                  <ul id="zmp-aia-filter-nav" class="uk-subnav uk-subnav-pill uk-margin-remove-bottom" uk-margin>
                    <li class="uk-active" uk-filter-control=".tag_completion" data-mode="completion"><a href="#"><?php echo esc_html__( 'Chat', 'zmp-ai-assistant' ); ?></a></li>
                    <li uk-filter-control=".tag_image" data-mode="image"><a href="#"><?php echo esc_html__( 'Image', 'zmp-ai-assistant' ); ?></a></li>
                  </ul>
                  <input name="zmp-aia-input-mode" class="uk-input uk-form-small uk-hidden" id="zmp-aia-input-mode" type="text" value="completion">
                </div> 
              </div> 
              <div class="uk-width-expand@m uk-width-1-1">       
                <ul class="js-filter uk-child-width-1-1 uk-grid-collapse" uk-grid>
                  <li class="tag_completion">
                    <div uk-grid class="">
                      <div class="uk-width-expand@m uk-width-1-1">
                        <div class="uk-margin-small-top">
                            <div class="uk-form-controls">
                              <div id="zmp-aia-div-chat" class="uk-padding-small uk-background-default uk-overflow-auto uk-height-medium" style="white-space: pre-wrap;" contenteditable="true"></div>
                            </div>
                        </div> 
                        <div class="uk-margin-small-top">
                            <label class="uk-form-label" for="zmp-aia-input-prompt"><?php echo esc_html__( 'Message the AI assistant', 'zmp-ai-assistant' ); ?></label>
                            <div class="uk-form-controls">
                              <textarea placeholder="<?php echo esc_html__( 'Your request to the AI assistant', 'zmp-ai-assistant' ); ?>" name="zmp-aia-input-prompt" class="uk-textarea" id="zmp-aia-input-prompt" rows="4" style="background:#fff;border: 1px solid #8c8f94;"></textarea>
                            </div>
                        </div> 
                      </div> 
                      <div class="uk-width-1-4@m uk-width-1-1" hidden id="zmpa-aia-toggle-settings-chat">
                        <div id="zmp-aia-advanced-form-chat">
                          <ul class="uk-child-width-1-1 uk-grid-collapse" uk-grid>
                            <li>
                              <div class="uk-margin-small-top">
                                  <label class="uk-form-label" for="zmp-aia-input-model">model</label>
                                  <div class="uk-form-controls">
                                      <select name="zmp-aia-input-model" class="uk-select uk-form-small" id="zmp-aia-input-model" style="max-width: 100%;border: 1px solid #8c8f94;">
                                        <?php $this->getModelsSelectOptions(); ?>
                                      </select>
                                  </div>
                              </div>
                            </li>
                            <li>
                              <div class="uk-margin-small-top">
                                  <label class="uk-form-label" for="zmp-aia-input-max_tokens">max_tokens</label>
                                  <div class="uk-form-controls">
                                      <input name="zmp-aia-input-max_tokens" class="uk-input uk-form-small" id="zmp-aia-input-max_tokens" type="number" min="1" step="1" placeholder="4096">
                                  </div>
                              </div>
                            </li>
                            <li>
                              <div uk-grid class="uk-child-width-1-2 uk-grid-small">
                                <div>
                                  <div class="uk-margin-small-top">
                                    <label class="uk-form-label" for="zmp-aia-input-temperature">temperature</label>
                                    <div class="uk-form-controls">
                                        <input name="zmp-aia-input-temperature" class="uk-input uk-form-small" id="zmp-aia-input-temperature" type="number" max="2" min="0" step="0.1" placeholder="1">
                                    </div>
                                  </div>
                                </div>
                                <div>
                                  <div class="uk-margin-small-top">
                                    <label class="uk-form-label" for="zmp-aia-input-top_p">top_p</label>
                                    <div class="uk-form-controls">
                                        <input name="zmp-aia-input-top_p" class="uk-input uk-form-small" id="zmp-aia-input-top_p" type="number" max="1" min="0" step="0.1" placeholder="1">
                                    </div>
                                  </div>
                                </div>
                              </div>                      
                            </li>
                            <li>
                              <div uk-grid class="uk-child-width-1-2 uk-grid-small">
                                <div>
                                  <div class="uk-margin-small-top">
                                    <label class="uk-form-label" for="zmp-aia-input-presence_penalty">presence_penalty</label>
                                    <div class="uk-form-controls">
                                        <input name="zmp-aia-input-presence_penalty" class="uk-input uk-form-small" id="zmp-aia-input-presence_penalty" min="-2.0" max="2.0" step="0.1" type="number" placeholder="0">
                                    </div>
                                  </div>
                                </div>
                                <div>
                                  <div class="uk-margin-small-top">
                                    <label class="uk-form-label" for="zmp-aia-input-frequency_penalty">frequency_penalty</label>
                                    <div class="uk-form-controls">
                                        <input name="zmp-aia-input-frequency_penalty" class="uk-input uk-form-small" id="zmp-aia-input-frequency_penalty" min="-2.0" max="2.0" step="0.1" type="number" placeholder="0">
                                    </div>
                                  </div>
                                </div>
                              </div> 
                            </li>
                            <li>
                              <div class="uk-margin-small-top">
                                  <label class="uk-form-label" for="zmp-aia-input-stop">stop</label>
                                  <div class="uk-form-controls">
                                      <input name="zmp-aia-input-stop" class="uk-input uk-form-small" id="zmp-aia-input-stop" type="text">
                                  </div>
                              </div>
                            </li>
                          </ul>
                        </div>   
                      </div> 
                    </div> 
                  </li>
                  <li class="tag_image">                  
                    <div uk-grid class="">
                      <div class="uk-width-expand@m uk-width-1-1">
                        <div class="uk-margin-small-top">
                            <div class="uk-form-controls uk-padding-small uk-background-default uk-overflow-auto uk-height-medium">
                              <div id="zmp-aia-image-result" class="uk-child-width-1-1 uk-child-width-1-2@m" uk-grid>
                              </div>
                            </div>
                        </div> 
                        <div class="uk-margin-small-top">
                            <label class="uk-form-label" for="zmp-aia-input-imageprompt"><?php echo esc_html__( 'Instructions for image generation', 'zmp-ai-assistant' ); ?></label>
                            <div class="uk-form-controls">
                              <textarea name="zmp-aia-input-imageprompt" placeholder="<?php echo esc_html__( 'Describe the image to be generated', 'zmp-ai-assistant' ); ?>" class="uk-textarea" id="zmp-aia-input-imageprompt" rows="4" style="background:#fff;border: 1px solid #8c8f94;"></textarea>
                            </div>
                        </div>  
                      </div>  
                      <div class="uk-width-1-4@m uk-width-1-1" hidden id="zmpa-aia-toggle-settings-img">                        
                        <div id="zmp-aia-advanced-form">
                          <ul class="uk-child-width-1-1 uk-grid-collapse" uk-grid>                  
                            <li>
                              <div class="uk-margin-small-top">
                                  <label class="uk-form-label" for="zmp-aia-input-size">size</label>
                                  <div class="uk-form-controls">
                                      <select name="zmp-aia-input-size" class="uk-select uk-form-small" id="zmp-aia-input-size" style="max-width: 100%;border: 1px solid #8c8f94;">
                                          <option value="1024x1024">1024x1024</option>
                                          <option value="1792x1024">1792x1024</option>
                                          <option value="1024x1792">1024x1792</option>
                                      </select>
                                  </div>
                              </div>
                            </li>
                            <li>
                              <div class="uk-margin-small-top">
                                  <label class="uk-form-label" for="zmp-aia-input-quality">quality</label>
                                  <div class="uk-form-controls">
                                      <select name="zmp-aia-input-quality" class="uk-select uk-form-small" id="zmp-aia-input-quality" style="max-width: 100%;border: 1px solid #8c8f94;">
                                          <option value="standard">standard</option>
                                          <option value="hd">hd</option>
                                      </select>
                                  </div>
                              </div>
                            </li>
                            <li>
                              <div class="uk-margin-small-top">
                                  <label class="uk-form-label" for="zmp-aia-input-style">style</label>
                                  <div class="uk-form-controls">
                                      <select name="zmp-aia-input-style" class="uk-select uk-form-small" id="zmp-aia-input-style" style="max-width: 100%;border: 1px solid #8c8f94;">
                                          <option value="vivid">vivid</option>
                                          <option value="natural">natural</option>
                                      </select>
                                  </div>
                              </div>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>   
                  </li>
                </ul>
              </div>
            </div>      
            <button type="submit" form="zmp-aia-form" value="Submit" id="zmp-aia-button-send" class="uk-button uk-button-primary uk-align-left uk-margin-top uk-margin-remove-bottom"><?php echo esc_html__( 'Submit', 'zmp-ai-assistant' ); ?></button>
            <button id="zmp-aia-button-reset" class="uk-button uk-button-muted uk-align-left uk-margin-top uk-margin-remove-bottom" type="button"><?php echo esc_html__( 'Reset', 'zmp-ai-assistant' ); ?></button>
            <div class="uk-align-left uk-margin-top uk-margin-remove-bottom" style="line-height: 40px;">
              <ul class="js-filter" uk-grid>
                <li class="tag_completion">
                  <a id="zmp-aia-action-copy-selected" class="" href="#"><?php echo esc_html__( 'Copy selected', 'zmp-ai-assistant' ); ?></a>
                </li>
                <li class="tag_completion">
                  <a id="zmp-aia-action-paste-selected-to-title" class="" href="#"><?php echo esc_html__( 'Selected to title', 'zmp-ai-assistant' ); ?></a>
                </li>
                <li class="tag_completion">
                  <a id="zmp-aia-action-paste-selected-to-content" class="" href="#"><?php echo esc_html__( 'Selected to content/block', 'zmp-ai-assistant' ); ?></a>
                </li>
                <li class="tag_completion">
                  <a class="" uk-toggle="target: #zmpa-aia-toggle-settings-chat" href="#"><?php echo esc_html__( 'Settings', 'zmp-ai-assistant' ); ?></a>
                </li>
                <li class="tag_image">
                  <a class="" uk-toggle="target: #zmpa-aia-toggle-settings-img" href="#"><?php echo esc_html__( 'Settings', 'zmp-ai-assistant' ); ?></a>
                </li>
              </ul>
            </div> 
          </div>        
        </fieldset>
      </form>
            
    <?php

  }

  public function getModalFormPostEditScreen() {

    ?>
      <style>#zmp-aia-modal{z-index:99999998;} .uk-subnav-pill > li > a{text-decoration:none;} .uk-notification{z-index:99999999;}</style>

      <div id="zmp-aia-modal" uk-modal="esc-close:false;bg-close:false;">
        <div id="zmp-aia-modal-screen" class="uk-modal-dialog" style="width: 1080px;">

            <div class="uk-modal-header uk-background-primary uk-section-primary uk-preserve-color uk-padding" style="padding-bottom:25px;">
              <div uk-grid class="uk-child-width-1-1 uk-child-width-expand@s">
                <h2 class="uk-modal-title" style="color:#fff;"><?php echo esc_html__( 'AI Assistant', 'zmp-ai-assistant' ); ?></h2>
                <div class="">
                  <select class="uk-select uk-width-expand" id="zmp-aia-template">
                    <option value="default"><?php echo esc_html__( 'Default', 'zmp-ai-assistant' ); ?></option>
                    <?php $this->getTemplatesSelectOptions(); ?>
                  </select>
                  <input id="zmp-aia-hidden-chat-id" hidden class="uk-hidden" value="<?php echo esc_attr( time() ); ?>">
                </div>
                <div class="">
                  <form id="zmp-aia-save-template" name="zmp-aia-save-template">
                    <div uk-grid class="uk-child-width-expand uk-grid-collapse">
                      <div>
                        <input name="zmp-aia-save-template-name" id="zmp-aia-save-template-name" required class="uk-input" type="text" placeholder="Template name" style="border:none;border-bottom-right-radius:unset;border-top-right-radius:unset;">
                      </div>
                      <div class="uk-width-auto">
                        <button type="submit" form="zmp-aia-save-template" value="Save" class="uk-button uk-button-muted"><?php echo esc_html__( 'Save', 'zmp-ai-assistant' ); ?></button>
                      </div>
                    </div>
                  </form>                
                </div>
              </div>
              <button class="uk-modal-close-default uk-light" type="button" uk-close></button>         
            </div>

            <div class="uk-modal-body uk-background-muted">                
              
              <?php $this->getForm(); ?>

            </div>
        </div>
      </div>

    <?php

  }

  public function getModelsSelectOptions(){

    global $zmpaiassistant;

    $models_ordered = $zmpaiassistant['app']->getModelsOrderedArray();

    $date_format = get_option( 'date_format' );
    
    foreach($models_ordered as $model){

      $date = date($date_format,$model['created']);

      echo '<option value="'.esc_attr( $model['id'] ).'">'.esc_html( $model['id'] ).' ('.esc_html( $date ).')</option>';

    }

  }

  public function getTemplatesSelectOptions(){

    global $zmpaiassistant;

    $templates = $zmpaiassistant['app']->getTemplatesOrderedArray();
    $default_template = $zmpaiassistant['app']->getDefaultTemplate();
    
    foreach($templates as $key => $value){

      $selected = '';
      if($key == $default_template){

        $selected = 'selected ';

      }

      echo '<option '.esc_attr( $selected ).'value="'.esc_attr( $key ).'">'.esc_html( $key ).'</option>';

    }

  }

}
