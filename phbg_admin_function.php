<?php
if ( ! defined( 'ABSPATH' ) ) {
	
	exit;
}
/********************************** function which will handle all the task related to admin*************************************/

/* it will add our own meta box in admin panel*/
function phbg_admin() {

    add_meta_box( '',
        'phoe_badge',
        'phbg_create',
        'phoe_badge', 'normal', 'high'
    );

}


	
/*********************
it will show the form  as meta boxes when badge
 is going to be added or updated

**********************************/	
function phbg_create()
{
  $prod_id=get_the_ID();	
  $action=sanitize_text_field($_GET['action']);
	if($action=='edit')
	{
		if(get_post_status($res->post_id)!='draft')
		phbg_edit_badge();
		else
		phbg_new_badge();
	}
	else
	{
		phbg_new_badge();
	}
}


/** it will remove metaboxes from admin panel*/
function phbg_remove_meta_boxes() {
remove_meta_box( 'postcustom', 'phoe_badge', 'normal' );
remove_meta_box( 'commentstatusdiv', 'phoe_badge', 'normal' );
remove_meta_box( 'commentsdiv', 'phoe_badge', 'normal' );
remove_meta_box( 'wpfooter', 'phoe_badge', 'normal' );
remove_meta_box( 'postdivrich', 'phoe_badge', 'advance' );
	
}

function phbg_admin_setting()
{
	add_meta_box("badge-meta-box", "Badge Box", "phbg_meta_box", "product", "side", "low", null);   
}

/** it will show a panel on woocommerce add/ update product page***********/

function phbg_meta_box()
	{
		global $wpdb;
		$action=sanitize_text_field($_GET['action']);
		if($action=='edit')
		{
			$prod_id=sanitize_text_field($_GET['post']);
			$row=get_post_meta($prod_id,'_phoe_badge_name');

			$sql = $wpdb->prepare( 'SELECT * FROM '.PHBGPRE.'postmeta where meta_key=%s','_phoe_badge' );
			$res=$wpdb->get_results($sql);	
			echo"<div class='option-group'><h3>Add Badge</h3>";
			echo"<select name='badge_name'>";
			echo"<option value='none'>none</option>";
			foreach($res as $res)
			{
				$res1=json_decode($res->meta_value);
				if(get_post_status($res->post_id)!='trash')
				{
					if($row[0]!=$res->post_id)
					{
						echo"<option value='".$res->post_id."'>".$res1->title."</option>";	
					}
					else
					{
						echo"<option value='".$res->post_id."' selected>".$res1->title."</option>";
					}
				}
				
			}
			echo"</select></div>";

		}
		else
		{
			
			$sql = $wpdb->prepare( 'SELECT * FROM '.PHBGPRE.'postmeta where meta_key=%s','_phoe_badge' );
			$res=$wpdb->get_results($sql);

			echo"<div class='option-group'><h3>Add Badge</h3>";
			echo"<select name='badge_name'>";
			echo"<option value='0'>none</option>";
			foreach($res as $res)
			{
				$res1=json_decode($res->meta_value);
				if(get_post_status($res->post_id)!='trash')
				{
					echo"<option value='".$res->post_id."'>".$res1->title."</option>";
				}
			}
			echo"</select></div>";
		}		
	}


