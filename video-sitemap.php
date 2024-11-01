<?php
/*
Plugin Name: Video XML Sitemap Generator
Plugin URI: 
Description: This plugin generates an XML file containg information about the videos embedded in your WP Pages and Posts as per Google XML Schema for the Video Sitemap.
Author: Tradebooster
Version: 1.0.0
Author URI: http://www.tradebooster.com/
*/

/*  Copyright 2013 Tradebooster,com  (email : sy@tradebooster.com)
 
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.
 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to
	Total Internet Solutions Pvt. Ltd.
	309-310 South Ex Tower
	389 Masjid Moth
	Behind South Ex - II Market
	New Delhi - 110049.
*/ 

add_action ('admin_menu', 'video_sitemap_generate_page');
add_action( 'save_post', 'save_sitemap_on_save_post');

function video_sitemap_generate_page () {
	
    	add_options_page (__('Video Sitemap'), __('Video Sitemap'),
        	'manage_options', 'video-sitemap-generate-page', 'video_sitemap_generate');
}

	/**
	 * Checks if a page or post is created then sitemap will be updated automatically.
	 *
	 * @since 3.05b
	 * @access private
	 * @author  tradebooster <http://www.tradebooster.com>
	 * @return bool true if writable
	 */

	function save_sitemap_on_save_post( $post_id ) {

		$post_type = get_post_type( $post_id );

		if ( $post_type == 'page' || $post_type == 'post' )
		{
			
		/**  Varaible for daiymotion  */	
$d="";
/**  Varaible for vimeo  */
$v="";
/**  Varaible for daiymotion  */
$y="";

/**  Cheked if checkbox is checked for dailymotion then assign value for its variable */
if(get_option('dailymotion_video')==1) { $d=1;}

/**  Cheked if checkbox is checked for vimeo then assign value for its variable */
if(get_option('vimeo_video')==1) { $v=1;}

/**  Cheked if checkbox is checked for youtube then assign value for its variable */
if(get_option('youtube_video')==1) { $y=1;}

/**  Call the function for genrating sitemap and pass assign value in it */

if($d!="" || $v!="" || $y!="") {
  video_sitemap_auto_generate($d,$v,$y);
}
			}
	}

	/**
	 * Checks if a file is writable and tries to make it if not.
	 *
	 * @since 3.05b
	 * @access private
	 * @author  tradebooster <http://www.tradebooster.com>
	 * @return bool true if writable
	 */
	function IsVideoSitemapWritable($filename) {
		//can we write?
		
		if(!is_writable($filename)) { 
			//no we can't.
			if(!@chmod($filename, 0666)) {
				$pathtofilename = dirname($filename); 
				//Lets check if parent directory is writable.
				if(!is_writable($pathtofilename)) {
					//it's not writeable too.
					if(!@chmod($pathtoffilename, 0666)) {
						//darn couldn't fix up parent directory this hosting is foobar.
						//Lets error because of the permissions problems.
						return false;
					}
				}
			}
		}
		//we can write, return 1/true/happy dance.
		return true;
	}

/**
	 * Checks if settings is saved then sitemap will generate.
	 *
	 * @since 3.05b
	 * @access private
	 * @author  tradebooster <http://www.tradebooster.com>
	 * @return html data
	 */

