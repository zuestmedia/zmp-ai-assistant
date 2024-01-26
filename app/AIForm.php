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
              <div class="uk-width-1-1">       
                <ul class="js-filter uk-child-width-1-1 uk-grid-collapse" uk-grid>
                  <li class="tag_completion">
                    <p><?php echo esc_html__( 'You input some text as a prompt, and the AI Assistant will generate a text completion that attempts to match whatever context or pattern you gave it. For example, if you give the AI Assistant the prompt, "As Descartes said, I think, therefore", it will return the completion " I am" with high probability.', 'zmp-ai-assistant' ); ?></p>
                  </li>
                  <li class="tag_image">
                    <p><?php echo esc_html__( 'The image generations AI Assistant allows you to create an original image given a text prompt. Generated images can have a size of 1024x1024, 1792x1024, or 1024x1792 pixels.', 'zmp-ai-assistant' ); ?></p>
                  </li>
                </ul>
              </div>
              <div class="uk-width-expand@m uk-width-1-1">       
                <ul class="js-filter uk-child-width-1-1 uk-grid-collapse" uk-grid>
                  <li class="tag_completion">
                    <div uk-grid class="">
                      <div class="uk-width-expand@m uk-width-1-1">
                        <div class="uk-margin-small-top">
                            <label class="uk-form-label" for="zmp-aia-input-prompt"><?php echo esc_html__( 'Prompt', 'zmp-ai-assistant' ); ?></label>
                            <div class="uk-form-controls">
                              <textarea name="zmp-aia-input-prompt" class="uk-textarea" id="zmp-aia-input-prompt" rows="16" style="background:#fff;border: 1px solid #8c8f94;"></textarea>
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
                                      <input name="zmp-aia-input-max_tokens" class="uk-input uk-form-small" id="zmp-aia-input-max_tokens" type="number" max="4096" min="1" step="1" placeholder="16">
                                  </div>
                              </div>
                            </li>
                            <li>
                              <div uk-grid class="uk-child-width-1-2 uk-grid-small">
                                <div>
                                  <div class="uk-margin-small-top">
                                    <label class="uk-form-label" for="zmp-aia-input-temperature">temperature</label>
                                    <div class="uk-form-controls">
                                        <input name="zmp-aia-input-temperature" class="uk-input uk-form-small" id="zmp-aia-input-temperature" type="number" max="1" min="0" step="0.1" placeholder="1">
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
                            <label class="uk-form-label" for="zmp-aia-input-imageprompt"><?php echo esc_html__( 'Prompt', 'zmp-ai-assistant' ); ?></label>
                            <div class="uk-form-controls">
                              <textarea name="zmp-aia-input-imageprompt" class="uk-textarea" id="zmp-aia-input-imageprompt" rows="12" style="background:#fff;border: 1px solid #8c8f94;"></textarea>
                            </div>
                        </div>
                        <div class="uk-margin-small-top">
                            <p class="uk-form-label"><?php echo esc_html__( 'Result', 'zmp-ai-assistant' ); ?></p>
                            <div class="uk-form-controls">
                              <div id="zmp-aia-image-result" class="uk-child-width-1-1 uk-child-width-1-2@m" uk-grid>
                              </div>
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
            <?php wp_nonce_field( 'zmp_aia_nonce' ); ?>       
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

            <div class="uk-modal-header uk-background-primary uk-section-primary uk-preserve-color">
              <h2 class="uk-modal-title uk-align-left uk-margin-small-top uk-margin-small-bottom" style="color:#fff;"><?php echo esc_html__( 'AI Assistant', 'zmp-ai-assistant' ); ?></h2>
              <div class="uk-align-left uk-margin-top uk-margin-remove-bottom uk-margin-small-right" style="line-height: 32px;font-weight: 600;color:#fff;"><?php echo esc_html__( 'Choose template:', 'zmp-ai-assistant' ); ?></div>
              <div class="uk-align-left uk-margin-top uk-margin-small-bottom">
                <select class="uk-select uk-width-medium uk-form-small" id="zmp-aia-template">
                  <option value="default"><?php echo esc_html__( 'Default', 'zmp-ai-assistant' ); ?></option>
                  <?php $this->getTemplatesSelectOptions(); ?>
                </select>
              </div>
              <div class="uk-align-left uk-margin-top uk-margin-small-bottom">
                <form id="zmp-aia-save-template" name="zmp-aia-save-template">
                  <div uk-grid class="uk-child-width-auto uk-grid-collapse">
                    <div>
                      <input name="zmp-aia-save-template-name" required class="uk-input uk-form-small" type="text" placeholder="Template name" style="border:none;border-bottom-right-radius:unset;border-top-right-radius:unset;">
                    </div>
                    <div>
                      <?php wp_nonce_field( 'zmp_aia_nonce' ); ?>
                      <button type="submit" form="zmp-aia-save-template" value="Save" class="uk-button uk-button-muted uk-button-small uk-align-left"><?php echo esc_html__( 'Save', 'zmp-ai-assistant' ); ?></button>
                    </div>
                  </div>
                </form>                
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
