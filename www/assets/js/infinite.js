function setParent(parentID)
{
    $.ajax({
        url: '/admin/content/set-parent',
        type: 'get',
        data: {'page': parentID},
        success: function(data, status) {
            parent.$.fancybox.close();
        }
    });
}

jQuery(document).ready(function() {
    /* grab important elements */
    var sortInput = jQuery('#sort_order');
    //var submit = jQuery('#autoSubmit');
    var list = jQuery('#sortable-list-content');

    /* create requesting function to avoid duplicate code */
    var request = function() {
        jQuery.ajax({
            //data: 'sort_order=' + sortInput[0].value + '&ajax=' + submit[0].checked + '&do_submit=1&byajax=1', //need [0]?
            data: 'sort_order=' + sortInput[0].value + '&ajax=' + true + '&do_submit=1&byajax=1', //need [0]?
            type: 'post',
            url: '/admin/content/set-order'
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
            //fnSubmit(submit[0].checked);
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