function video_sitemap_generate () {

/**  If Save the Settings and atleast one checkbox is checked */
if (isset($_POST ['save']) && (!empty($_POST['dailymotion']) || !empty($_POST['vimeo']) || !empty($_POST['youtube']))) 
{
      /**  If Dailymotion checkbox is checked */
	if (isset($_POST['dailymotion'])) { add_option( 'dailymotion_video', '1', '', 'yes' ); }
	else { delete_option( 'dailymotion_video'); }
    /**  If Vimeo checkbox is checked */
    if (isset($_POST['vimeo'])) { add_option( 'vimeo_video', '1', '', 'yes' ); }
	else { delete_option( 'vimeo_video'); }
	/**  If Youtube checkbox is checked */
	if (isset($_POST['youtube'])){ add_option( 'youtube_video', '1', '', 'yes' ); }
	else { delete_option( 'youtube_video'); }

}
else if (isset($_POST ['save']) && empty($_POST['dailymotion']) && empty($_POST['vimeo']) && empty($_POST['youtube'])) 
{
/**  If no any checkbox is checked */
echo "<script>alert('Please select atleast one video service');return false;</script>"; 
}


/**  If Generating the sitemap and is checked atleast one checkbox  */
if (isset($_POST ['generate']) && ((get_option('dailymotion_video')==1) || (get_option('vimeo_video')==1) || (get_option('youtube_video')==1)))
{
/**  Varaible for daiymotion  */	
$d="";
/**  Varaible for vimeo  */
$v="";
/**  Varaible for daiymotion  */
$y="";

/**  Cheked if checkbox is checked for dailymotion then assign value for its variable */
if(get_option('dailymotion_video')==1) { $d=1;}

/**  Cheked if checkbox is checked for vimeo then assign value for its variable */
if(get_option('vimeo_video')==1) { $v=1;}

/**  Cheked if checkbox is checked for youtube then assign value for its variable */
if(get_option('youtube_video')==1) { $y=1;}

/**  Call the function for genrating sitemap and pass assign value in it */
$st = video_sitemap_loop ($d,$v,$y);
		if (!$st) {
echo '';	
exit();
}

?>

<div class="wrap">
<div style="float:right; margin:10px">
</div>
<h2>Video XML Sitemap Generator</h2>
<?php $sitemapurl =  get_site_url()."/video-sitemap.xml"; ?>
<p>The Video Sitemap has been successfully generated. Open <a target="_blank" href="<?php echo $sitemapurl; ?>">Sitemap XML</a></p>
<p>You can submit your video Sitemap <a href="http://www.google.com/webmasters/tools/" target="_blank">Webmaster Tools</a> directly or<a target="_blank" href="http://www.google.com/webmasters/sitemaps/ping?sitemap=<?php echo $sitemapurl; ?>">Ping  Google</a>.</p>
<p></p>
<?php 
} 
else
{ 

if (isset($_POST ['generate']) && ((get_option('dailymotion_video')!=1) || (get_option('vimeo_video')!=1) || (get_option('youtube_video')!=1)))
{
echo "<script>alert('Please select atleast one video service from the services listed above');return false;</script>"; 
}
?>

<div class="wrap">
<h2>Video XML Sitemap Generator by <a href="http://www.tradebooster.com/">Tradebooster</a></h2>
<h3>Plugin Settings</h3> 
  <p>This plugin allows you to create a video sitemap for your videos hosted on Youtube, Vimeo or Dailymotion. <br />
  Select any or all of the services that you would like to include from the list below.</p>

  <form id="options_form" method="post" action="" >
		
	<input type="checkbox" id="dailymotion" name="dailymotion" value="1" <?php if(get_option('dailymotion_video')!=0){ ?>  checked="checked" <?php }?> />
    <label for="dailymotion">Dailymotion</label></br>
	
	<input type="checkbox" id="vimeo" name="vimeo" value="1" <?php if(get_option('vimeo_video')!=0){ ?> checked="checked" <?php }?>/>
    <label for="vimeo">Vimeo</label></br>
	
	<input type="checkbox" id="youtube" name="youtube" value="1" <?php if(get_option('youtube_video')!=0){ ?>  checked="checked" <?php }?>/>
    <label for="youtube">Youtube</label>

    <div class="submit">
      <input type="submit" name="save" id="sb_submit" value="Save Settings" />
    </div>
  </form>
  <p></p>
  <div style="border:1px solid #dfdfdf;"></div>
  <h3>Create your Video Sitemap</h3> 
  <p>
   <form id="options_form" method="post" action="" >
    <input type="submit" name="generate" id="gn_submit" value="Generate Video Sitemap" />
    </div>
  </form>
  </p>
</div>
<?php	}
}


