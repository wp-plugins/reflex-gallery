<?php
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

if(isset($_POST['galleryId'])) {
	
	$this->reflexdb->deleteGallery(intval($_POST['galleryId']));
		
	?>  
	<div class="updated"><p><strong><?php _e('Gallery has been deleted.', 'reflex-gallery'); ?></strong></p></div>  
	<?php	
}

$galleryResults = $this->reflexdb->getGalleries();

if (isset($_POST['defaultSettings'])) {
	$temp_defaults = get_option('reflex_gallery_options');
	$temp_defaults[1]['thumbnail_width'] = $_POST['default_width'];
	$temp_defaults[1]['thumbnail_height'] = $_POST['default_height'];
	
	update_option('reflex_gallery_options', $temp_defaults);
	?>  
	<div class="updated"><p><strong><?php _e('Gallery options have been updated.', 'reflex-gallery'); ?></strong></p></div>  
	<?php
}
$default_options = get_option('reflex_gallery_options');
?>
<div class='wrap'>
<h2>ReFlex Gallery</h2>
<p><?php _e('This is a listing of all galleries', 'reflex-gallery'); ?></p>
    <table class="widefat post fixed" id="galleryResults" cellspacing="0">
    	<thead>
        <tr>
        	<th><?php _e('Gallery Name', 'reflex-gallery'); ?></th>
            <th><?php _e('Gallery Short Code', 'reflex-gallery'); ?></th>
            <th><?php _e('Description', 'reflex-gallery'); ?></th>
            <th width="136"></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
        	<th><?php _e('Gallery Name', 'reflex-gallery'); ?></th>
            <th><?php _e('Gallery Short Code', 'reflex-gallery'); ?></th>
            <th><?php _e('Description', 'reflex-gallery'); ?></th>
            <th></th>
        </tr>
        </tfoot>
        <tbody>
        	<?php foreach($galleryResults as $gallery) { ?>				
            <tr>
            	<td><?php echo $gallery->name; ?></td>
                <td><input type="text" size="40" value="[ReflexGallery id='<?php echo $gallery->Id; ?>']" /></td>
                <td><?php echo $gallery->description; ?></td>
                <td class="major-publishing-actions">
                <form name="delete_gallery_<?php echo $gallery->Id; ?>" method ="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
                	<input type="hidden" name="galleryId" value="<?php echo $gallery->Id; ?>" />
                    <input type="submit" name="Submit" class="button-primary" value="<?php _e('Delete Gallery', 'reflex-gallery'); ?>" />
                </form>
                </td>
            </tr>
			<?php } ?>
        </tbody>
     </table>
     <br />
     <h3><?php _e('Default Options', 'reflex-gallery'); ?></h3>
     <form name="save_default_settings" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
     <table class="widefat post fixed" cellspacing="0">
     	<thead>
        	<th><?php _e('Attribute', 'reflex-gallery'); ?></th>
            <th><?php _e('Default Value', 'reflex-gallery'); ?></th>
            <th><?php _e('Description', 'reflex-gallery'); ?></th>
        </thead>
        <tfoot>
        	<th><?php _e('Attribute', 'reflex-gallery'); ?></th>
            <th><?php _e('Default Value', 'reflex-gallery'); ?></th>
            <th><?php _e('Description', 'reflex-gallery'); ?></th>
        </tfoot>
        <tbody>
        	<tr>
            	<td><?php _e('Default Thumbnail Width', 'reflex-gallery'); ?></td>
                <td><input name="default_width" id="default_width" value="<?php echo $default_options[1]['thumbnail_width']; ?>" /> px</td>
                <td><?php _e('This is the default width (in pixels) of all of the gallery thumbnail images.<br />(This property can be overwritten when creating individual galleries.)', 'reflex-gallery'); ?></td>
            </tr>
            <tr>
            	<td><?php _e('Default Thumbnail Height', 'reflex-gallery'); ?></td>
                <td><input name="default_height" id="default_height" value="<?php echo $default_options[1]['thumbnail_height']; ?>" /> px</td>
                <td><?php _e('This is the default height (in pixels) of all of the gallery thumbnail images.<br />(This property can be overwritten when creating individual galleries.)', 'reflex-gallery'); ?></td>
            </tr>
            <tr>
            	<td>                
                	<input type="hidden" name="defaultSettings" value="true" />
                    <input type="submit" name="Submit" class="button-primary" value="<?php _e('Save', 'reflex-gallery'); ?>" />                
                </td>
                <td></td>
                <td>
            </tr>
        </tbody>
     </table>
     </form>
     <br /><br />
     <table class="widefat post fixed">
    	<thead>
        <tr>
        	<th><em>Please consider making a donatation for the continued development of this plugin. Thanks.</em></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
        	<th></th>
        </tr>
        </tfoot>
        <tbody>        				
            <tr>
            <td><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=BD7VZR88K9DB4" target="_blank"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" alt="PayPal - The safer, easier way to pay online!"><img alt="" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"></a></td>            
            </tr>
            </tbody>
            </table>
            <br /><br />
     <table class="widefat post fixed">
    	<thead>
        <tr>
        	<th><em>Other plugins by <a href="http://labs.hahncreativegroup.com/" target="_blank">HahnCreativeGroup</a></em></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
        	<th></th>
        </tr>
        </tfoot>
        <tbody>        				
            <tr>
            <td>
            	<ul>
                	<li><a href="http://wordpress-photo-gallery.com/?src=rg" target="_blank">ReFlex Gallery Pro</li>
                    <li><a href="http://labs.hahncreativegroup.com/wordpress-plugins/wp-easy-gallery-pro-simple-wordpress-gallery-plugin/?src=gr" target="_blank">WP Easy Gallery Pro</a></li>
                    <li><a href="http://wordpress.org/extend/plugins/custom-post-donations/" target="_blank">Custom Post Donations</a></li>
                    <li><a href="http://wordpress.org/extend/plugins/wp-translate/" target="_blank">WP Translate</a></li>
                </ul>
            </td>            
            </tr>
            </tbody>
            </table>
</div>