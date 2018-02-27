jQuery(function(){
    
   var custom_uploader;
    jQuery(document).on('click', '.upload_image_button', function (e) {

        var upload_txt_id = jQuery(this).data('id');

        e.preventDefault();
        //If the uploader object has already been created, reopen the dialog
    //    console.log(custom_uploader);
    //    if (custom_uploader) {
    //        custom_uploader.open();
    //        return;
    //    } else {

            custom_uploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Image',
                button: {
                    text: 'Choose Image'
                },
                multiple: false
            });

            //Extend the wp.media object
            custom_uploader.on('select', function () {
                attachment = custom_uploader.state().get('selection').first().toJSON();
                jQuery(upload_txt_id).val(attachment.url);
                jQuery(upload_txt_id+'_IMG').attr('src', attachment.url);
                jQuery(upload_txt_id+'_RM').show();
    //            console.log(attachment.url);
                //close the uploader dialog
                custom_uploader.close();
            });

            custom_uploader.open();
            return;
    //    }
    }); 
    
    jQuery(document).on('click', '.remove_image', function(){
        var $this = jQuery(this), id =$this.data('id'), placeholder_image = PRESSROOM.PLACE_HOLDER_IMG;
        jQuery(id).val('');
        jQuery(id+'_IMG').attr('src', placeholder_image);
        jQuery(this).hide();        
    });
});