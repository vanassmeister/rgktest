/* 
 * @author Ivan Nikiforov
 * Apr 19, 2016
 */

(function($){
    $('button.mark-as-read').click(function(){
        var button = $(this);
        var alert = button.closest("div.alert");
        $.ajax({
            type: "post",
            dataType: "json",
            url: "/site/mark",
            data: {id: button.attr("data-id")},
            success: function(data) {
                if(data.status !== "ok") {
                    return;
                }
                
                button.hide();
                alert.removeClass("alert-warning").addClass("alert-info");
            }
        });
    });
})(jQuery);
