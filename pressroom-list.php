<?php

function mmm_press_room_list() {
        global $wpdb;
        $table_name = TABLE_NAME;
        $table_pk = TABLE_PK;
        $place_holder_img = PLACE_HOLDER_IMG;
        
        $customPagHTML     = "";
        $query             = "SELECT * FROM $table_name";
        $total_query     = "SELECT COUNT(1) FROM ${table_name} AS combined_table";
        $total             = $wpdb->get_var( $total_query );
        $items_per_page = RECORD_PER_PAGE;
        $page             = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
        $offset         = ( $page * $items_per_page ) - $items_per_page;
        $rows         = $wpdb->get_results( $query . " ORDER BY ${table_pk} DESC LIMIT ${offset}, ${items_per_page}" );
        $totalPage         = ceil($total / $items_per_page);
        
        if($totalPage > 1){
            
            $pagination_text = "Showing ".(($page-1)*$items_per_page+1) ." to ".($page*$items_per_page)." of  $total entries";
            
            $customPagHTML     =  '<div class="pagination"><span>'.$pagination_text.', &nbsp;&nbsp;Page '.$page.' of '.$totalPage.'</span>'.paginate_links( array(
            'base' => add_query_arg( 'cpage', '%#%' ),
            'format' => '',
            'prev_text' => __('&laquo;'),
            'next_text' => __('&raquo;'),
            'total' => $totalPage,
            'current' => $page
            )).'</div>';
        }
        
    ?>
    <link rel="stylesheet" href="<?php echo WP_MYPLUGIN_PATH; ?>/magnific-popup/magnific-popup.css">
    <link type="text/css" href="<?php echo WP_MYPLUGIN_PATH; ?>/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2 class="head-h2">Press Room</h2>
        
        <div class="tablenav top">
            <div class="alignleft actions">
                <a class="page-title-action" href="<?php echo admin_url('admin.php?page=mmm_press_room_create'); ?>">Add New</a>
            </div>            
        </div>
        <?php if (!empty($customPagHTML)) {
            echo $customPagHTML;
        } ?>
        <table class='responsive wp-list-table widefat fixed striped pages'>
            <thead>
            <tr>
                <th class="manage-column ss-list-width">ID</th>
                <th class="manage-column ss-list-width">Title</th>
                <th class="manage-column ss-list-width">Full Image</th>
                <th class="manage-column ss-list-width">Thumbnail Image</th>
                <th class="manage-column ss-list-width">Remarks</th>
                <th class="manage-column ss-list-width">Status</th>
                <th class="manage-column ss-list-width">Media Type</th>
                <th class="manage-column ss-list-width">Updated Date</th>
                <th>Action</th>
                
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rows as $row) {
                ?>
                <tr>
                    <td class="manage-column ss-list-width"><?php echo $row->$table_pk; ?></td>
                    <td class="manage-column ss-list-width" title="News Paper Link - <?php echo $row->NEWSPAPERLINK; ?>"><?php echo $row->TITLE; ?></td>
                    <td class="manage-column ss-list-width">
                        <?php if($row->MEDIATYPE == 'VIDEO' && strpos($row->NEWSPAPERLINK, 'youtube.com') !== false) { ?>
                            <iframe width="200" height="100" src="<?php echo $row->NEWSPAPERLINK; ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                        <?php } elseif(!empty($row->IMAGEPATH)) { ?>
                            <a href="<?php echo $row->IMAGEPATH; ?>" class="pressroom_full_images zoom-gallery"><img style="max-width: 200px;" src="<?php echo $row->IMAGEPATH; ?>" alt="<?php echo $row->TITLE; ?>"></a>
                        <?php } else { ?>
                            <img style="max-width: 200px;" src="<?php echo $place_holder_img; ?>" alt="<?php echo $row->TITLE; ?>">
                        <?php } ?>                        
                    </td>
                    <td class="manage-column ss-list-width">
                        <?php if(!empty($row->THUMBNAIL)) { ?>
                        <a href="<?php echo $row->THUMBNAIL; ?>" class="pressroom_thumb_images zoom-gallery"><img style="max-width: 200px;" src="<?php echo $row->THUMBNAIL; ?>" alt="<?php echo $row->TITLE; ?>"></a>
                        <?php } else { ?>
                            <img style="max-width: 200px;" src="<?php echo $place_holder_img; ?>" alt="<?php echo $row->TITLE; ?>">
                        <?php } ?>  
                    </td>
                    
                    <td class="manage-column ss-list-width"><?php echo $row->REMARKS; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->ACTIVE; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->MEDIATYPE; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->UPDATED_DATETIME; ?></td>
                    <td>
                        <a href="<?php echo admin_url("admin.php?page=mmm_press_room_update&$table_pk=" . $row->$table_pk); ?>" class="pressroom-btn pressroom-btn-danger">Update</a>
                        <form method="post" action="<?php echo admin_url("admin.php?page=mmm_press_room_update&$table_pk=" . $row->$table_pk); ?>">
                            <input type='submit' name="delete" value='Delete' class="pressroom-btn pressroom-btn-default" onclick="return confirm('Are you sure ?')">
                        </form>
                    </td>
                    
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php if(!empty($customPagHTML)) { echo $customPagHTML; } ?>
    </div>
    <script src="<?php echo WP_MYPLUGIN_PATH; ?>/magnific-popup/jquery.magnific-popup.js"></script>
    <script>
    jQuery(function(){
        
        jQuery('.pressroom_full_images,.pressroom_thumb_images').magnificPopup({
          type: 'image',
          closeOnContentClick: true,
          closeBtnInside: false,
          fixedContentPos: true,
          mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
          image: {
            verticalFit: true
          },
//          gallery: {
//            enabled: true
//          },
          zoom: {
            enabled: true,
            duration: 300 // don't foget to change the duration also in CSS
          }
        });
        
        
    });
    </script>
    <?php
}
