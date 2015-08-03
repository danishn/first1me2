$(document).ready(function(){
    var BASE_URL = "../index.php";                                              //cloud
    //var BASE_URL = "../first1me2/index.php";                                  //home
    
    $(function(){
        $("#example1").dataTable();
        $('#dealTableTable').dataTable({
          "bPaginate": true,
          "bLengthChange": false,
          "bFilter": false,
          "bSort": true,
          "bInfo": true,
          "bAutoWidth": false
        });
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
    
    $("#dealTable").on("click", "tr", function(){
        //console.log("clicked" + $(this).attr("id"));
        $.ajax({
            url : BASE_URL + "/Deals/GetThis",
            type : "POST",
            headers : {"Api-Key": "1234"},
            data : {"dealId" : $(this).attr("id")},
            success : function(data){
                //console.log(data);
                data = JSON.parse(data);
                if(data.status == "success"){
                    //console.log(data);
                    $("#editDealModal #name").val(data.data.name);
                    $("#editDealModal #shortDesc").val(data.data.shortDesc);
                    $("#editDealModal #longDesc").val(data.data.longDesc);
                    $("#editDealModal #pseudoViews").val(data.data.pseudoViews);
                    $("#editDealModal #region").val(data.data.region);
                    
                    var now = new Date(data.data.expiresOn.date.substring(0,data.data.expiresOn.date.indexOf(' ')));
                    var day = ("0" + now.getDate()).slice(-2);
                    var month = ("0" + (now.getMonth() + 1)).slice(-2);
                    var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
                    $("#editDealModal #expiresOn").val(today);
                    $("#editDealModal").modal('show');
                }
            },
           
            error : function(XMLHttpRequest, textStatus, errorThrown){ 
                console.log("Status: " + textStatus + ", Error: " + errorThrown); 
            }  
        });
    });
    
    function dealStat(){
        $.ajax({
            url : BASE_URL + "/Stat/Deals",
            type : "POST",
            headers : {"Api-Key": "1234"},
            
            success : function(data){
                //console.log(data);
                if(BASE_URL.indexOf("index.php") > -1)
                    var imagePath = BASE_URL.substring(0, BASE_URL.indexOf("/index.php"));
                else
                    var imagePath = BASE_URL;
                data = JSON.parse(data);
                $("#dealTable").empty();
                data.data.deals.map(function(deal){
                    $("#dealTable").append("<tr id='" + deal.id + "'><td>" + "<img src='" + imagePath + deal.thumbnailImg + "' width = '30px' height = '30px'>" + "</td><td>" + deal.name + "</td><td>" + deal.category + "</td><td>" + deal.region + "</td><td>" + deal.views + "</td><td>" + deal.expiresOn.date.substring(0,deal.expiresOn.date.indexOf(' ')) + "</td><td>" + getStatusButton(deal.status) + "</td></tr>");
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
    
    $("#region").keyup(function(event)
    {
        if((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 65 && event.keyCode <= 90) || event.keyCode == 8 || event.keyCode == 46)
        {
            suggestion_prefix = $("#region").val();
            if(suggestion_prefix != "")
            {
                $.ajax({
                    url : BASE_URL + "/Places/GetCities",
                    type : "POST",
                    headers : {"Api-Key": "1234"},
                    data : {"countryCode" : "IN", "input" : suggestion_prefix},

                    success : function(data){
                        response = JSON.parse(data);
                        if(response.status == "success"){
                            //console.log(response.data);
                            $("#regionList").empty();
                            for(var i=0; i<response.data.length; i++)
                            {
                                $("#regionList").append("<option value='" + response.data[i] + "'>");
                            }
                        }
                        else
                            console.log("Error : ", data);
                    },

                    error : function(XMLHttpRequest, textStatus, errorThrown){ 
                        console.log("Status: " + textStatus + ", Error: " + errorThrown); 
                    }  
                });
            }
        }
    });
    
    
    $("form").submit(function(event){	//GENERIC form submit function
	event.preventDefault();
        var form_id = "#" + $(this).closest("form").attr("id");
	var form_data = new FormData(this);
	//var form_data = $(this).serialize();
        var url = $(form_id).attr("action");
        //console.log("form data - ", form_data);
        $.ajax({
            url : BASE_URL + url,
            type : "POST",
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            headers : {"Api-Key": "1234"},
            data : form_data,
            success : function(data){
                console.log(data);
                response = JSON.parse(data);
                if(response.status == "success"){
                    //console.log(response.data[0]);
                    $(form_id + "_success").text(response.data[0]).fadeIn(2000);
                    dealStat();
                }
                else{
                    $(form_id + "_danger").text("Error Code #" + response.message.Code + ", " + response.message.Title);
                }
                $(form_id + "input[type='reset']").trigger("click");
            },
           
            error : function(XMLHttpRequest, textStatus, errorThrown){ 
                console.log("Status: " + textStatus + ", Error: " + errorThrown); 
            }
        });
    });
});