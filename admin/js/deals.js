$(document).ready(function(){
    var BASE_URL = "../index.php";                                              //cloud
    //var BASE_URL = "../first1me2/index.php";                                  //home
    
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
    
    function dealStat(){
        $.ajax({
            url : BASE_URL + "/Stat/Deals",
            type : "POST",
            headers : {"Api-Key": "1234"},
            
            success : function(data){
                //console.log(data);
                data = JSON.parse(data);
                $("#dealTable").empty();
                data.data.deals.map(function(deal){
                    $("#dealTable").append("<tr id='" + deal.id + "'><td>" + "IMG" + "</td><td>" + deal.shortDesc + "</td><td>" + deal.region + "</td><td>" + deal.views + "</td><td>" + deal.expiresOn.date.substring(0,deal.expiresOn.date.indexOf('.')) + "</td><td>" + getStatusButton(deal.status) + "</td></tr>");
                });
            },
           
            error : function(XMLHttpRequest, textStatus, errorThrown){ 
                console.log("Status: " + textStatus + ", Error: " + errorThrown); 
            }  
        });
    }
    dealStat();
    
    function categoryListing(){
        $.ajax({
            url : BASE_URL + "/Category/Listing",
            type : "POST",
            headers : {"Api-Key": "1234"},

            success : function(data){
                data = JSON.parse(data);
                //console.log(data);
                $("#categoryList").empty();
                $("#categoryList").append("<option value='0'>-Select-</option>");
                data.data.map(function(category){
                    $("#categoryList").append("<option value='" + category.id + "'>" + category.name + "</options>");
                });
            },

            error : function(XMLHttpRequest, textStatus, errorThrown){ 
                console.log("Status: " + textStatus + ", Error: " + errorThrown); 
            }  
        });
    }
    categoryListing();
    
    function vendorListing(){
        $.ajax({
            url : BASE_URL + "/Vendor/Listing",
            type : "POST",
            headers : {"Api-Key": "1234"},

            success : function(data){
                data = JSON.parse(data);
                //console.log(data);
                $("#vendorList").empty();
                $("#vendorList").append("<option value='0'>-Select-</option>");
                data.data.map(function(category){
                    $("#vendorList").append("<option value='" + category.id + "'>" + category.name + "</options>");
                });
            },

            error : function(XMLHttpRequest, textStatus, errorThrown){ 
                console.log("Status: " + textStatus + ", Error: " + errorThrown); 
            }  
        });
    }
    vendorListing();
});