<?php

namespace ZMP\AIAssistant;

class SettingsInit {

    private $admin_menu;

    function __construct( ){

      global $zmplugin;

      $this->admin_menu = new \ZMP\Plugin\AdminMenu( $zmplugin['zmp-ai-assistant']->getSlug() );
      $this->admin_menu->setSubMenuPageParent( $zmplugin['zmplugin']->getSlug() );
      $this->admin_menu->setSubMenuPageName( __('AI Assistant', 'zmp-ai-assistant') );

      $template = clone $zmplugin['default_admin_template'];
      $template->setOptPra( $zmplugin['zmp-ai-assistant']->getSlug() );
      $template->setDisplayName( $zmplugin['zmp-ai-assistant']->getDisplayName() );
      $template->setTitle( __('ZMP AI Assistant', 'zmp-ai-assistant') );
      $template->setDescr( __('This Plugin adds the AI Assistant to the edit-post-screen, to directly create content and images with artificial intelligence, while working in WordPress editor.', 'zmp-ai-assistant') );

      $template->setVersion( $zmplugin['zmp-ai-assistant']->getConfigVersion() );

      //start adminpage container
      $this->admin_menu->setSubMenuPage( $template->htmlAdminMenuStart() );
        $settingsform = new \ZMP\AIAssistant\Settings($template);
        $this->admin_menu->setSubMenuPage( $settingsform->getForm() );
      $this->admin_menu->setSubMenuPage( $template->htmlAdminMenuEnd() );
      //end adminpage container

      $this->admin_menu->addSubMenuPage();

    }

}
