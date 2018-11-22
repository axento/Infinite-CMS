/**
 * Created by axento on 2/03/16.
 */
jQuery(document).ready(function() {
    /* grab important elements */
    var sortInput = jQuery('#sort_order');
    var list = jQuery('#sortable-list-header');

    /* create requesting function to avoid duplicate code */
    var request = function() {
        jQuery.ajax({
            data: 'sort_order=' + sortInput[0].value + '&ajax=' + true + '&do_submit=1&byajax=1', //need [0]?
            type: 'post',
            url: '/admin/header/set-order'
        });
    };
    /* worker function */
    var fnSubmit = function(save) {
        var sortOrder = [];
        list.children('li').each(function(){
            sortOrder.push(jQuery(this).data('id'));
        });
        sortInput.val(sortOrder.join(','));
        console.log(sortInput.val());
        if(save) {
            request();
        }
    };
    /* store values */
    list.children('li').each(function() {
        var li = jQuery(this);
        li.data('id',li.attr('title')).attr('title','');
    });
    /* sortables */
    list.sortable({
        opacity: 0.7,
        update: function() {
            fnSubmit(true);
        }
    });
    list.disableSelection();
    /* ajax form submission */

    jQuery('#dd-form').bind('submit',function(e) {
        if(e) e.preventDefault();
        fnSubmit(true);
    });

});
