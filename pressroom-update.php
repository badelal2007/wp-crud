<?php
function mmm_press_room_update() {
    
    wp_enqueue_media();
    global $validator;
    global $wpdb;
    $table_name = TABLE_NAME;
    $table_pk = TABLE_PK;    
    $place_holder_img = PLACE_HOLDER_IMG;
    $redirect_url = 'admin.php?page=mmm_press_room_list';
    $pressroom = null;
    $image_text_type = IMAGE_TEXT_TYPE;  // hidden OR text    
    $pk = @$_GET[$table_pk];
    $title = @$_POST["TITLE"];
    $imagepath = @$_POST["IMAGEPATH"];
    $thumbnail = @$_POST["THUMBNAIL"];
    $newspaperlink = @$_POST["NEWSPAPERLINK"];
    $remarks = @$_POST["REMARKS"];
    $description = @$_POST["DESCRIPTION"];
    $mediatype = @$_POST["MEDIATYPE"];
    $active = @$_POST["ACTIVE"];
    
    $submited_data = [];    
    $errors = $validator->getErrors();
    
    if (isset($_POST['update']) && $validator->passed()) {
        
        foreach ($_POST as $key=>$value) {
            if(!is_array($value)) {
                $submited_data[$key] = trim($value);
            } else {
                $submited_data[$key] = $value;
            }
            
        }
        
        $validator->validate($submited_data, VALIDATION_RULES);
        $errors = $validator->getErrors();
        
        $pk = $submited_data[$table_pk];
        $title = $submited_data["TITLE"];
        $imagepath = $submited_data["IMAGEPATH"];
        $thumbnail = $submited_data["THUMBNAIL"];
        $newspaperlink = $submited_data["NEWSPAPERLINK"];
        $remarks = $submited_data["REMARKS"];
        $description = $submited_data["DESCRIPTION"];
        $mediatype = $submited_data["MEDIATYPE"];
        $active = $submited_data["ACTIVE"];
        
        if($validator->passed()) {
            $wpdb->update(
                $table_name,
                array(
                    'TITLE' => $title,
                    'IMAGEPATH' => $imagepath,
                    'THUMBNAIL' => $thumbnail,
                    'NEWSPAPERLINK' => $newspaperlink,
                    'REMARKS' => $remarks,
                    'DESCRIPTION' => $description,
                    'MEDIATYPE' => $mediatype,
                    'ACTIVE' => $active,
                    'UPDATED_DATETIME' => date('Y-m-d H:i:s'),
                    'UPDATEDBY' => get_current_user_id(),
                ),
                array($table_pk => $pk),
                array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'),
                array('%s')
            );
        
            $_SESSION['pres_room_msg'] = '<div class="updated"><p>"'.$title . '" updated successfully.</p></div>';   
        }
        
    } else if (isset($_POST['delete'])) {
        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE $table_pk = %s", $pk));
        $_SESSION['pres_room_msg'] = '<div class="updated"><p>Press Room deleted successfully.</p></div>';
    } else if(!isset ($_POST['update'])) {//selecting value to update	
        $pressroom = $wpdb->get_results($wpdb->prepare("SELECT * from $table_name where $table_pk=%s", $pk));
        foreach ($pressroom as $s) {
            $title = $s->TITLE;
            $imagepath = $s->IMAGEPATH;
            $description = $s->DESCRIPTION;
            $newspaperlink = $s->NEWSPAPERLINK;
            $active = $s->ACTIVE;
            $remarks = $s->REMARKS;
            $mediatype = $s->MEDIATYPE;
            $thumbnail = $s->THUMBNAIL;
        }
    } else {
        $pressroom = true;
    }
    
    ?>
    <link type="text/css" href="<?php echo WP_MYPLUGIN_PATH; ?>/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2 class="head-h2">Press Room - <a href="<?php echo admin_url('admin.php?page=mmm_press_room_list') ?>">&laquo; Back to Press Room</a></h2> 
        <div class="tablenav top">
            <div class="alignleft actions">
                <a class="page-title-action" href="<?php echo admin_url('admin.php?page=mmm_press_room_create'); ?>">Add New</a>
            </div>            
        </div>
        
        <?php 
        $flag = 1;
        if(isset($_SESSION['pres_room_msg']) && !empty($_SESSION['pres_room_msg'])) {
            $flag = 0;
            echo $_SESSION['pres_room_msg'];
            unset($_SESSION['pres_room_msg']);
        }
        if($pressroom) {
        
        ?>
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <div id="post-body-content" class="form-section">
                    <div class="container-left">
                        <div class="inside">
                            <strong>Title</strong>
                            <input name="TITLE" id="TITLE" value="<?php echo $title; ?>" class="textbox" title="Enter title" placeholder="Enter title" type="text" autocomplete="off">
                            <?php if($errors->get('TITLE')) { echo '<span class="pressroom-error">'.$errors->get('TITLE')->first().'</span>'; } ?>
                        </div>
                        
                        <div class="inside">
                            <strong>Full Image</strong>
                            <input name="IMAGEPATH" id="IMAGEPATH" value="<?php echo $imagepath; ?>" class="textbox" title="Image URL" placeholder="Image URL" type="<?php echo $image_text_type; ?>" autocomplete="off"/>
                            <?php if(empty($imagepath)){
                                $imagepath = $place_holder_img;
                            } ?>
                            <?php if($errors->get('IMAGEPATH')) { echo '<span class="pressroom-error">'.$errors->get('IMAGEPATH')->first().'</span>'; } ?>
                            <br/>
                            <div style="float: right;" >
                                <input data-id="#IMAGEPATH" class="upload_image_button pressroom-btn-default" type="button" value=" Upload " />
                                <?php 
                                $style = "display: none;";
                                if($imagepath != $place_holder_img) {
                                    $style = "display: block;";
                                }
                                ?>
                                <br/>
                                <input data-id="#IMAGEPATH" id="IMAGEPATH_RM" style="<?php echo $style; ?>" class="remove_image pressroom-btn-danger" type="button" value="Remove" />
                            </div>
                            <img id="IMAGEPATH_IMG" style="width:200px;" src="<?php echo $imagepath; ?>" alt="Full image">                            
                        </div>
                        
                        <div class="inside">
                            <strong>Thumbnail Image</strong>
                            <input name="THUMBNAIL" id="THUMBNAIL" value="<?php echo $thumbnail; ?>" class="textbox" title="Image URL" placeholder="Image URL" type="<?php echo $image_text_type; ?>" autocomplete="off"/>
                            <?php if($errors->get('THUMBNAIL')) { echo '<span class="pressroom-error">'.$errors->get('THUMBNAIL')->first().'</span>'; } ?>
                            <?php if(empty($thumbnail)){
                                $thumbnail = $place_holder_img;
                            } ?>
                            <br/>
                            <div style="float: right;" >
                              <input data-id="#THUMBNAIL" class="upload_image_button pressroom-btn-default" type="button" value=" Upload " />
                                <?php 
                                $style = "display: none;";
                                if($thumbnail != $place_holder_img) {
                                    $style = "display: block;";
                                }
                                ?>
                                <br/>
                                <input data-id="#THUMBNAIL" id="THUMBNAIL_RM" style="<?php echo $style; ?>" class="remove_image pressroom-btn-danger" type="button" value="Remove" />
                            </div>
                            <img id="THUMBNAIL_IMG" style="width:200px;" src="<?php echo $thumbnail; ?>" alt="Thumbnail image">                            
                        </div>
                        
                        <div class="inside">
                            <strong>Newspaper / Video link</strong>
                            <input name="NEWSPAPERLINK" id="NEWSPAPERLINK" value="<?php echo $newspaperlink; ?>" class="textbox" title="Enter Newspaper/Video link" placeholder="Enter Newspaper/Video link" type="text" autocomplete="off">
                            <?php if($errors->get('NEWSPAPERLINK')) { echo '<span class="pressroom-error">'.$errors->get('NEWSPAPERLINK')->first().'</span>'; } ?>
                        </div>
                        
                        <div class="inside">
                            <strong>Remarks</strong>
                            <input name="REMARKS" id="REMARKS" value="<?php echo $remarks; ?>" class="textbox" title="Enter remarks" placeholder="Enter remark" type="text" autocomplete="off">
                            <?php if($errors->get('REMARKS')) { echo '<span class="pressroom-error">'.$errors->get('REMARKS')->first().'</span>'; } ?>
                        </div>
                        
                    </div>
                    <div class="container-right">
                        <div class="inside">
                            <strong>Description</strong>
                            <?php wp_editor($description, 'DESCRIPTION', ['textarea_name'=>'DESCRIPTION', 'editor_height'=>EDITOR_HEIGHT]); ?>
                            <?php if($errors->get('DESCRIPTION')) { echo '<span class="pressroom-error">'.$errors->get('DESCRIPTION')->first().'</span>'; } ?>
                            <input name="<?php echo $table_pk; ?>" id="<?php echo $table_pk; ?>" value="<?php echo $pk; ?>" type="hidden"/>                            
                        </div>
                        <div class="inside">
                            <div style="float:left;">
                            <strong>Media Type</strong>
                            <select name="MEDIATYPE" id="MEDIATYPE" title="Select media type">
                                <option value="IMAGE" <?php if($mediatype=='IMAGE') { echo "selected='selected'"; } ?>>IMAGE</option>
                                <option value="VIDEO" <?php if($mediatype=='VIDEO') { echo "selected='selected'"; } ?>>VIDEO</option>
                            </select>
                                 <br/>
                            <?php if($errors->get('MEDIATYPE')) { echo '<span class="pressroom-error">'.$errors->get('MEDIATYPE')->first().'</span>'; } ?>
                            </div>
                            <div style="float:left;">
                            <strong>Status</strong>
                            <select name="ACTIVE" id="ACTIVE" title="Select Status">
                                <option value="A" <?php if($active=='A') { echo "selected='selected'"; } ?>>Active</option>
                                <option value="I" <?php if($active=='I') { echo "selected='selected'"; } ?>>In Active</option>
                            </select>
                                <br/>
                            <?php if($errors->get('ACTIVE')) { echo '<span class="pressroom-error">'.$errors->get('ACTIVE')->first().'</span>'; } ?>
                        </div>
                        </div>
                        
                        <div class="inside">
                            <input style="padding: 4px 17px; font-size: large;" type='submit' name="update" value='Save' class='pressroom-btn-default'>
                            <!--<input type='submit' name="delete" value='Delete' class='button' onclick="return confirm('Are you sure ?')">-->
                        </div>
                    </div>
                    
                </div>
                
            </form>
        <?php } else if($flag) { ?>
        <div class="updated"><p>No Record found.</p></div>
        <?php } ?>
    </div>    
    <script type='text/javascript'>
        PRESSROOM = {
            'PLUGIN_URL' : "<?php echo WP_MYPLUGIN_PATH; ?>",
            'PLACE_HOLDER_IMG' : "<?php echo PLACE_HOLDER_IMG; ?>"
        };
        
    </script>  
    <script type='text/javascript' src='<?php echo WP_MYPLUGIN_PATH; ?>/script.js'></script>
    <?php
}
