jQuery(document).ready(function($){

  //init
  zmpaiastart($);
  zmpaiasetTemplates($);

  //form actions
  zmpaiaformreset($);
  zmpaiaformsend($);
  zmpaiaformsavetemplate($);

  //copy paste 
  zmpaiaSlectedTextButtons($);

});

  function zmpaiastart($){ 

    UIkit.util.on('#zmp-aia-filtered', 'afterFilter', function () {
      zmpaiasetModeHiddenInput($);
    });  

  }

  function zmpaiasetModeHiddenInput($){
    var mode = $('#zmp-aia-filter-nav li.uk-active').data('mode');
    $('#zmp-aia-input-mode').val(mode);
  }

  function zmpaiasetTemplates($){

    //set default template values on first load (if 0 it gets either default settings or the template set in settings...)
    zmpaiaaddTemplateValues($,0);

    //then set values on each change of template
    $('#zmp-aia-template').on('change', function() {
      zmpaiaaddTemplateValues($,this.value);
    });

  }  

  function zmpaiaformreset($){

    $( "#zmp-aia-button-reset" ).click(function() {

      //reset all form inputs
      $('#zmp-aia-form').trigger("reset");
      //load default values to form inputs
      zmpaiaaddTemplateValues($,'default');
      //set default template in select list (does not trigger on change function... only for browser events
      //so needs upper function! its better, because different value (default != 0) is possible...)
      //on change could be triggered with this: .trigger('change');
      $('#zmp-aia-template').val('default');

    });    

  }

  function zmpaiaaddTemplateValues($,template_name){

    //start loading screen
    zmpaiaLoadingScreen($);

    //get post id to prepare prompt data with title and more...
    var urlParams = new URLSearchParams(window.location.search);
    var postid = 0;
    if(urlParams.has('post') == true){
      postid = urlParams.get('post');
    }

    const zmgptdata = {      
      template_name: template_name,
      id: postid,
      security: zmp_aia_ajax.zmp_aia_nonce_get_gpt_templates
    };

    var zmpajaxrequest = wp.ajax.post( "get_gpt_templates", zmgptdata );

    zmpajaxrequest.done(function(response) {
        
      //success
      zmpaiaSuccessLoadingScreen($);

      const newresponse = JSON.parse(response);

      zmpaiasetInputValues($,newresponse);

    }); 

    zmpajaxrequest.fail(function(response) {
        
      //fail
      zmpaiaFailLoadingScreen($);

    });

    zmpajaxrequest.always(function(response) {

      //remove LoadingScreen from Modal
      zmpaiaremoveLoadingScreen($);

    }); 

  }

  function zmpaiasetInputValues($,obj) {

    var mode = 'completion';
    if ('mode' in obj === true) {
      mode = obj.mode;
    }
    //always set mode, if not in obj set to default = completion.
    zmpaiachangeFilterMode(mode);

    $.each(obj, function( index, value ) {
      $( '#zmp-aia-input-' + index ).val(value);
    });

  }

  function zmpaiachangeFilterMode($mode){

    //trigger the click event to change filters...
    //get the element subnav button
    var element = document.querySelector('li[uk-filter-control=".tag_' + $mode + '"]>a');
    //trigger the click event
    element.click();

  }

  function zmpaiaformsend($){

    $('#zmp-aia-form').submit(function(event) {
      
      event.preventDefault();

      //init loading screen
      zmpaiaLoadingScreen($);

      // Get all the forms elements and their values in one step
      var values = $(this).serializeArray();

      const zmgptdata = {
        values: values,
        security: zmp_aia_ajax.zmp_aia_nonce_get_gpt_data
      };
  
      var zmpajaxrequest = wp.ajax.post( "get_gpt_data", zmgptdata );
    
      zmpajaxrequest.done(function(response) {

        //success
        zmpaiaSuccessLoadingScreen($);

        const newresponse = JSON.parse(response);

        console.log(newresponse);

        zmpaiagetresponse($,newresponse);

      }); 

      zmpajaxrequest.fail(function(response) {
        
        //fail
        zmpaiaFailLoadingScreen($);
  
      });

      zmpajaxrequest.always(function(response) {

        //remove LoadingScreen from Modal
        zmpaiaremoveLoadingScreen($);

      });     
    
    });   

  }

  //verarbeite response von api und sende daten zu result...
  function zmpaiagetresponse($,response){

    const apiresponse = response['api_respons_obj'];

    if ('error' in apiresponse === true) {

      UIkit.notification({
        message: '<span uk-icon=\'icon: close\'></span> Error: ' + apiresponse['error']['message'],
        status: 'danger',
        pos: 'top-center',
        group: 'zmp-aia',
        timeout: 2000
      });

    }

    if(response['type'] == 'completion'){

      $.each(apiresponse['choices'], function( index, value ) {
        $('#zmp-aia-input-prompt').val($('#zmp-aia-input-prompt').val() + ' ' + apiresponse['choices'][index]['message']['content']);

        if(apiresponse['choices'][index]['finish_reason'] !== 'stop'){

          zmpaiaWarnings(apiresponse['choices'][index]['finish_reason']);

        }

      });

    } else if(response['type'] == 'image'){

      $('#zmp-aia-image-result').empty(); //remove old results, so no errors with init image upload...

      $.each(apiresponse['data'], function( index, value ) {
        $('#zmp-aia-image-result').append('<div><img style="width:100%;" src="' + apiresponse['data'][index]['url'] + '" /><a href="#" class="zmp-aia-image-upload" data-image-url="' + apiresponse['data'][index]['url'] + '">Upload to media library</a></div>');
      });

      //init image upload
      zmpaiaImageUpload($);

    } 

  }

  function zmpaiaWarnings(message){

    UIkit.notification({
      message: '<span uk-icon=\'icon: close\'></span> Finish reason: ' + message,
      status: 'warning',
      pos: 'top-center',
      group: 'zmp-aia',
      timeout: 2000
    });

  }

  function zmpaiaImageUpload($){

    $('.zmp-aia-image-upload').click(function(event) {
      
      event.preventDefault();

      //init loading screen
      zmpaiaLoadingScreen($);

      var imageurl = $(this).data('image-url');

      const zmgptdata = {
        imageurl: imageurl,
        security: zmp_aia_ajax.zmp_aia_nonce_save_gpt_image
      };

      var zmpajaxrequest = wp.ajax.post( "save_gpt_image", zmgptdata );

      zmpajaxrequest.done(function(response) {

        //success
        zmpaiaSuccessLoadingScreen($);

        //if uploaded change link to 
        $('a.zmp-aia-image-upload[data-image-url="' + imageurl + '"]').replaceWith('<div><i uk-icon="check"></i> Saved</div>');

      }); 

      zmpajaxrequest.fail(function(response) {
        
        //fail
        zmpaiaFailLoadingScreen($);          

      });
      
      zmpajaxrequest.always(function(response) {

        //remove LoadingScreen from Modal
        zmpaiaremoveLoadingScreen($);

      });    

    });

  }

  function zmpaiaformsavetemplate($){

    $('#zmp-aia-save-template').submit(function(event) {
      
      event.preventDefault();

      //init loading screen
      zmpaiaLoadingScreen($);

      // Get all the forms elements and their values in one step
      var template_data = $(this).serializeArray();
      var form_data = $('#zmp-aia-form').serializeArray();

      const zmgptdata = {
        template_data: template_data,
        form_data: form_data,
        security: zmp_aia_ajax.zmp_aia_nonce_save_gpt_template
      };
  
      var zmpajaxrequest = wp.ajax.post( "save_gpt_template", zmgptdata );
    
      zmpajaxrequest.done(function(response) {

        //success
        zmpaiaSuccessLoadingScreen($);

        //append option to templates after is saved successfully
        $('#zmp-aia-template').append($('<option>', {
          value: response,
          text: response
        }));

        //chose new value
        $('#zmp-aia-template').val(response);

      }); 

      zmpajaxrequest.fail(function(response) {
        
        //fail
        zmpaiaFailLoadingScreen($);          

      });
      
      zmpajaxrequest.always(function(response) {

        //remove LoadingScreen from Modal
        zmpaiaremoveLoadingScreen($);

      });     
    
    });  
        
  }

  function zmpaiaLoadingScreen($){

    var element1 = $('<div/>')
      .attr("id", "zmpaia-loading-screen")
      .addClass("uk-overlay-default uk-position-cover uk-position-z-index");
      var element2 = $("<div/>")
        .attr("id", "zmpaia-loading-screen-body")
        .addClass("uk-position-center");      
        var element3 = $("<div/>")
          .attr("id", "zmpaia-loading-screen-spinner")
          .attr("uk-spinner", "");

    var element = element2.append(element3);
    var element = element1.append(element);

    $('#zmp-aia-modal-screen').prepend(element);

  }
  function zmpaiaSuccessLoadingScreen($){

    $('#zmpaia-loading-screen-spinner').remove();

    var element = $('<div/>').attr("uk-icon", "icon:check;ratio:2;")

    $('#zmpaia-loading-screen-body').append(element);

  }
  function zmpaiaFailLoadingScreen($){

    $('#zmpaia-loading-screen-spinner').remove();

    var element = $('<div/>').attr("uk-icon", "icon:close;ratio:2;")

    $('#zmpaia-loading-screen-body').append(element);

    UIkit.notification({
      message: '<span uk-icon=\'icon: close\'></span> error',
      status: 'danger',
      pos: 'top-center',
      group: 'zmp-aia',
      timeout: 2000
    });

  }
  function zmpaiaremoveLoadingScreen($){

    $('#zmpaia-loading-screen').remove();

  }

  //by id eg: "zmp-aia-input-prompt" 
  function zmpaiagetSelectedTextbyID(id){
    // Obtain the object reference for the <textarea>
    var txtarea = document.getElementById(id);

    // Obtain the index of the first selected character
    var start = txtarea.selectionStart;

    // Obtain the index of the last selected character
    var finish = txtarea.selectionEnd;

    // Obtain the selected text
    var sel = txtarea.value.substring(start, finish);

    return sel;

  }

  function zmpaiagetSelectedText(){

    var prompt_selection = zmpaiagetSelectedTextbyID("zmp-aia-input-prompt");

    if(prompt_selection){
      return prompt_selection;
    }

  }
  
  function zmpaiaSlectedTextButtons($){

    $('#zmp-aia-action-copy-selected').click(function(event) {
      
      event.preventDefault();
      
      selectedtext = zmpaiagetSelectedText();

      if(selectedtext){

        zmpaiaCopy(selectedtext);

        UIkit.notification({
          message: '<span uk-icon=\'icon: check\'></span> copied to clipboard',
          status: 'success',
          pos: 'top-center',
          group: 'zmp-aia',
          timeout: 500
        });

      }

    });

    $('#zmp-aia-action-paste-selected-to-title').click(function(event) {
      
      event.preventDefault();

      selectedtext = zmpaiagetSelectedText();

      if(selectedtext){

        zmpaiaInserttoTitle($,selectedtext);

        UIkit.notification({
          message: '<span uk-icon=\'icon: check\'></span> selection inserted to title',
          status: 'success',
          pos: 'top-center',
          group: 'zmp-aia',
          timeout: 500
        });

      }

    });

    $('#zmp-aia-action-paste-selected-to-content').click(function(event) {
      
      event.preventDefault();

      selectedtext = zmpaiagetSelectedText();

      if(selectedtext){

        zmpaiaInserttoEditor($,selectedtext);

        UIkit.notification({
          message: '<span uk-icon=\'icon: check\'></span> selection inserted to editor',
          status: 'success',
          pos: 'top-center',
          group: 'zmp-aia',
          timeout: 500
        });

      }

    });

  }

  //copy to clipboard
  function zmpaiaCopy(string){

    navigator.clipboard.writeText(string);    

  }

  function zmpaiaInserttoEditor($,string){

    /*//does not work always...
    if(tinyMCE && tinyMCE.activeEditor){

      tinyMCE.activeEditor.selection.setContent( string );

    }*/

    if( zmpaiaIsGutenbergActive() && zmpaiaIsWPBlocksSet() ){

      const newBlock = wp.blocks.createBlock( "core/paragraph", {
        content: string,
      });

      wp.data.dispatch( "core/editor" ).insertBlocks( newBlock );

    } else {

      if( $( "#wp-content-wrap" ).hasClass('tmce-active') === true){

        //iframe is active
        $("#content_ifr").contents().find("#tinymce").text( string );

      } else {

        //is text editor
        $( "#content" ).val( string );

      }

    }

  }

  function zmpaiaIsWPBlocksSet() {
    return typeof wp !== 'undefined' && typeof wp.blocks !== 'undefined';
  }
  function zmpaiaIsGutenbergActive() {
    return document.body.classList.contains( 'block-editor-page' );
  }

  function zmpaiaInserttoTitle($,string){

    if( zmpaiaIsGutenbergActive() && zmpaiaIsWPBlocksSet() ){

      //with gutenberg editor
      $( ".wp-block-post-title" ).text( string );

    } else {

      //with classic editor
      $( "#title" ).val( string );
      $( "#title-prompt-text" ).addClass( 'screen-reader-text' );

    }
    
  }

  

 