<?php
/* 
Plugin Name: MyUpLoad
Description: just a pluin help ...
Author: Johnson
Version: 1.0 
Author URI: http://weibo.com/lijohnson 
*/

if(!class_exists("MyUpLoad")){
	class MyUpload{
		private static $optionKey = 'MyUpLoad';
		public function upLoadDir( $param ){
			$opt = get_option(self::$optionKey);
			if( !$opt || !isset($opt['basedir']) || !isset($opt['baseurl'])){
				return $param;
			}
			$param['basedir'] = $opt['basedir'];
			$param['path'] = $opt['basedir'] . $param['subdir'];
			$param['baseurl'] = $opt['baseurl'] ;
			$param['url'] = $opt['baseurl'] . $param['subdir'];;
			return $param;
		}

		public function addOptionPage(){
			add_options_page('MyUpload', 'MyUpload', 9,__file__ , array(&$this,"optionPage"));
		}

		public function optionPage(){
			if( $_POST['update'] ){
				$opt['basedir'] = $_POST['basedir'];
				$opt['baseurl'] = $_POST['baseurl'];
				if(update_option(self::$optionKey,$opt)){
					echo "<div class=updated ><p>更新成功</p></div>";
				}
			}
			if( $_POST['reset'] ){
				if(delete_option(self::$optionKey,$opt)){
					echo "<div class=updated ><p>恢复成功</p></div>";
				}
			}
			$opt = get_option(self::$optionKey);

			if( !$opt ){$opt = $opt = wp_upload_dir();}

			echo "<h3>设置上传目录&访问路径</h3>"; 
			echo "<form method=post >";
			echo "<label>basedir <input name='basedir' value='$opt[basedir]' required type=text style='width: 600px;'/></label><br>";
			echo "<label>baseurl <input name='baseurl' value='$opt[baseurl]' required type=url style='width: 600px;'/></label><br>";
			echo "<input class='button button-primary' type=submit name=update value=update /></form>";
			echo "<hr><form method=post ><input class='button button-primary' type=submit name=reset value=reset /></form>";
			?>
			<br>
			<br>
			<div class=postbox style='padding-left: 5px;'>
			<h4>SAE Storage设置注意 </h4>
			<p>建一个Domain(<a style="color:red">abs</a>),则basedir可填 saestor://<a style="color:red">abs</a>/<a style="color:blue">uploadPath</a></p>
			<p>相应的baseurl可填http://<b>appname</b>-<a style="color:red">abs</a>.stor.sinaapp.com/<a style="color:blue">uploadPath</a></p>
			<hr>
			<p>最后修改一下 <b>wp_mkdir_p()</b>(wp-includes\functions.php 1334行)因为sae storage不存在创建目录为玩意<br>
<pre>
//添加三行代码
if ( strpos($target, 'saestor') == 0 ) {
	return true;
}
</pre>		<img src=http://ww2.sinaimg.cn/large/5e22416bgw1e9eqrfb2mgj20h904l3yt.png />
			</p>
			</div>
			<?php
		}
		
	}
}
$myUpload = new MyUpload();
add_filter("upload_dir", array(&$myUpload , 'upLoadDir') );
add_action('admin_menu', array(&$myUpload,"addOptionPage"));
//add_filter("wp_handle_upload_prefilter", array(&$myUpload , 'upLoadDir') );
