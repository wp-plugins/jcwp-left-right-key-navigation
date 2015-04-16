<?php
  /*
    Plugin Name: jcwp left right key navigation
    Plugin URI: http://jaspreetchahal.org
    Description: This plugin enables left and right key post navigation.
    Author: jaschahal
    Version: 1.4
    Author URI: http://jaspreetchahal.org
    License: GPLv2 or later
    */

    /*
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    */
    
    // if not an admin just block access
    if(preg_match('/admin\.php/',$_SERVER['REQUEST_URI']) && is_admin() == false) {
        return false;
    }
    
    register_activation_hook(__FILE__,'jcorglrkn_activate');
    function jcorglrkn_activate() {
            add_option('jcorglrkn_plugin','enable');
            add_option('jcorglrkn_theme','gray');
            add_option('jcorglrkn_next_post_label','Next');
            add_option('jcorglrkn_previous_post_label','Previous');
            add_option('jcorglrkn_posts_same_category','disable');
            add_option('jcorglrkn_exclude_categories',"");
            add_option('jcorglrkn_minimum_screen_width',"1024");
            add_option('jcorglrkn_linkback',"no");
    }
    
    add_action("admin_menu","jcorglrkn_menu");
    function jcorglrkn_menu() {
        add_options_page('JCWP Left Right Key', 'JCWP Left Right Key', 'manage_options', 'jcorglrkn-plugin', 'jcorglrkn_plugin_options');
    }
    add_action('admin_init','jcorglrkn_regsettings');
    function jcorglrkn_regsettings() {        
		add_option("jcorglrkn_linkback_text","");
        register_setting("jcorglrkn-setting","jcorglrkn_plugin");
        register_setting("jcorglrkn-setting","jcorglrkn_theme");
        register_setting("jcorglrkn-setting","jcorglrkn_next_post_label");
        register_setting("jcorglrkn-setting","jcorglrkn_previous_post_label");
        register_setting("jcorglrkn-setting","jcorglrkn_posts_same_category");
        register_setting("jcorglrkn-setting","jcorglrkn_exclude_categories");
        register_setting("jcorglrkn-setting","jcorglrkn_minimum_screen_width");
        register_setting("jcorglrkn-setting","jcorglrkn_linkback");
    }   
    
    add_action('wp_head','jcorglrkn_inclscript',200);
    function jcorglrkn_inclscript() {
        if((get_option("jcorglrkn_plugin") == "enable")) {
        wp_enqueue_style('jcorglrkn_css',plugin_dir_url(__FILE__)."/jcLeftRightKeyNav.min.css");
        wp_enqueue_script('jcorglrkn_script',plugin_dir_url(__FILE__)."/jcSimpleLeftRightKeyNav.min.js",array('jquery'));
        $next_url = $next_url_title = "";
        $previous_url = $previous_url_title = "";
        if(is_single()) {

            $previous_url = get_adjacent_post( (get_option("jcorglrkn_posts_same_category") == "enable"));
            $next_url = get_adjacent_post( (get_option("jcorglrkn_posts_same_category") == "enable"),'',false);
            if(is_object($previous_url)) {
                $previous_url_title = $previous_url->post_title;
                $previous_url = get_permalink($previous_url->ID);
            }
            if(is_object($next_url)) {
                $next_url_title = $next_url->post_title;
                $next_url = get_permalink($next_url->ID);
            }
            if($next_url || $previous_url) {

        ?> 
            <script type="text/javascript">

         jQuery(document).ready(function(){
             if(jQuery(document).width() > <?php echo intval(get_option("jcorglrkn_minimum_screen_width")?intval(get_option("jcorglrkn_minimum_screen_width")):0)?>) {
                 jQuery().jcNextPrev({nextLink:"<?php echo $next_url?>",
                     nextLinkText:'<?php echo str_replace("'","\'",$next_url_title)?>',
                     prevLink:'<?php echo $previous_url?>',
                     prevLinkText:'<?php echo str_replace("'","\'",$previous_url_title)?>',
                     nextText:'<?php echo str_replace("'","\'",get_option("jcorglrkn_next_post_label"))?>',
                     previousText:'<?php echo str_replace("'","\'",get_option("jcorglrkn_previous_post_label"))?>'

                 });
             }
         });
         </script>
         
            <?php

            }
        }
        }
    }

    add_action('wp_footer','jcorglrkn_footer',200);
    function jcorglrkn_footer() {
        if(get_option('jcorglrkn_linkback') =="Yes") {
            $link_text = array("Key navigation plugin by Jaspreet Chahal","Left Right key navigation by Jaspreet Chahal","Key navigation plugin by JaspreetChahal.org","Left Right key navigation by JaspreetChahal","WordPress Left Right key navigation plugin","Left Right key navigation plugin for Wordpress","Navigation plugin by http://jaspreetchahal.org","Easy navigation plugin by http://jaspreetchahal.org","Easy Wordpress navigation plugin by http://jaspreetchahal.org","Left Right key navigation plugin by Jaspreet Chahal","Left Right key navigation plugin by JaspreetChahal.org","Wordpress Left Right key navigation by Jaspreet Chahal","Wordpress Left Right key navigation by JaspreetChahal.org","Key navigation plugin by Jaspreet Chahal","Wordpress Key navigation plugin by Jaspreet Chahal","Key navigation plugin by JaspreetChahal.org","Wordpress Key navigation plugin by JaspreetChahal.org","Key navigation by JaspreetChahal.org","Wordpress Key navigation by JaspreetChahal.org","Key Navigation with title preview plugin by Jaspreet Chahal","http//jaspreetchahal.org","Key Navigation with title preview plugin by JaspreetChahal.org");
                if(get_option("jcorglrkn_linkback_text") === FALSE || get_option("jcorglrkn_linkback_text") == "") {
                    add_option("jcorglrkn_linkback_text","");
                    update_option("jcorglrkn_linkback_text",$link_text[rand(0,count($link_text)-1)]);
                }
                echo '<a style="margin-left:45%;color:transparent;cursor:default;font-size:0.01em !important;" href="http://jaspreetchahal.org">'.get_option("jcorglrkn_linkback_text").'</a>';
        }
    }
    
    function jcorglrkn_plugin_options() {
        jcorglrknDonationDetail();           
        ?> 
        <style type="text/css">
        .jcorgbsuccess, .jcorgberror {   border: 1px solid #ccc; margin:0px; padding:15px 10px 15px 50px; font-size:12px;}
        .jcorgbsuccess {color: #FFF;background: green; border: 1px solid  #FEE7D8;}
        .jcorgberror {color: #B70000;border: 1px solid  #FEE7D8;}
        .jcorgb-errors-title {font-size:12px;color:black;font-weight:bold;}
        .jcorgb-errors { border: #FFD7C4 1px solid;padding:5px; background: #FFF1EA;}
        .jcorgb-errors ul {list-style:none; color:black; font-size:12px;margin-left:10px;}
        .jcorgb-errors ul li {list-style:circle;line-height:150%;/*background: url(/images/icons/star_red.png) no-repeat left;*/font-size:11px;margin-left:10px; margin-top:5px;font-weight:normal;padding-left:15px}
        td {font-weight: normal;}
        </style><br>
        <div class="wrap" style="float: left;" >
            <?php             
            
            screen_icon('tools');?>
            <h2>JaspreetChahal's left right key navigation settings</h2>
            <?php 
                $errors = get_settings_errors("",true);
                $errmsgs = array();
                $msgs = "";
                if(count($errors) >0)
                foreach ($errors as $error) {
                    if($error["type"] == "error")
                        $errmsgs[] = $error["message"];
                    else if($error["type"] == "updated")
                        $msgs = $error["message"];
                }

                echo jcorglrknMakeErrorsHtml($errmsgs,'warning1');
                if(strlen($msgs) > 0) {
                    echo "<div class='jcorgbsuccess' style='width:90%'>$msgs</div>";
                }

            ?><br><br>
            <form action="options.php" method="post" id="jcorgbotinfo_settings_form">
            <?php settings_fields("jcorglrkn-setting");?>
            <table class="widefat" style="width: 700px;" cellpadding="7">
                 <tr valign="top">
                    <th scope="row">Enable Left Right Post Navigation</th>
                    <td><input type="radio" name="jcorglrkn_plugin" <?php if(get_option('jcorglrkn_plugin') == "enable") echo "checked='checked'";?>
                            value="enable" 
                            /> Yes
                            <input type="radio" name="jcorglrkn_plugin" <?php if(get_option('jcorglrkn_plugin') == "disable" || get_option('jcorglrkn_plugin') == "") echo "checked='checked'";?>
                            value="disable" 
                            /> No 
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Create Next/Previous link from same category</th>
                    <td><input type="radio" name="jcorglrkn_posts_same_category" <?php if(get_option('jcorglrkn_posts_same_category') == "enable") echo "checked='checked'";?>
                            value="enable" 
                            /> Yes
                            <input type="radio" name="jcorglrkn_posts_same_category" <?php if(get_option('jcorglrkn_posts_same_category') == "disable" || get_option('jcorglrkn_posts_same_category') == "") echo "checked='checked'";?>
                            value="disable" 
                            /> No 
                    </td>
                </tr>
                <!--<tr valign="top">
                    <th scope="row">Exclude these categories for post link generation</th>
                    <td>
                        <select name="jcorglrkn_exclude_categories">
                            <option value="grey" <?php if(get_option('jcorglrkn_exclude_categories') == "grey"){  _e('selected');}?> >Grey</option>
                        </select>
                </tr>-->
                <tr valign="top">
                    <th scope="row">Theme</th>
                    <td>
                    <select name="jcorglrkn_theme">
                        <option value="grey" <?php if(get_option('jcorglrkn_theme') == "grey"){  _e('selected');}?> >Grey</option>
                    </select>
               </tr>
                <tr valign="top">
                    <th width="25%" scope="row">Next post label</th>
                    <td><input type="text" name="jcorglrkn_next_post_label"
                               value="<?php echo get_option('jcorglrkn_next_post_label'); ?>"  style="padding:5px" size="40" maxlength="12"/></td>
                </tr>
                <tr valign="top">
                    <th width="25%" scope="row">Previous post label</th>
                    <td><input type="text" name="jcorglrkn_previous_post_label"
                               value="<?php echo get_option('jcorglrkn_previous_post_label'); ?>"  style="padding:5px" size="40" maxlength="12"/></td>
                </tr>
                <tr valign="top">
                    <th width="25%" scope="row">Minimum screen resolution for links to be displayed</th>
                    <td><input type="text" name="jcorglrkn_minimum_screen_width"
                               value="<?php echo get_option('jcorglrkn_minimum_screen_width'); ?>"  style="padding:5px" size="40" maxlength="12"/> Do no add 'px'</td>
                </tr>
               <tr valign="top">
                    <th scope="row">Include 'Post Navigation powered by' link</th>
                    <td><input type="checkbox" name="jcorglrkn_linkback"
                            value="Yes" <?php if(get_option('jcorglrkn_linkback') =="Yes") echo "checked='checked'";?> /> <br>
                            <strong>An invisible link will be placed in the footer which points to author's website (http://jaspreetchahal.org). This is one way to support me.</strong></td>
                </tr> 
        </table>
        <p class="submit">
            <input type="submit" class="button-primary"
                value="Save Changes" />
        </p>          
            </form>
        </div>
        <?php     
        echo "<div style='float:left;margin-left:20px;margin-top:75px'>".jcorglrknfeeds()."</div>";
    }
    
    function jcorglrknDonationDetail() {
        ?>    
        <style type="text/css"> .jcorgcr_donation_uses li {float:left; margin-left:20px;font-weight: bold;} </style> 
        <div style="padding: 10px; background: #f1f1f1;border:1px #EEE solid; border-radius:15px;width:98%"> 
        <h2>If you like this Plugin, please consider donating a small amount.</h2> 
        You can choose your own amount. Developing this awesome plugin took a lot of effort and time; days and weeks of continuous voluntary unpaid work. 
        If you like this plugin or if you are using it for commercial websites, please consider a donation to the author to 
        help support future updates and development. 
        <div class="jcorgcr_donation_uses"> 
        <span style="font-weight:bold">Main uses of Donations</span><ol ><li>Web Hosting Fees</li><li>Cable Internet Fees</li><li>Time/Value Reimbursement</li><li>Motivation for Continuous Improvements</li></ol> </div> <br class="clear"> <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=MHMQ6E37TYW3N"><img src="https://www.paypalobjects.com/en_AU/i/btn/btn_donateCC_LG.gif" /></a> <br><br><strong>For help please visit </strong><br> 
        <a href="http://jaspreetchahal.org/wordpress-left-right-key-navigation-plugin">http://jaspreetchahal.org/wordpress-left-right-key-navigation-plugin</a> <br><strong> </div>
        
        <?php
        
    }
    function jcorglrknfeeds() {
        $list = "
        <table style='width:400px;' class='widefat'>
        <tr>
            <th>
            Latest posts from JaspreetChahal.org
            </th>
        </tr>
        ";
        $max = 5;
        $feeds = fetch_feed("http://feeds.feedburner.com/jaspreetchahal/mtDg");
        $cfeeds = $feeds->get_item_quantity($max); 
        $feed_items = $feeds->get_items(0, $cfeeds); 
        if ($cfeeds > 0) {
            foreach ( $feed_items as $feed ) {    
                if (--$max >= 0) {
                    $list .= " <tr><td><a href='".$feed->get_permalink()."'>".$feed->get_title()."</a> </td></tr>";}
            }            
        }
        return $list."</table>";
    }
    
    
    function jcorglrknMakeErrorsHtml($errors,$type="error")
    {
        $class="jcorgberror";
        $title=__("Please correct the following errors","jcorgbot");
        if($type=="warnings") {
            $class="jcorgberror";
            $title=__("Please review the following Warnings","jcorgbot");
        }
        if($type=="warning1") {
            $class="jcorgbwarning";
            $title=__("Please review the following Warnings","jcorgbot");
        }
        $strCompiledHtmlList = "";
        if(is_array($errors) && count($errors)>0) {
                $strCompiledHtmlList.="<div class='$class' style='width:90% !important'>
                                        <div class='jcorgb-errors-title'>$title: </div><ol>";
                foreach($errors as $error) {
                      $strCompiledHtmlList.="<li>".$error."</li>";
                }
                $strCompiledHtmlList.="</ol></div>";
        return $strCompiledHtmlList;
        }
    }