$(document).ready(function(){
    var BASE_URL = "../index.php";
    
    function vendorStat(){
        $.ajax({
            url : BASE_URL + "/Stat/Vendor",
            type : "POST",
            headers : {"Api-Key": "1234"},
            
            success : function(data){
                //console.log(data);
                data = JSON.parse(data);
                $("#vendorCount > h3").text(data.data.vendors.length);
                $("#vendorTable").empty();
                data.data.vendors.map(function(vendor){
                    $("#vendorTable").append("<tr id='" + vendor.id + "'><td>" + vendor.firstName + " " + vendor.lastName + "</td><td>" + vendor.businessTitle + "</td><td>" + vendor.registeredOn.date.substring(0,vendor.registeredOn.date.indexOf('.')) + "</td><td>" + vendor.totalCategories + "</td><td>" + vendor.totalDeals + "</td><td>" + vendor.totalViews + "</td></tr>");
                });
            },
           
            error : function(XMLHttpRequest, textStatus, errorThrown){ 
                console.log("Status: " + textStatus + ", Error: " + errorThrown); 
            }  
        });
    }
    vendorStat();
});