/**********************************

this function is used to save the new badge 
or updated badge information. it will also stored
the information of badge related to product.

***********************************************/
function phbg_posts($post_id) {
	
	if( $_POST['post_type'] == 'phoe_badge' )
	{
		
		//echo $post_id;
		
		$ch= sanitize_text_field($_POST['badge_type']);
		
		if($ch==1)
		{
			$row['type']='text';
			
			$row['title']=sanitize_text_field($_POST['post_title']);
			
			$row['text']=sanitize_text_field($_POST['b_text']);
			
			$row['height']=sanitize_text_field($_POST['size_h']);
			
			$row['width']=sanitize_text_field($_POST['size_w']);
			
			$row['txt_color']=sanitize_text_field($_POST['txt_color']);
			
			$row['bg_color']=sanitize_text_field($_POST['bg_color']);
			
			$row['position']=sanitize_text_field($_POST['badge_pos']);

			$row=json_encode($row);
			
			update_post_meta($post_id,'_phoe_badge',$row);
			
		}
		else if($ch==2)
		{
			
			$row['type']='img';
			
			$row['title']=sanitize_text_field($_POST['post_title']);
			
			$row['img']=sanitize_text_field($_POST['img_path']);
			
			$row['position']=sanitize_text_field($_POST['badge_pos1']);
			
			$row=json_encode($row);
			
			update_post_meta($post_id,'_phoe_badge',$row);
			
		}
		
	}
	
	/* else if(isset($_POST['save']))
	{
			$ch= sanitize_text_field($_POST['badge_type']);
			if($ch==1)
			{
				$row['type']='text';
				$row['title']=sanitize_text_field($_POST['post_title']);
				$row['text']=sanitize_text_field($_POST['b_text']);
				$row['height']=sanitize_text_field($_POST['size_h']);
				$row['width']=sanitize_text_field($_POST['size_w']);
				$row['txt_color']=sanitize_text_field($_POST['txt_color']);
				$row['bg_color']=sanitize_text_field($_POST['bg_color']);
				$row['position']=sanitize_text_field($_POST['badge_pos']);

				$row=json_encode($row);
				update_post_meta($post_id,'_phoe_badge',$row);
			}
	
			else if($ch==2)
			{
					
				$row['type']='img';
				$row['title']=sanitize_text_field($_POST['post_title']);
				$row['img']=sanitize_text_field($_POST['img_path']);
				$row['position']=sanitize_text_field($_POST['badge_pos1']);
				$row=json_encode($row);
				update_post_meta($post_id,'_phoe_badge',$row);		
			}

	} */
	
/*************************** add the information when prodct select the any badge.*************************/	
  
	if(!empty($_POST['badge_name']))
	{
		
		$p_id = $post_id;
		
		//$ch=get_post_meta($p_id,'_phoe_badge_name');
	
		$badge_name=sanitize_text_field($_POST['badge_name']);
		
		update_post_meta($p_id,'_phoe_badge_name',$badge_name);	
		
	}	
}
/******************************

this function will generate custom,
post type.

***********************************/

	function phbg_post_type() {
	
		
		 register_post_type( 'phoe_badge',
        array(
            'labels' => array(
                'name' => 'Badge',
                'singular_name' => 'Badge',
                'add_new' => 'Add New',
                'add_new_item' => 'Add new badge',
                'edit' => 'Edit',
                'edit_item' => 'Edit Badge',
                'new_item' => 'New Badge',
                'view' => 'View',
                'search_items' => 'Search Badge',
                'not_found' => 'No Badge found',
                'not_found_in_trash' => 'No Badges found in Trash',
                'parent' => 'Parent Badge Review'
            ),
 
            'public' => true,
            'menu_position' => 12,
		'menu_icon'  => PHBGPLUG_PATH.'assets/images/phoenixx.png',
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
            'taxonomies' => array( '' ),
            'has_archive' => true
        )
    );
}

