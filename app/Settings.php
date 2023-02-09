<?php

namespace ZMP\AIAssistant;

class Settings {

    private $form;

    private $zmp_aia_template_delete;

    function __construct($template){

      global $zmplugin;
      global $zmpaiassistant;

      $this->zmp_aia_template_delete = new \ZMP\AIAssistant\AdminButtonDeleteTemplate( 'zmp_aia_template_delete' );
      $this->zmp_aia_template_delete->setAdminUrl('admin.php?page='.$zmplugin['zmp-ai-assistant']->getSlug() );
      $this->zmp_aia_template_delete->setAdminNotice(__('Template deleted.', 'zmp-ai-assistant'));
      $this->zmp_aia_template_delete->registerGetParams();      

      $this->form = new \ZMP\Plugin\FormSettings();
      $this->form->setOptionsgroupName( $zmplugin['zmp-ai-assistant']->getOptGroup() ); //optional präfix for form name and field names, to better organize, avoid duplicates and shorter names in addfield
      $this->form->setAction('options.php');
      $this->form->setMethod('post');
      $this->form->setClass('uk-form-horizontal');
      //$this->form->setOuterClass('uk-card uk-card-body uk-card-small uk-padding-remove-left');
      $this->form->setOuterClass('uk-card uk-card-body uk-card-small uk-padding-remove-left uk-padding-remove-top');
      $this->form->setInnerClass('uk-form-controls');
      $this->form->setSettingsFields(1); //necessary hidden security and settings fields for options.php handler

        $this->form->addField('html',
          $template->htmlSettingsFormAccordionStart(__('API credentials for GPT-3','zmp-ai-assistant'))
        );    

          $this->form->addField(
            'input',
              array(
                'type'=> 'text',
                'label'=> __('API Key', 'zmp-ai-assistant'),
                'class'=>'uk-input uk-form-width-large',
                'name'=>$zmpaiassistant['app']->getCredentialsFieldName('api_key'),
                'default_value'=>$zmpaiassistant['app']->getCredentialsDefaultValue()
              ),
              'option_mod',//type
              '_api_credentials',//optionsgroup + "_option_mod_name"
              'sanitizearray'
          );

          $this->form->addField(
            'input',
              array(
                'type'=> 'text',
                'label'=> __('Organization ID (optional)', 'zmp-ai-assistant'),
                'class'=>'uk-input uk-form-width-large',
                'name'=>$zmpaiassistant['app']->getCredentialsFieldName('org_id'),
                'default_value'=>$zmpaiassistant['app']->getCredentialsDefaultValue()
              ),
              'option_mod',//type
              '_api_credentials',//optionsgroup + "_option_mod_name"
              'sanitizearray'
          );
          
          
        $this->form->addField('html',
          $template->htmlSettingsFormAccordionBetween(__('Default settings','zmp-ai-assistant'))
        );  

          //Filter Start
          $this->form->addField('html',           
            '<div uk-filter="target: .js-filter">
              <div>
                <ul class="uk-subnav uk-subnav-pill" uk-margin>
                  <li class="uk-active" uk-filter-control=".tag_completion"><a href="#">'.__('Completion','zmp-ai-assistant').'</a></li>
                  <li uk-filter-control=".tag_edit"><a href="#">'.__('Edit','zmp-ai-assistant').'</a></li>
                  <li uk-filter-control=".tag_image"><a href="#">'.__('Image','zmp-ai-assistant').'</a></li>
                </ul>
              </div>          
              <ul class="js-filter uk-child-width-1-1 uk-grid-collapse" uk-grid>');

            $this->form->addField('html', '<li class="tag_completion">' );

              $this->form->addField('html', '<h2>'.__('Completion','zmp-ai-assistant').'</h2>' );

              $this->form->addField(
                'textarea',
                  array(
                    'label'=> 'prompt',
                    'class'=>'uk-textarea uk-form-width-large',
                    'name'=>$zmpaiassistant['app']->getSettingFieldName('prompt'),
                    'default_value'=>$zmpaiassistant['app']->getSettingDefaultValue('prompt')
                  ),
                  'option_mod',//type
                  '_api_settings',//optionsgroup + "_option_mod_name"
                  'strarray'
              );
    
              $this->form->addField(
                'textarea',
                  array(
                    'label'=> 'suffix',
                    'class'=>'uk-textarea uk-form-width-large',
                    'name'=>$zmpaiassistant['app']->getSettingFieldName('suffix'),
                    'default_value'=>$zmpaiassistant['app']->getSettingDefaultValue('suffix')
                  ),
                  'option_mod',//type
                  '_api_settings',//optionsgroup + "_option_mod_name"
                  'strarray'
              );
                                    
            $this->form->addField('html', '</li><li class="tag_edit">' );

              $this->form->addField('html', '<h2>'.__('Edit','zmp-ai-assistant').'</h2>' );

              $this->form->addField(
                'textarea',
                  array(
                    'label'=> 'input',
                    'class'=>'uk-textarea uk-form-width-large',
                    'name'=>$zmpaiassistant['app']->getSettingFieldName('input'),
                    'default_value'=>$zmpaiassistant['app']->getSettingDefaultValue('input')
                  ),
                  'option_mod',//type
                  '_api_settings',//optionsgroup + "_option_mod_name"
                  'strarray'
              );
    
              $this->form->addField(
                'textarea',
                  array(
                    'label'=> 'instruction',
                    'class'=>'uk-textarea uk-form-width-large',
                    'name'=>$zmpaiassistant['app']->getSettingFieldName('instruction'),
                    'default_value'=>$zmpaiassistant['app']->getSettingDefaultValue('instruction')
                  ),
                  'option_mod',//type
                  '_api_settings',//optionsgroup + "_option_mod_name"
                  'strarray'
              );

            $this->form->addField('html', '</li><li class="tag_image">' );

              $this->form->addField('html', '<h2>'.__('Image','zmp-ai-assistant').'</h2>' );
          
              $this->form->addField(
                'textarea',
                  array(
                    'label'=> 'prompt',
                    'class'=>'uk-textarea uk-form-width-large',
                    'name'=>$zmpaiassistant['app']->getSettingFieldName('imageprompt'),
                    'default_value'=>$zmpaiassistant['app']->getSettingDefaultValue('imageprompt')
                  ),
                  'option_mod',//type
                  '_api_settings',//optionsgroup + "_option_mod_name"
                  'strarray'
              );


            $this->form->addField('html', '</li><li class="tag_completion tag_edit">' );
              $this->form->addField(
                'select',
                  array(
                    'label'=> 'model',
                    'class'=>'uk-select uk-form-width-large uk-form-small',
                    'options'=> $zmpaiassistant['app']->getModelsOptionsChoices(),
                    'name'=>$zmpaiassistant['app']->getSettingFieldName('model'),
                    'default_value'=>$zmpaiassistant['app']->getSettingDefaultValue('model')
                  ),
                  'option_mod',//type
                  '_api_settings',//optionsgroup + "_option_mod_name"
                  'textarray'
              );

              

            $this->form->addField('html', '</li><li class="tag_completion tag_edit tag_image">' );
              $this->form->addField(
                'input',
                  array(
                    'type'=> 'number',
                    'step'=> '1',
                    'min'=> '1',
                    'max'=> '10',
                    'label'=> 'n',
                    'class'=>'uk-input uk-form-width-large uk-form-small',
                    'name'=>$zmpaiassistant['app']->getSettingFieldName('n'),
                    'default_value'=>$zmpaiassistant['app']->getSettingDefaultValue('n')
                  ),
                  'option_mod',//type
                  '_api_settings',//optionsgroup + "_option_mod_name"
                  'numericarray'
              );  

            $this->form->addField('html', '</li><li class="tag_completion">' );
              $this->form->addField(
                'input',
                  array(
                    'type'=> 'number',
                    'step'=> '1',
                    'min'=> '1',
                    'max'=> '10',
                    'label'=> 'best_of',
                    'class'=>'uk-input uk-form-width-large uk-form-small',
                    'name'=>$zmpaiassistant['app']->getSettingFieldName('best_of'),
                    'default_value'=>$zmpaiassistant['app']->getSettingDefaultValue('best_of')
                  ),
                  'option_mod',//type
                  '_api_settings',//optionsgroup + "_option_mod_name"
                  'numericarray'
              );

            $this->form->addField('html', '</li><li class="tag_completion">' );
              $this->form->addField(
                'input',
                  array(
                    'type'=> 'number',
                    'step'=> '1',
                    'min'=> '1',
                    'max'=> '4096',
                    'label'=> 'max_tokens',
                    'class'=>'uk-input uk-form-width-large uk-form-small',
                    'name'=>$zmpaiassistant['app']->getSettingFieldName('max_tokens'),
                    'default_value'=>$zmpaiassistant['app']->getSettingDefaultValue('max_tokens')
                  ),
                  'option_mod',//type
                  '_api_settings',//optionsgroup + "_option_mod_name"
                  'numericarray'
              );

            $this->form->addField('html', '</li><li class="tag_completion">' );
              $this->form->addField(
                'input',
                  array(
                    'type'=> 'text',
                    'label'=> 'stop',
                    'class'=>'uk-input uk-form-width-large uk-form-small',
                    'name'=>$zmpaiassistant['app']->getSettingFieldName('stop'),
                    'default_value'=>$zmpaiassistant['app']->getSettingDefaultValue('stop')
                  ),
                  'option_mod',//type
                  '_api_settings',//optionsgroup + "_option_mod_name"
                  'strarray'
              );
              
            $this->form->addField('html', '</li><li class="tag_completion tag_edit">' );
              $this->form->addField(
                'input',
                  array(
                    'type'=> 'number',
                    'step'=> '0.1',
                    'min'=> '0',
                    'max'=> '1',
                    'label'=> 'temperature',
                    'class'=>'uk-input uk-form-width-large uk-form-small',
                    'name'=>$zmpaiassistant['app']->getSettingFieldName('temperature'),
                    'default_value'=>$zmpaiassistant['app']->getSettingDefaultValue('temperature')
                  ),
                  'option_mod',//type
                  '_api_settings',//optionsgroup + "_option_mod_name"
                  'floatarray'
              );

            $this->form->addField('html', '</li><li class="tag_completion tag_edit">' );
              $this->form->addField(
                'input',
                  array(
                    'type'=> 'number',
                    'step'=> '0.1',
                    'min'=> '0',
                    'max'=> '1',
                    'label'=> 'top_p',
                    'class'=>'uk-input uk-form-width-large uk-form-small',
                    'name'=>$zmpaiassistant['app']->getSettingFieldName('top_p'),
                    'default_value'=>$zmpaiassistant['app']->getSettingDefaultValue('top_p')
                  ),
                  'option_mod',//type
                  '_api_settings',//optionsgroup + "_option_mod_name"
                  'floatarray'
              );
              
            
    
            $this->form->addField('html', '</li><li class="tag_completion">' );
              $this->form->addField(
                'input',
                  array(
                    'type'=> 'number',
                    'step'=> '0.1',
                    'min'=> '-2.0',
                    'max'=> '2.0',
                    'label'=> 'presence_penalty',
                    'class'=>'uk-input uk-form-width-large uk-form-small',
                    'name'=>$zmpaiassistant['app']->getSettingFieldName('presence_penalty'),
                    'default_value'=>$zmpaiassistant['app']->getSettingDefaultValue('presence_penalty')
                  ),
                  'option_mod',//type
                  '_api_settings',//optionsgroup + "_option_mod_name"
                  'floatarray'
              );

            $this->form->addField('html', '</li><li class="tag_completion">' );
              $this->form->addField(
                'input',
                  array(
                    'type'=> 'number',
                    'step'=> '0.1',
                    'min'=> '-2.0',
                    'max'=> '2.0',
                    'label'=> 'frequency_penalty',
                    'class'=>'uk-input uk-form-width-large uk-form-small',
                    'name'=>$zmpaiassistant['app']->getSettingFieldName('frequency_penalty'),
                    'default_value'=>$zmpaiassistant['app']->getSettingDefaultValue('frequency_penalty')
                  ),
                  'option_mod',//type
                  '_api_settings',//optionsgroup + "_option_mod_name"
                  'floatarray'
              );  

            $this->form->addField('html', '</li><li class="tag_image">' );
              $this->form->addField(
                'select',
                  array(
                    'label'=> 'size',
                    'class'=>'uk-select uk-form-width-large uk-form-small',
                    'options'=> array(
                      array('option'=> '256x256','value'=>'256x256'),
                      array('option'=> '512x512','value'=>'512x512'),
                      array('option'=> '1024x1024','value'=>'1024x1024')
                    ),
                    'name'=>$zmpaiassistant['app']->getSettingFieldName('size'),
                    'default_value'=>$zmpaiassistant['app']->getSettingDefaultValue('size')
                  ),
                  'option_mod',//type
                  '_api_settings',//optionsgroup + "_option_mod_name"
                  'textarray'
              );  
    
            $this->form->addField('html', '</li><li class="tag_image">' );
              $this->form->addField(
                'select',
                  array(
                    'label'=> 'response_format',
                    'class'=>'uk-select uk-form-width-large uk-form-small',
                    'options'=> array(
                      array('option'=> 'url','value'=>'url'),
                      array('option'=> 'b64_json','value'=>'b64_json')
                    ),
                    'name'=>$zmpaiassistant['app']->getSettingFieldName('response_format'),
                    'default_value'=>$zmpaiassistant['app']->getSettingDefaultValue('response_format')
                  ),
                  'option_mod',//type
                  '_api_settings',//optionsgroup + "_option_mod_name"
                  'textarray'
              );  
                                    
            $this->form->addField('html', '</li>' );
                  
          //Filter End
          $this->form->addField('html', '</ul></div>' );
        
        $this->form->addField('html',
          $template->htmlSettingsFormAccordionBetween(__('Templates','zmp-ai-assistant'))
        );            
         
          $this->form->addField(
            'select',
              array(
                'label'=> __('Default Template', 'zmp-ai-assistant'),
                'class'=>'uk-select uk-form-width-large uk-form-small',
                'name'=>$zmpaiassistant['app']->getDefaultTemplateFieldName(),
                'default_value'=>$zmpaiassistant['app']->getDefaultTemplateDefaultValue(),
                'options'=>$zmpaiassistant['app']->getTemplatesOptionsChoices(),
                'validation' => 'slug'
              )
          ); 

          $this->form = $this->getTemplatesList($this->form);

      /*  $this->form->addField('html',
          $template->htmlSettingsFormAccordionBetween(__('About','zmp-ai-assistant'))
        );            
         
          $this->form->addField('html', '"AI Assistant" Plugin' );
       */ 

        $this->form->addField('html',
          $template->htmlSettingsFormAccordionEnd()
        );

      $this->form = $template->accordionFormSetup($this->form);

    }

    public function getForm(){

      return $this->form->getForm();

    }

    public function getTemplatesList($form){

      global $zmpaiassistant;

      $templates = $zmpaiassistant['app']->getTemplatesOrderedArray();

      $form->addField('html', '<div class="uk-card uk-card-body uk-card-small uk-padding-remove-left uk-padding-remove-top"><label class="uk-form-label">'.__('Template List','zmp-ai-assistant').'</label><div class="uk-form-controls">');

      $form->addField('html', '<table class="uk-table uk-table-hover uk-table-divider uk-form-width-large uk-table-small"><tbody>');

      foreach($templates as $key => $value){

        $form->addField('html', '<tr>');
        $form->addField('html', '<td>'.esc_html( $key ).'</td>');
        $form->addField('html', '<td class="uk-text-right">'.$this->zmp_aia_template_delete->getActionButton( '', 'uk-text-right', $key ).'</td>');
        $form->addField('html', '</tr>');

      }

      $form->addField('html', '</tbody></table>');

      $form->addField('html', '</div></div>');

      return $form;

    }

}
