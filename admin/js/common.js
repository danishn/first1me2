$(document).ready(function(){
    $(document).ajaxStart(function(){
        $("#progress_container").fadeIn(300)
    }).ajaxStop(function(){
        $("#progress_container").fadeOut(300)
    });
    
    
    function getStatusButton(status){
        if(status == 0)
            return "<span class='label label-danger'>Suspend</span>";
        if(status == 1)
            return "<span class='label label-success'>Active</span>";
        if(status == 2)
            return "<span class='label label-warning'>Pending</span>";
        if(status == 3)
            return "<span class='label label-primary'>Hidden</span>";
    }
});