/******************

it will show notice if 
woocommerrce is not available

********************/
function phbg_notice()
{
	echo '<div id="message" class="error"><p>sorry you have to install woocommerce in order to use Badges</p></div>';
}
/********************** create form for settings*********************/
function phoe_badge_set()
{

	if(isset($_POST['set_badge']))
	{
		$name="_phoe_badge_onsale";
		
		if(sanitize_text_field($_POST['badge_able'])=='on')
		{
			$value='1';
		}
		else
		{
			$value='2';
		}
		
		$row=get_option('_phoe_badge_onsale');
		
		if($row)
		{	
			update_option( $name, $value);
		}
		else
		{
			add_option( $name, $value);
		}

	}


	if(isset($_POST['set_badge']))
	{
		$name="_phoe_badge_enable";
		
		if(sanitize_text_field($_POST['my_badge_enable'])=='on')
		{
			$value_b='1';
		}
		else
		{
			$value_b='2';
		}
		
		
		$row=get_option('_phoe_badge_enable');
		
		if($row)
		{
			update_option( $name, $value_b);
		}
		else
		{	
			add_option( $name, $value_b);
		}

	}

	$tab = sanitize_text_field( $_GET['tab'] );
    
		$value=get_option('_phoe_badge_onsale');
		$value_b=get_option('_phoe_badge_enable');
	
        ?>
       
  <style>
  
  .upgrade-setting {
    
    padding-left: 11px;
}
  </style>
       
      
       
       <div class="wrap">
			<h2 id="add-new-user"> Settings For Tab <br /></h2>
		
			<div>
			

			<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
			
				<a id="setting" class="nav-tab <?php if($tab == 'general' || $tab == ''){ echo esc_html( "nav-tab-active" ); } ?>" href="?post_type=phoe_badge&page=phoe_badge_set&amp;tab=general">General</a>
				
				<a id="premium" class="nav-tab <?php if($tab == 'premium'){ echo esc_html( "nav-tab-active" ); } ?>" href="?post_type=phoe_badge&page=phoe_badge_set&amp;tab=premium">Premium</a>
									
			</h2>
			</div>
			<?php 
            
            
            $plugin_dir_url =  plugin_dir_url( __FILE__ );
            
            if($tab=='general' || $tab == ''){ ?>
			<form method="post" action="">
		
			<div id="setting_div">
			
			<div class="meta-box-sortables" id="normal-sortables">
				<div class="postbox " id="pho_wcpc_box">
					<h3><span class="upgrade-heading">Upgrade to the PREMIUM VERSION</span></h3>
					<div class="inside">
						<div class="pho_premium_box">

							<div class="column two">
								<!-----<h2>Get access to Pro Features</h2>----->

								<p>Switch to the premium version of Woocommerce Check Pincode/Zipcode for Shipping and COD to get the benefit of all features!</p>

									<div class="pho-upgrade-btn">
										<a href="http://www.phoeniixx.com/product/badge-management-for-woocommerce/" target="_blank"><img src="<?php echo $plugin_dir_url; ?>assets/images/premium-btn.png" /></a>
									</div>
							</div>
						</div>
					</div>
				</div>
			</div>
            
            
<h3>General Setting</h3><br>
	<table class="form-table" style="background-color:#FFFFFF; padding:10px;max-width:1100px;">
		<tbody>
			<tr class="" valign="top">
				<th scope="row" class="titledesc">Enable Badge Management Plugin</th>
				<td class="forminp forminp-checkbox">
					
						<legend class="screen-reader-text"></legend>
						<label for="pzmp_plugin_enable">
							<input name="my_badge_enable" id="pzmp_plugin_enable" <?php  if($value_b!='2') echo'checked=""';?> type="checkbox"> 
						</label> 
					
				</td>
			</tr>
			<tr class="" valign="top">
				<th scope="row" class="titledesc">Hide Default "On Sale" Badge</th>
				<td class="forminp forminp-checkbox">
					
						<legend class="screen-reader-text"></legend>
						<label for="pzmp_plugin_mobile_enable">
							<input name="badge_able" id="pzmp_plugin_mobile_enable";<?php  if($value=='1') { ?> checked="" <?php } ?>  type="checkbox"> 
						</label> 
					
				</td>
			</tr>
		</tbody>
	</table>
	<br>
	<input type="submit" name="set_badge" class="button button-primary" value="Save Changes"><div>
	</form>
<?php }if($tab=='premium'){
		
		require_once('phbg_premium.php');
	}
	
}


/*********************
this is used to generate position 
of badge both in frontend and backend

***********************/

function phbg_set_pos($my_pos)
{
	if($my_pos=='top-right')
	return 'position:absolute; right:0;left:auto;top:0;bottom:auto;';	
	else if($my_pos=='top-left')
	return 'position:absolute; left:0; right:auto;top:0;bottom:auto;';	
	else if($my_pos=='bottom-right')
	return 'position:absolute; left:auto; right:0;bottom:0;top:auto;';	
	else if($my_pos=='bottom-left')
	return 'position:absolute; left:0; right:auto; bottom:0;top:auto;';	
}

?>
