<?php

namespace ZMP\AIAssistant;

class ErrorMenu {

  /**
  * Minimal Errorpage to show when ZMToolbox Core is missing
  * change FavoritePost namespace for new plugin! --> and autoloader!
  */

  /**
    * Plugin Location -> Name comes from Location!
    * Format --> 'zm-toolbox/zm-toolbox.php'
    * @var string
    * @access private
    */
  private $location;

  /**
    * Display Name
    * @var string
    * @access private
    */
  private $display_name = NULL;

  /**
    * Menu Page HTML
    * @var string
    * @access private
    */
  private $menu_page = NULL;

  /**
  * Location is minimal value to setup a plugin without menu page.
  */
  function __construct( $location ) {

		$this->location = $location;

	}

  /**
  * Get Plugin Location
  */
  public function getLocation() {

    return $this->location;

  }

  /**
   * Get the value of Display Name
   */
  public function getDisplayName(){

      return $this->display_name;

  }
  /**
   * Set the value of Display Name
   */
  public function setDisplayName($display_name){

      $this->display_name = $display_name;

  }

  /**
  * GET PLUGIN SLUG
  */
  public function getSlug() {

    return trim(dirname($this->getLocation()), '/');

  }

  /**
  * Create Menu Pages
  * MainMenu or SubMenu
  */

  /**
    * Set the value of Menu Page HTML
    */
  public function setMenuPage($menu_page){

      $this->menu_page .= $menu_page;

  }
  /**
   * Get the value of Menu Page HTML
   */
  public function getMenuPage()  {

      echo '<div class="wrap"><h1>'.esc_html( $this->getDisplayName() ).'</h1><div class="error notice"><p><strong>Error:</strong> '.esc_html( $this->getDisplayName() ).' is an addon of <a>ZMPlugin</a>. Please install ZMPlugin to use this addon!</p></div></div>';

  }
  /**
   * Main menu Page
   */
  public function MenuPage() {

    add_menu_page( $this->getDisplayName(), $this->getDisplayName(), 'manage_options', $this->getSlug(), array($this,'getMenuPage') );

  }

  /**
   * ADD Main menu Page to admin menu
   */
  public function addMenuPage() {

    add_action( 'admin_menu', array( $this, 'MenuPage' )  );

  }

}
