



<div class="cf7rt">
        <?php
        if(!$is_contact_form_7_active){
                ?>
                        <div class="error notice">
                        <p><strong>Warning! Contact Form 7 needs to installed and activated for this plugin to work.</strong> </p>
                        </div>
                <?php
        } 
        ?>
        <h2 class="nav-tab-wrapper">
        <a href="?page=cf7rt-optionSetting&tab=setting_options" id="one" class="nav-tab">Setting</a>
        <a href="?page=cf7rt-optionSetting&tab=help_options" id="two" class="nav-tab" >Help </a>
        <a href="?page=cf7rt-optionSetting&tab=about" id="two" class="nav-tab" >About </a>
        </h2>
         <div class="nav-tab">
                        <?php if($active_tab=="setting_options") { ?>
                                <div>         
                                        <form method="post"  >
                                        
                                                <p>Track following HTTP header(s):</p>
                                                <?php                                        
                                                $i=0;     
                                                while(sizeof($bitss_track_http_headers) > $i) {   
                                                        $name= $bitss_track_http_headers[$i];   
                                                        if(in_array($name,$cf7rt_selected_http_headers)){
                                                                echo "<label><Input checked type='Checkbox' id='".strtolower($name)."' name='track_http_headers[]' 
                                                                value=". $name."> ".$name."</label></br>";
                                                        }else{
                                                                echo "<label><Input type='Checkbox' id='".strtolower($name)."' name='track_http_headers[]' 
                                                                value=". $name."> ".$name."</label></br>";
                                                        }
                                                        $i=$i+1;   
                                                }                                   
                                                ?> 
                                                <br>
                                                <p>Note: HTTP headers will be automatically included in admin emails.</p>
                                                <select style="display:none" name="include_http_header_in_admin_email">
                                                        <option <?php echo $selectOption=="Automatic"?"selected":""; ?> value="Automatic">Automatic</option>
                                                        <option <?php echo $selectOption=="Manual"?"selected":""; ?> value="Manual">Manual</option>
                                                </select><br>
                                                <?php
                                                submit_button(); 
                                                ?>
                                                <br>
                                        </form>       
                                </div>
                        <?php } ?>
                        <?php if($active_tab=="help_options") { ?>
                                <div>                                                      
                                        <div style="padding: 5px;">
                                                <h2><i> Steps to send Referer from Contact forms: </i></h2>
                                                <h3 style="display:inline">1.</h3> <p style="font-size: large;display: inline;"> Select which http headers should be tracked in Settings Tab.</p><br> 
                                        </div>
                                        <div style="padding: 5px;">
                                                <h3 style="display:inline">2.</h3> <p style="font-size: large;display: inline;"> Thats it! Selected http headers will be automatically sent along with all Contact Form 7 submission emails.  <br>       
                                        </div>
                                        <div style="padding: 5px;">
                                                <h3 style="display:inline">3.</h3><p style="font-size: large;display: inline;"> Save and test. It will look like this:</p>
                                                <img src="<?php echo esc_url( plugins_url( 'img/cf7rt_email_preview.jpg', __DIR__ )); ?>" style="display: block;width: 80%;" alt="bitss" />
                                        </div>     
                                       
                                                                
                                </div>
                        <?php } ?>
                        <?php if($active_tab=="about") { ?>
                                <div style="padding: 5px;">
                                        <img src="<?php echo esc_url( plugins_url( 'img/bitss_techniques_logo.png', __DIR__ )); ?>" alt="Bitss Techniques logo"/><br>
                                        <b>Developed by: <a target="_blank" href="https://bitss.tech">Bitss Techniques</a></b><br>
                                        <em>Made in India</em>
                                        <p>If you find this plugin useful, please rate it. For any suggestions/feedback please <a target="_blank" href="https://wa.me/+919462242982">contact us</a>.</p>
                                </div> 
                        <?php } ?>   
        </div>
</div>
