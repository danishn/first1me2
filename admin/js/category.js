$(document).ready(function(){
    var BASE_URL = "../index.php";
    
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
    
    function categoryStat(){
        $.ajax({
            url : BASE_URL + "/Stat/Category",
            type : "POST",
            headers : {"Api-Key": "1234"},
            
            success : function(data){
                data = JSON.parse(data);
                //console.log(data);
                $("#categoryTable").empty();
                data.data.map(function(category){
                    $("#categoryTable").append("<tr id='" + category.id + "'><td>" + category.displayName + "</td><td>" + category.createdOn.date.substring(0,category.createdOn.date.indexOf(' ')) + "</td><td>" + category.subscribed + "</td><td>" + category.deals + "</td><td>" + category.totalViews + "</td><td>" + getStatusButton(category.status) + "</td></tr>");
                });
            },
            error : function(XMLHttpRequest, textStatus, errorThrown){ 
                console.log("Status: " + textStatus + ", Error: " + errorThrown); 
            }  
        });
    }
    categoryStat();
});