/**
	 * It genrates sitemap for dailymotion, vimeo & youtube videos.
	 *
	 * @since 3.05b
	 * @access private
	 * @author  tradebooster <http://www.tradebooster.com>
	 * @return outputted xml data
	 */
function video_sitemap_loop ($dailymtn,$vimeo,$ytube) {
	global $wpdb;
	/**  creating condition according to checked  checkbox value  */
    $condition="(";
	if ($dailymtn!="") { $condition.=" post_content LIKE '%dailymotion.com%' ||"; }
	if ($vimeo!="")	{ $condition.=" post_content LIKE '%vimeo.com%' ||";  }
	if ($ytube!="")	{ $condition.=" post_content LIKE '%youtube.com%' ||"; }
	$condition=substr($condition, 0, -2);	
	$condition.=")";
	
	/**  Get all the result for video */
	$posts = $wpdb->get_results ("SELECT id, post_title, post_content, post_date_gmt, post_excerpt 
							FROM $wpdb->posts 
							WHERE post_status = 'publish' 
							AND (post_type = 'post' OR post_type = 'page')
							AND $condition 
							ORDER BY post_date DESC");
      
	  /**  If No any video found */
	if (empty ($posts)) {
	
	echo "Sitemap has not been genrated because No Videos Found!";
		return false;

	} else {
         /**  XML Code start here if Video is found */
		$xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";       		     	
	    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">' . "\n";	
		
		/**  Code for dailymotion videos sitemap  */
		
                       foreach ($posts as $post) {
					   $a=0;
			if (preg_match_all ("/http:\/\/www.dailymotion.com\/embed\/video\/([a-z0-9\-]*)/", 
				$post->post_content, $matches, PREG_SET_ORDER)) {
                    $excerpt = ($post->post_excerpt != "") ? $post->post_excerpt : $post->post_title ; 
                    $permalink = video_EscapeXMLEntities(get_permalink($post->id)); 
					$publish_date=date (DATE_W3C, strtotime ($post->post_date_gmt));
					
					
                foreach ($matches as $match) {
						$id = $match [1];
						
						
						if ($id == '') $id = $match [2];
						if ($id == '') $id = $match [3];
						
						$fixa =  $a++==0?'':' [Video '. $a .'] ';
						
					$dynamic_data='';	
	                $dynamic_data=dailymotion_data($id);
					$xml .= "\n <url>\n";
					$xml .= " <loc>$permalink</loc>\n";
					$xml .= " <video:video>\n";
					$xml .= "  <video:player_loc allow_embed=\"yes\" autoplay=\"autoplay=1\">http://www.dailymotion.com/embed/video/$id</video:player_loc>\n";
					
					$xml .= "  <video:title>" . htmlspecialchars($post->post_title) . $fixa . "</video:title>\n";
                    
					$xml .= "  <video:description>" . $fixa . htmlspecialchars($excerpt) . "</video:description>\n";	
                     $xml .= "  <video:duration>" . htmlspecialchars($dynamic_data['duration']) . "</video:duration>\n";					
                 $xml .= " <video:thumbnail_loc>". htmlspecialchars($dynamic_data['thumbnail_loc']) ."</video:thumbnail_loc>\n";
                     
                     $xml .= "  <video:publication_date>" . $publish_date . "</video:publication_date>\n";
					$xml .= " </video:video>\n </url>";
				}
			}
		}

		
		/**  Code for vimeo videos sitemap  */
		
		foreach ($posts as $post) {
		$b=0;
			if (preg_match_all ("/http:\/\/player.vimeo.com\/video\/([a-z0-9\-]*)/", 
				$post->post_content, $matches, PREG_SET_ORDER)) {
                    $excerpt = ($post->post_excerpt != "") ? $post->post_excerpt : $post->post_title ; 
                    $permalink = video_EscapeXMLEntities(get_permalink($post->id)); 
					$publish_date=date (DATE_W3C, strtotime ($post->post_date_gmt));
                    foreach ($matches as $match) {
						$id = $match [1];
						
						$fixb =  $b++==0?'':' [Video '. $b .'] ';
						
						if ($id == '') $id = $match [2];
						if ($id == '') $id = $match [3];
					$dynamic_data='';	
	                $dynamic_data=vimeo_data($id);
					$xml .= "\n <url>\n";
					$xml .= " <loc>$permalink</loc>\n";
					$xml .= " <video:video>\n";
					$xml .= "  <video:player_loc allow_embed=\"yes\" autoplay=\"autoplay=1\">http://player.vimeo.com/video/$id</video:player_loc>\n";
					
					$xml .= "  <video:title>" . htmlspecialchars($post->post_title) . $fixb . "</video:title>\n";
                    
					$xml .= "  <video:description>" . $fixb . htmlspecialchars($excerpt) . "</video:description>\n";	

					 $xml .= "  <video:duration>" . htmlspecialchars($dynamic_data['duration']) . "</video:duration>\n";					
                 $xml .= " <video:thumbnail_loc>". htmlspecialchars($dynamic_data['thumbnail_loc']) ."</video:thumbnail_loc>\n";
                     
                     $xml .= "  <video:publication_date>" . $publish_date . "</video:publication_date>\n";
					$xml .= " </video:video>\n </url>";
				}
			}
		}
		
				
		
		/**  Code for youtube videos sitemap  */
		
		        $videos = array();
    
        foreach ($posts as $post) {
            $c = 0;
            if (preg_match_all ("/youtube.com\/(v\/|watch\?v=|embed\/)([a-zA-Z0-9\-_]*)/", $post->post_content, $matches, PREG_SET_ORDER)) {

                    $excerpt = ($post->post_excerpt != "") ? $post->post_excerpt : $post->post_title ; 
                    $permalink = video_EscapeXMLEntities(get_permalink($post->id)); 

                foreach ($matches as $match) {
                                    
                        $id = $match [2];
                        $fixc =  $c++==0?'':' [Video '. $c .'] ';
                        
                        if (in_array($id, $videos))
                            continue;
                            
                        array_push($videos, $id);
                        
                        $xml .= "\n <url>\n";
                        $xml .= " <loc>$permalink</loc>\n";
                        $xml .= " <video:video>\n";
                        $xml .= "  <video:player_loc allow_embed=\"yes\" autoplay=\"autoplay=1\">http://www.youtube.com/v/$id</video:player_loc>\n";
                       
                        $xml .= "  <video:title>" . htmlspecialchars($post->post_title) . $fixc . "</video:title>\n";
                        $xml .= "  <video:description>" . $fixc . htmlspecialchars($excerpt) . "</video:description>\n";
    
                        $xml .= "  <video:duration>".youtube_duration ($id)."</video:duration>\n";
                         
  					    $xml .= "  <video:thumbnail_loc>http://i.ytimg.com/vi/$id/hqdefault.jpg</video:thumbnail_loc>\n";  

                    $xml .= "  <video:publication_date>".date (DATE_W3C, strtotime ($post->post_date_gmt))."</video:publication_date>\n";
    
         

                    $xml .= " </video:video>\n </url>";
                }
            }
        }
		
		$xml .= "\n</urlset>";
	}

	/**  Full path of XML file on root folder  */
	$video_sitemap_url = $_SERVER['DOCUMENT_ROOT'].'/video-sitemap.xml'; 
	
	/**  Check if file is writable  */
	if (IsVideoSitemapWritable($video_sitemap_url)) 
	{    
	    /**  Check if file is writable then save all xml data in file  */
		if (file_put_contents ($video_sitemap_url, $xml)) 
		{
			return true;
		}
	} 
	/**  if file is not writable then will be echo all XML data, that can be copied manually   */
echo '<br /><div class="wrap"><h2>Error Writing XML File</h2><p>The sitemap has been generated successfully, but the plugin was unable to save the xml file in your root WordPress folder <strong>' . $_SERVER["DOCUMENT_ROOT"] . '</strong> probably because the file is write-protected <a href="http://codex.wordpress.org/Changing_File_Permissions" target="_blank">permissions writing </a>.</p><p> You can manually copy and paste the following text into a file and save it as video-sitemap.xml in your root WordPress folder. </p><br /><textarea rows="30" cols="150" style="font-family:verdana; font-size:11px;color:#666;background-color:#f9f9f9;padding:5px;margin:5px">' . $xml . '</textarea></div>';	
	exit();
}

/**
	 * It returns dailymotion data using json API. 
	 *
	 * @since 3.05b
	 * @access private
	 * @author  tradebooster <http://www.tradebooster.com>
	 * @return  result in array 
	 */
	function dailymotion_data( $video_id ) {
    
	 /**  API url for dailymotion videos  */
    $api_url = "https://api.dailymotion.com/video/$video_id?fields=title,duration,description,thumbnail_360_url";
  
	$request = wp_remote_get( $api_url );
	print_r($request);
		$data = json_decode( $request['body'] );
        /**  assign value to variable  */
        $title= $data->title;
        $duration = $data->duration;
        $description= $data->description;
		$thumbnail_loc= $data->thumbnail_360_url;
		return array(
				'title' => $title,
				'duration' => $duration,
				'description' => $description,
				'thumbnail_loc' => $thumbnail_loc,
				);
	}
	
	/**
	 * It returns Vimeo data using  json API. 
	 *
	 * @since 3.05b
	 * @access private
	 * @author  tradebooster <http://www.tradebooster.com>
	 * @return  result in array
	 */
function vimeo_data( $video_id ) {
    /**  API url for vimeo videos  */
    $api_url ="http://vimeo.com/api/v2/video/$video_id.json";
	$request = wp_remote_get( $api_url );
	
	$vim_data = json_decode( $request['body'] );
        
		/**  assign value to variable  */
		$data=$vim_data[0];
        $title= $data->title;
        $duration = $data->duration;
        $description= $data->description;
		$thumbnail_loc= $data->thumbnail_large;
        return array(
				'title' => $title,
				'duration' => $duration,
				'description' => $description,
				'thumbnail_loc' => $thumbnail_loc,
				);
	}	
	
	/**
	 * It returns Youtube data using json API.
	 *
	 * @since 3.05b
	 * @access private
	 * @author  tradebooster <http://www.tradebooster.com>
	 * @return  int value
	 */
	function youtube_duration ($id) {
    try {
        $ch = curl_init ();
        curl_setopt ($ch, CURLOPT_URL, "http://gdata.youtube.com/feeds/api/videos/$id");
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec ($ch);
        curl_close ($ch);

        preg_match ("/duration=['\"]([0-9]*)['\"]/", $data, $match);
        return $match [1];

    } catch (Exception $e) {
        # returning 0 if the YouTube API fails for some reason.
        return '0';
    }
}

/**
	 * Checks if permalink contain the tag, it replace with html code.
	 *
	 * @since 3.05b
	 * @access private
	 * @author  tradebooster <http://www.tradebooster.com>
	 * @return result
	 */
function video_EscapeXMLEntities($xml) {
    return str_replace(array('&', '<', '>', '\'', '"'), array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'), $xml);
}

/**
	 * If plugins will deactivate then options will be removed from database table.
	 *
	 * @since 3.05b
	 * @access private
	 * @author  tradebooster <http://www.tradebooster.com>
	 * @return Nothing
	 */
function prefix_on_deactivate()
 {
	delete_option( 'dailymotion_video'); 
    delete_option( 'vimeo_video'); 
    delete_option( 'youtube_video');
}

/**
	 * Auto Generate the Video Sitemap if any post created containing Videos. 
	 *
	 * @since 3.05b
	 * @access private
	 * @author  tradebooster <http://www.tradebooster.com>
	 * @return outputted xml data
	 */
function video_sitemap_auto_generate($dailymtn,$vimeo,$ytube) {
	global $wpdb;
	/**  creating condition according to checked  checkbox value  */
    $condition="(";
	if ($dailymtn!="") { $condition.=" post_content LIKE '%dailymotion.com%' ||"; }
	if ($vimeo!="")	{ $condition.=" post_content LIKE '%vimeo.com%' ||";  }
	if ($ytube!="")	{ $condition.=" post_content LIKE '%youtube.com%' ||"; }
	$condition=substr($condition, 0, -2);	
	$condition.=")";
	
	/**  Get all the result for video */
	$posts = $wpdb->get_results ("SELECT id, post_title, post_content, post_date_gmt, post_excerpt 
							FROM $wpdb->posts 
							WHERE post_status = 'publish' 
							AND (post_type = 'post' OR post_type = 'page')
							AND $condition 
							ORDER BY post_date DESC");
      
	  /**  If No any video found */
	if (!empty ($posts)) 
	{
         /**  XML Code start here if Video is found */
		$xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";       		     	
	    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">' . "\n";	
		
		/**  Code for dailymotion videos sitemap  */
		
                       foreach ($posts as $post) {
					   $a=0;
			if (preg_match_all ("/http:\/\/www.dailymotion.com\/embed\/video\/([a-z0-9\-]*)/", 
				$post->post_content, $matches, PREG_SET_ORDER)) {
                    $excerpt = ($post->post_excerpt != "") ? $post->post_excerpt : $post->post_title ; 
                    $permalink = video_EscapeXMLEntities(get_permalink($post->id)); 
					$publish_date=date (DATE_W3C, strtotime ($post->post_date_gmt));
					
					
                foreach ($matches as $match) {
						$id = $match [1];
						
						
						if ($id == '') $id = $match [2];
						if ($id == '') $id = $match [3];
						
						$fixa =  $a++==0?'':' [Video '. $a .'] ';
						
					$dynamic_data='';	
	                $dynamic_data=dailymotion_data($id);
					$xml .= "\n <url>\n";
					$xml .= " <loc>$permalink</loc>\n";
					$xml .= " <video:video>\n";
					$xml .= "  <video:player_loc allow_embed=\"yes\" autoplay=\"autoplay=1\">http://www.dailymotion.com/embed/video/$id</video:player_loc>\n";
					
					$xml .= "  <video:title>" . htmlspecialchars($post->post_title) . $fixa . "</video:title>\n";
                    
					$xml .= "  <video:description>" . $fixa . htmlspecialchars($excerpt) . "</video:description>\n";	
                     $xml .= "  <video:duration>" . htmlspecialchars($dynamic_data['duration']) . "</video:duration>\n";					
                 $xml .= " <video:thumbnail_loc>". htmlspecialchars($dynamic_data['thumbnail_loc']) ."</video:thumbnail_loc>\n";
                     
                     $xml .= "  <video:publication_date>" . $publish_date . "</video:publication_date>\n";
					$xml .= " </video:video>\n </url>";
				}
			}
		}

		
		/**  Code for vimeo videos sitemap  */
		
		foreach ($posts as $post) {
		$b=0;
			if (preg_match_all ("/http:\/\/player.vimeo.com\/video\/([a-z0-9\-]*)/", 
				$post->post_content, $matches, PREG_SET_ORDER)) {
                    $excerpt = ($post->post_excerpt != "") ? $post->post_excerpt : $post->post_title ; 
                    $permalink = video_EscapeXMLEntities(get_permalink($post->id)); 
					$publish_date=date (DATE_W3C, strtotime ($post->post_date_gmt));
                    foreach ($matches as $match) {
						$id = $match [1];
						
						$fixb =  $b++==0?'':' [Video '. $b .'] ';
						
						if ($id == '') $id = $match [2];
						if ($id == '') $id = $match [3];
					$dynamic_data='';	
	                $dynamic_data=vimeo_data($id);
					$xml .= "\n <url>\n";
					$xml .= " <loc>$permalink</loc>\n";
					$xml .= " <video:video>\n";
					$xml .= "  <video:player_loc allow_embed=\"yes\" autoplay=\"autoplay=1\">http://player.vimeo.com/video/$id</video:player_loc>\n";
					
					$xml .= "  <video:title>" . htmlspecialchars($post->post_title) . $fixb . "</video:title>\n";
                    
					$xml .= "  <video:description>" . $fixb . htmlspecialchars($excerpt) . "</video:description>\n";	

					 $xml .= "  <video:duration>" . htmlspecialchars($dynamic_data['duration']) . "</video:duration>\n";					
                 $xml .= " <video:thumbnail_loc>". htmlspecialchars($dynamic_data['thumbnail_loc']) ."</video:thumbnail_loc>\n";
                     
                     $xml .= "  <video:publication_date>" . $publish_date . "</video:publication_date>\n";
					$xml .= " </video:video>\n </url>";
				}
			}
		}
		
				
		
		/**  Code for youtube videos sitemap  */
		
		        $videos = array();
    
        foreach ($posts as $post) {
            $c = 0;
            if (preg_match_all ("/youtube.com\/(v\/|watch\?v=|embed\/)([a-zA-Z0-9\-_]*)/", $post->post_content, $matches, PREG_SET_ORDER)) {

                    $excerpt = ($post->post_excerpt != "") ? $post->post_excerpt : $post->post_title ; 
                    $permalink = video_EscapeXMLEntities(get_permalink($post->id)); 

                foreach ($matches as $match) {
                                    
                        $id = $match [2];
                        $fixc =  $c++==0?'':' [Video '. $c .'] ';
                        
                        if (in_array($id, $videos))
                            continue;
                            
                        array_push($videos, $id);
                        
                        $xml .= "\n <url>\n";
                        $xml .= " <loc>$permalink</loc>\n";
                        $xml .= " <video:video>\n";
                        $xml .= "  <video:player_loc allow_embed=\"yes\" autoplay=\"autoplay=1\">http://www.youtube.com/v/$id</video:player_loc>\n";
                       
                        $xml .= "  <video:title>" . htmlspecialchars($post->post_title) . $fixc . "</video:title>\n";
                        $xml .= "  <video:description>" . $fixc . htmlspecialchars($excerpt) . "</video:description>\n";
    
                        $xml .= "  <video:duration>".youtube_duration ($id)."</video:duration>\n";
                         
  					    $xml .= "  <video:thumbnail_loc>http://i.ytimg.com/vi/$id/hqdefault.jpg</video:thumbnail_loc>\n";  

                    $xml .= "  <video:publication_date>".date (DATE_W3C, strtotime ($post->post_date_gmt))."</video:publication_date>\n";
    
         

                    $xml .= " </video:video>\n </url>";
                }
            }
        }
		
		$xml .= "\n</urlset>";
	}

	/**  Full path of XML file on root folder  */
	$video_sitemap_url = $_SERVER['DOCUMENT_ROOT'].'/video-sitemap.xml'; 
	
	/**  Check if file is writable  */
	if (IsVideoSitemapWritable($video_sitemap_url)) 
	{    
	    /**  Check if file is writable then save all xml data in file  */
		if (file_put_contents ($video_sitemap_url, $xml)) 
		{
			return true;
		}
	} 

}


register_deactivation_hook(__FILE__, 'prefix_on_deactivate');
?>