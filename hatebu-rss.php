<?php
/*
Plugin Name: Hatena Bookmark RSS
Version: 1.0
Plugin URI: http://www.app-life.jp/
Description: はてなブックマークのRSSを解析して、HTMLとして入力できるようにする
Author URI: http://www.app-life.jp/
*/

//メニューに追加
add_action('admin_menu','admin_menu_hatebu_rss');
function admin_menu_hatebu_rss(){
	add_options_page('はてなブックマーク','はてなブックマーク',manage_options, __FILE__, 'get_hatebu_option');
}

//プラグインの管理画面
function get_hatebu_option(){
?>
	<div class="wrap">
		<h2>はてなブックマークRSS</h2>
		<form method="post" action="options.php">
			<?php wp_nonce_field('update-options'); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">はてなブックマーク ユーザID</th>
					<td><input type="text" name="hatebu_account" value="<?php echo get_option('hatebu_account'); ?>" /></td>
				</tr>
			</table>
			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="page_options" value="hatebu_account" />
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
	</div>
<?php
}

//投稿画面に追加
add_action('admin_init','hatebu_rss_post');
function hatebu_rss_post(){
	add_meta_box('hatebu_rss_post','はてブRSS 読み込みプラグイン','hatebu_rss_post_box','post','normal','high');
}
function hatebu_rss_post_box(){
	$path = content_url() . '/plugins/hatebu-rss/get_feed.php?id=' . get_option('hatebu_account');
	echo <<<EOM
<input type="button" value="今日のRSSフィードを読み込む" id="get_hatebu_rss_feed" />
<script type="text/javascript">
jQuery(function(){
	jQuery('#get_hatebu_rss_feed').click(function(){
		jQuery.get('{$path}',function(data){
			jQuery('#content').val(data);
			//jQuery('#tinymce').innerHTML(data);
			var ifr = jQuery('#content_ifr').contents().find('#tinymce');
			ifr.html(data);
			//console.log(jQuery('#content_ifr').contents().find('#tinymce'));
		});
	});
});
</script>
EOM;
	echo ' はてなブックマーク アカウント：' . get_option('hatebu_account');
}

//プラグイン向こうにする際の処理
if(function_exists('register_uninstall_hook')){
	register_uninstall_hook(__FILE__,'uninstall_hook_get_hatebu_option');
}
function ininstall_hook_get_hatebu_option(){
	delete_option('key');
}
