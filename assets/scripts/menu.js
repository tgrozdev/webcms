jQuery(document).ready(function () {
    
    var menuup = function(event){
        let id=$(this).parent().attr('id');
        var $element = this;
        var row = $($element).parents("tr:first");
        row.insertBefore(row.prev());
        jQuery.post("/admin.php",
            {   
                'page' : 'menu',
                'section': 'menuup',
                'id': id
            }, function (response) { 
                if (response.ok == "false") {
                    return false;
                } else if (response.ok == "success") {
                    // all good
                } else {
                    return false;
                }
            });
    }

    var menudown = function(event){
        let id=$(this).parent().attr('id');
        var $element = this;
        var row = $($element).parents("tr:first");
        row.insertAfter(row.next());
        jQuery.post("/admin.php",
            {   
                'page' : 'menu',
                'section': 'menudown',
                'id': id
            }, function (response) {                                
                if (response.ok == "false") {
                    return false;
                } else if (response.ok == "success") {
                    // all good
                } else {
                    return false;
                }
            });
    }

    var menudelete = function(event){
        let id=$(this).parent().attr('id');
        jQuery(event.currentTarget).parent().parent().slideUp().remove();
        jQuery.post("/admin.php",
            {   
                'page' : 'menu',
                'section': 'delete',
                'id': id
            }, function (response) {  
                if (response.ok == "false") {
                    return false;
                } else if (response.ok == "success") {
                    console.log("done");
                } else {
                    return false;
                }
            });
    }

    jQuery(".menu_up").click(menuup);
    jQuery(".menu_down").click(menudown);
    jQuery(".menu_remove").click(menudelete);
});