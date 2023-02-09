<?php

namespace ZMP\AIAssistant;

class AIForm {

  /**
   *                                    
   *   -- Main Form Inputs --           Completion   Edit   Image
   * - zmp-aia-input-mode                  x          x      x    (hidden input!)
   * - zmp-aia-input-prompt                x                 
   * - zmp-aia-input-suffix                x
   * - zmp-aia-input-instruction                      x
   * - zmp-aia-input-input                            x
   * - zmp-aia-input-imageprompt                             x
   * 
   *   -- Advanced Settings --          Completion   Edit   Image
   * - zmp-aia-input-model                 x          x
   * - zmp-aia-input-max_tokens            x
   * - zmp-aia-input-temperature           x          x
   * - zmp-aia-input-top_p                 x          x
   * - zmp-aia-input-n                     x          x      x   
   * - zmp-aia-input-best_of               x
   * - zmp-aia-input-stop                  x                             (only string till now... but array possible up to 4 values mostly used in chat apps)
   * - zmp-aia-input-presence_penalty      x
   * - zmp-aia-input-frequency_penalty     x
   * - zmp-aia-input-size                                    x
   * - zmp-aia-input-response_format                         x
   * 
   *  --> switch main form inputs
   *  --> keep always same advanced form, but endable disable needed inputs
   * 
   */
  public function getForm() {

    global $zmpaiassistant;

    ?>      
              
      <form id="zmp-aia-form" name="zmp-aia-form" class="uk-form-stacked">
        <fieldset class="uk-fieldset">
          <div id="zmp-aia-filtered" uk-filter="target: .js-filter"> 
            <div uk-grid="masonry:true" class="uk-grid-row-collapse"> 
              <div class="uk-width-3-4@m uk-width-1-1">       
                <ul class="js-filter uk-child-width-1-1 uk-grid-collapse" uk-grid>
                  <li class="tag_completion">
                    <h3 class="uk-margin-remove-top"><?php echo esc_html__( 'Completion', 'zmp-ai-assistant' ); ?></h3>
                    <p><?php echo esc_html__( 'You input some text as a prompt, and the model will generate a text completion that attempts to match whatever context or pattern you gave it. For example, if you give the API the prompt, "As Descartes said, I think, therefore", it will return the completion " I am" with high probability.', 'zmp-ai-assistant' ); ?></p>
                  </li>
                  <li class="tag_edit">
                    <h3 class="uk-margin-remove-top"><?php echo esc_html__( 'Edit', 'zmp-ai-assistant' ); ?></h3>
                    <p><?php echo esc_html__( 'You provide some text and an instruction for how to modify it, and the model will attempt to edit it accordingly. This is a natural interface for translating, editing, and tweaking text. This is also useful for refactoring and working with code.', 'zmp-ai-assistant' ); ?></p>
                  </li>
                  <li class="tag_image">
                    <h3 class="uk-margin-remove-top"><?php echo esc_html__( 'Image', 'zmp-ai-assistant' ); ?></h3>
                    <p><?php echo esc_html__( 'The image generations endpoint allows you to create an original image given a text prompt. Generated images can have a size of 256x256, 512x512, or 1024x1024 pixels. Smaller sizes are faster to generate. You can request 1-10 images at a time using the n parameter.', 'zmp-ai-assistant' ); ?></p>
                  </li>
                </ul>
              </div>
              <div class="uk-width-1-4@m uk-width-1-1">
                <div>
                  <ul id="zmp-aia-filter-nav" class="uk-subnav uk-subnav-pill uk-margin-remove-bottom" uk-margin>
                    <li class="uk-active" uk-filter-control=".tag_completion" data-mode="completion"><a href="#"><?php echo esc_html__( 'Completion', 'zmp-ai-assistant' ); ?></a></li>
                    <li uk-filter-control=".tag_edit" data-mode="edit"><a href="#"><?php echo esc_html__( 'Edit', 'zmp-ai-assistant' ); ?></a></li>
                    <li uk-filter-control=".tag_image" data-mode="image"><a href="#"><?php echo esc_html__( 'Image', 'zmp-ai-assistant' ); ?></a></li>
                  </ul>
                  <input name="zmp-aia-input-mode" class="uk-input uk-form-small uk-hidden" id="zmp-aia-input-mode" type="text" value="completion">
                </div> 
              </div>
              <div class="uk-width-3-4@m uk-width-1-1">       
                <ul class="js-filter uk-child-width-1-1 uk-grid-collapse" uk-grid>
                  <li class="tag_completion">
                    <div class="uk-margin-small-top">
                        <label class="uk-form-label" for="zmp-aia-input-prompt"><?php echo esc_html__( 'Prompt', 'zmp-ai-assistant' ); ?></label>
                        <div class="uk-form-controls">
                          <textarea name="zmp-aia-input-prompt" class="uk-textarea" id="zmp-aia-input-prompt" rows="17" style="background:#fff;border: 1px solid #8c8f94;"></textarea>
                        </div>
                    </div> 
                    <div class="uk-margin-small-top">
                        <label class="uk-form-label" for="zmp-aia-input-suffix"><?php echo esc_html__( 'Suffix (optional)', 'zmp-ai-assistant' ); ?></label>
                        <div class="uk-form-controls">
                          <textarea name="zmp-aia-input-suffix" class="uk-textarea" id="zmp-aia-input-suffix" rows="2" style="background:#fff;border: 1px solid #8c8f94;"></textarea>
                        </div>
                    </div>  
                  </li>
                  <li class="tag_edit">
                    <div uk-grid>
                      <div class="uk-width-1-2@m">
                        <div class="uk-margin-small-top">
                            <label class="uk-form-label" for="zmp-aia-input-input"><?php echo esc_html__( 'Input', 'zmp-ai-assistant' ); ?></label>
                            <div class="uk-form-controls">
                              <textarea name="zmp-aia-input-input" class="uk-textarea" id="zmp-aia-input-input" rows="17" style="background:#fff;border: 1px solid #8c8f94;"></textarea>
                            </div>
                        </div>
                        <div class="uk-margin-small-top">
                            <label class="uk-form-label" for="zmp-aia-input-instruction"><?php echo esc_html__( 'Instruction', 'zmp-ai-assistant' ); ?></label>
                            <div class="uk-form-controls">
                              <textarea name="zmp-aia-input-instruction" class="uk-textarea" id="zmp-aia-input-instruction" rows="2" style="background:#fff;border: 1px solid #8c8f94;"></textarea>
                            </div>
                        </div>
                      </div>
                      <div class="uk-width-1-2@m">
                        <div class="uk-margin-small-top">
                            <label class="uk-form-label" for="zmp-aia-edit-result"><?php echo esc_html__( 'Result', 'zmp-ai-assistant' ); ?></label>
                            <div class="uk-form-controls">
                            <textarea class="uk-textarea" id="zmp-aia-edit-result" rows="22" style="background:#fff;border: 1px solid #8c8f94;"></textarea>
                            </div>
                        </div>                        
                      </div>
                    </div>
                  </li>
                  <li class="tag_image">
                    <div class="uk-margin-small-top">
                        <label class="uk-form-label" for="zmp-aia-input-imageprompt"><?php echo esc_html__( 'Prompt', 'zmp-ai-assistant' ); ?></label>
                        <div class="uk-form-controls">
                          <textarea name="zmp-aia-input-imageprompt" class="uk-textarea" id="zmp-aia-input-imageprompt" rows="2" style="background:#fff;border: 1px solid #8c8f94;"></textarea>
                        </div>
                    </div>
                    <div class="uk-margin-small-top">
                        <label class="uk-form-label" for="zmp-aia-image-result"><?php echo esc_html__( 'Result', 'zmp-ai-assistant' ); ?></label>
                        <div class="uk-form-controls">
                          <div id="zmp-aia-image-result" class="uk-child-width-1-1 uk-child-width-1-2@m" uk-grid>
                          </div>
                        </div>
                    </div>    
                  </li>
                </ul>
              </div>
              <div class="uk-width-1-4@m uk-width-1-1">
                <div id="zmp-aia-advanced-form">
                  <ul class="js-filter uk-child-width-1-1 uk-grid-collapse" uk-grid>
                    <li class="tag_completion tag_edit">
                      <div class="uk-margin-small-top">
                          <label class="uk-form-label" for="zmp-aia-input-model">model</label>
                          <div class="uk-form-controls">
                              <select name="zmp-aia-input-model" class="uk-select uk-form-small" id="zmp-aia-input-model" style="max-width: 100%;border: 1px solid #8c8f94;">
                                <?php echo $this->getModelsSelectOptions(); ?>
                              </select>
                          </div>
                      </div>
                    </li>
                    <li class="tag_completion tag_edit tag_image">
                      <div class="uk-margin-small-top">
                          <label class="uk-form-label" for="zmp-aia-input-n">n</label>
                          <div class="uk-form-controls">
                              <input name="zmp-aia-input-n" class="uk-input uk-form-small" id="zmp-aia-input-n" type="number" max="10" min="1" step="1" placeholder="1">
                          </div>
                      </div>
                    </li>
                    <li class="tag_completion">
                      <div class="uk-margin-small-top">
                          <label class="uk-form-label" for="zmp-aia-input-best_of">best_of</label>
                          <div class="uk-form-controls">
                              <input name="zmp-aia-input-best_of" class="uk-input uk-form-small" id="zmp-aia-input-best_of" type="number" max="10" min="1" step="1" placeholder="1">
                          </div>
                      </div>
                    </li>
                    <li class="tag_completion">
                      <div class="uk-margin-small-top">
                          <label class="uk-form-label" for="zmp-aia-input-max_tokens">max_tokens</label>
                          <div class="uk-form-controls">
                              <input name="zmp-aia-input-max_tokens" class="uk-input uk-form-small" id="zmp-aia-input-max_tokens" type="number" max="4096" min="1" step="1" placeholder="16">
                          </div>
                      </div>
                    </li>
                    <li class="tag_completion">
                      <div class="uk-margin-small-top">
                          <label class="uk-form-label" for="zmp-aia-input-stop">stop</label>
                          <div class="uk-form-controls">
                              <input name="zmp-aia-input-stop" class="uk-input uk-form-small" id="zmp-aia-input-stop" type="text">
                          </div>
                      </div>
                    </li>
                    <li class="tag_completion tag_edit">
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
                    <li class="tag_completion">
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
                    <li class="tag_image">
                      <div class="uk-margin-small-top">
                          <label class="uk-form-label" for="zmp-aia-input-size">size</label>
                          <div class="uk-form-controls">
                              <select name="zmp-aia-input-size" class="uk-select uk-form-small" id="zmp-aia-input-size" style="max-width: 100%;border: 1px solid #8c8f94;">
                                  <option value="256x256">256x256</option>
                                  <option value="512x512">512x512</option>
                                  <option value="1024x1024">1024x1024</option>
                              </select>
                          </div>
                      </div>
                    </li>
                    <li class="tag_image">
                      <div class="uk-margin-small-top">
                          <label class="uk-form-label" for="zmp-aia-input-response_format">response_format</label>
                          <div class="uk-form-controls">
                              <select name="zmp-aia-input-response_format" class="uk-select uk-form-small" id="zmp-aia-input-response_format" style="max-width: 100%;border: 1px solid #8c8f94;">
                                  <option value="url">url</option>
                                  <option value="b64_json">b64_json</option>
                              </select>
                          </div>
                      </div>
                    </li>
                  </ul>
                </div>                    
              </div>
            </div>            
            <button type="submit" form="zmp-aia-form" value="Submit" id="zmp-aia-button-send" class="uk-button uk-button-primary uk-align-left uk-margin-top uk-margin-remove-bottom"><?php echo esc_html__( 'Submit', 'zmp-ai-assistant' ); ?></button>
            <button id="zmp-aia-button-reset" class="uk-button uk-button-muted uk-align-left uk-margin-top uk-margin-remove-bottom" type="button"><?php echo esc_html__( 'Reset', 'zmp-ai-assistant' ); ?></button>
            <div class="uk-align-left uk-margin-top uk-margin-remove-bottom" style="line-height: 40px;">
              <ul class="js-filter" uk-grid>
                <li class="tag_completion tag_edit">
                  <a id="zmp-aia-action-copy-selected" class="" href="#"><?php echo esc_html__( 'Copy selected', 'zmp-ai-assistant' ); ?></a>
                </li>
                <li class="tag_completion tag_edit">
                  <a id="zmp-aia-action-paste-selected-to-title" class="" href="#"><?php echo esc_html__( 'Selected to title', 'zmp-ai-assistant' ); ?></a>
                </li>
                <li class="tag_completion tag_edit">
                  <a id="zmp-aia-action-paste-selected-to-content" class="" href="#"><?php echo esc_html__( 'Selected to content/block', 'zmp-ai-assistant' ); ?></a>
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
        <div id="zmp-aia-modal-screen" class="uk-modal-dialog" style="width: 1200px;">

            <div class="uk-modal-header uk-background-primary uk-section-primary">
              <h2 class="uk-modal-title uk-align-left uk-margin-small-top uk-margin-small-bottom" style="color:#fff;"><?php echo esc_html__( 'AI Assistant', 'zmp-ai-assistant' ); ?></h2>
              <button class="uk-modal-close-default" type="button" uk-close></button> 
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
                      <button type="submit" form="zmp-aia-save-template" value="Save" class="uk-button uk-button-muted uk-button-small uk-align-left"><?php echo esc_html__( 'Save', 'zmp-ai-assistant' ); ?></button>
                    </div>
                  </div>
                </form>                
              </div>             
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
