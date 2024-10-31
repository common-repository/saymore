<?php
/*
Plugin Name: SayMore 
Plugin URI: http://fainmailmarketing.com
Description: Adds User to list when comment is made to Wordpress Page
Version: 1.0
Author: Nick Holdren
Author URI: http://nickholdren.com/
License: GPL2
*/



/**
* Contains the SayMore class
*
* Contiains the SayMore class and the object to instantiate the SayMore plugin
*
* LICENSE:
*
* @copyright  2010 Fanmail Marketing
* @license   
* @version    1.0
* @link       http://dev.zend.com/package/PackageName
* @since      File available since Release 1.0
*/

/**
* SayMore plugin class
*
* 
*
* @copyright  2010 Fanmail Marketing
* @license    
* @version    Release: 1.0
* @link       
* @since      Class available since Release 1.0
*/ 
class SayMore{
	
	//Define Properties
	var $mid;
	var $lid;
	
	
	/**
	* Constructor for the SayMore class
	*
	*/ 
	function __construct(){
		
		
		add_action('admin_menu', array(&$this,'createMenu'));
		add_action( 'admin_init',  array(&$this,'registerSettings'));
		add_action('comment_post',array(&$this,'optIn'));
	
		//Set the properties for SayMore
		$this->lid = get_option('saymore_lid');
		$this->mid = get_option('saymore_mid');
		
	}
	
	/**
	* Constructor for the SayMore class
	*
	*/ 
	function SayMore(){
		
		
		add_action('admin_menu', array(&$this,'createMenu'));
		add_action( 'admin_init',  array(&$this,'registerSettings'));
		add_action('comment_post',array(&$this,'optIn'));
	
		//Set the properties for SayMore
		$this->lid = get_option('saymore_lid');
		$this->mid = get_option('saymore_mid');
		
	}	
	
	/**
	* Creates the menu SayMore plugin
	*
	*/
	function createMenu(){
		
		//Create a menu page for the plugin
		add_menu_page('Settings', 'SayMore', 9, basename(__FILE__), array(&$this, 'showAdminPanel'));

		
	}
	
	/**
	* Shows the admin panel for the SayMore plugin
	*
	*/
	function showAdminPanel(){
		?>
        <h2>SayMore Settings</h2>
<form method="post" action="options.php">
  <?php settings_fields('saymore_settings'); ?>
  <fieldset>
    <legend>
    <h3>ExactTarget Account</h3>
    </legend>
    <table cellpadding="10" cellspacing="10">
      <tr>
        <td width="200"><label for="mid">Account ID:</label></td>
        <td><input type="text" name="saymore_mid" value="<?php echo ($this->mid); ?>" />
          <br /></td>
      </tr>
      <tr>
        <td colspan="2"><span class="description">This identifies your account and is found in: Admin > Account Settings > Account ID</span></td>
      </tr>
      <tr>
        <td><label for="lid">List ID:</label></td>
        <td><input type="text" name="saymore_lid" value="<?php echo($this->lid); ?>" />
          <br /></td>
      </tr>
      <tr>
        <td colspan="2"><span class="description">This identifies the list to populate and is found in: Subscribers > My Lists > Check Box List Name > Properties > List Identification > ID</span></td>
      </tr>
    </table>
  </fieldset>
  <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</form>
		<?php
	}
	
	/**
	* Registers the settings for the SayMore plugin
	*
	*/
	function registerSettings(){
		
		register_setting( 'saymore_settings', 'saymore_mid' );
		register_setting( 'saymore_settings', 'saymore_lid' );
		
	}
	
	
	/**
	* Adds the user to a list for possible optin
	*
	*/
	function optIn($comment_ID){
	
		$comment = get_comment($comment_ID);
	
		$email = $comment->comment_author_email;
		$name = $comment->comment_author;
		$post = $comment->comment_post_ID;
		
		//URL to post information to
		$url = 'http://cl.exct.net/subscribe.aspx';

		$fields_string = '';
		
		$fields = array(
						'Email Address'=>urlencode($email),
						'MID' => urlencode($this->mid),
						'lid' => urlencode($this->lid)
				);


		//url-ify the data for the POST
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }

		//Remove last ampersand
		rtrim($fields_string,'&');

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_POST,count($fields));
		
		/* Suppress header in cURL's output */
		curl_setopt($ch, CURLOPT_HEADER, 0);

		/* Make curl return the results to a variable instead of STDOUT */
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

		//execute post
		$result = curl_exec($ch);
		
		//close connection
		curl_close($ch);
		
	}

	/**
	* Destructor for the SayMore class
	*
	* 
	*/
	function __desctruct(){
		
		
	}

}


$saymore = new SayMore();

?>
