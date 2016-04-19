/* 
 * @author Ivan Nikiforov
 * Apr 19, 2016
 */

(function($){
    
    var eventSelect = $("#notification-event");
    var placeholders = $("#notification-placeholders");
    
    function getPlaceholders() {
        $.ajax({
            dataType: "json",
            url: "/notification/placeholders",
            data: {class: eventSelect.val().split("::")[0]},
            success: function(data) {
                if(data.status !== "ok") {
                    return;
                }
                
                placeholders.text(data.placeholders.join(", "));
            }
        });
    }
    
    eventSelect.change(function(){
       getPlaceholders(); 
    });
    
    getPlaceholders();
    
})(jQuery);
