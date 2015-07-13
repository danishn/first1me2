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
    
    function donutChart(totalAndroid, totalIos){
	var chart = new CanvasJS.Chart("donut-chart",
	{
		title:{
			text: "Devices Registered"
		},     
                animationEnabled: true,     
		data: [
		{        
			type: "doughnut",
			startAngle: 60,                          
			toolTipContent: "{legendText}: {y} - <strong>#percent% </strong>", 					
			showInLegend: true,
			dataPoints: [
				{y: totalAndroid, indexLabel: "Android Users #percent%", legendText: "Android Users" },
				{y: totalIos, indexLabel: "iOS Users #percent%", legendText: "iOS Users" }	
			]
		}
		]
	});
	chart.render();
    }
    
    function monthlyChart(){
        var chart = new CanvasJS.Chart("month-chart",
        {      
          title:{
            text: "New Registration"
          },
          animationEnabled: true,
          axisY :{
            includeZero: false
          },
          toolTip: {
            shared: "true"
          },
          data: [
          {        
            type: "spline", 
            showInLegend: true,
            name: "New User Registration",
            markerSize: 0,        
            dataPoints: [
            {x: new Date(2013,4,1 ), y: 430576},
            {x: new Date(2013,4,2 ), y: 498157},      
            {x: new Date(2013,4,3 ), y: 415128},      
            {x: new Date(2013,4,4 ), y: 342031},      
            {x: new Date(2013,4,5 ), y: 320376},      
            {x: new Date(2013,4,6 ), y: 405322},      
            {x: new Date(2013,4,7 ), y: 433426},      
            {x: new Date(2013,4,8 ), y: 430876},      
            {x: new Date(2013,4,09 ), y: 372277},      
            {x: new Date(2013,4,10 ), y: 351863},      
            {x: new Date(2013,4,11 ), y: 281959},      
            {x: new Date(2013,4,12 ), y: 282666},      
            {x: new Date(2013,4,13 ), y: 353718},      
            {x: new Date(2013,4,14 ), y: 507833}    
            ]
          },
          {        
            type: "spline", 
            showInLegend: true,
            name: "New Vendor Registration",
            markerSize: 0,        
            dataPoints: [
            {x: new Date(2013,4,1 ), y: 110386},
            {x: new Date(2013,4,2 ), y: 110330},      
            {x: new Date(2013,4,3 ), y: 108025},      
            {x: new Date(2013,4,4 ), y: 59493},      
            {x: new Date(2013,4,5 ), y: 66765},      
            {x: new Date(2013,4,6 ), y: 102950},      
            {x: new Date(2013,4,7 ), y: 89233},      
            {x: new Date(2013,4,8 ), y: 89133},      
            {x: new Date(2013,4,09 ), y: 86751},      
            {x: new Date(2013,4,10 ), y: 58672},      
            {x: new Date(2013,4,11 ), y: 43560},      
            {x: new Date(2013,4,12 ), y: 87404},      
            {x: new Date(2013,4,13 ), y: 202324},      
            {x: new Date(2013,4,14 ), y: 208084}    
            ]
          }      
          ]
        });

        chart.render();
    }
    
    $("#refreshUser").click(function(){
        dashBoardStat();
    });
    
    $("#refreshCategory").click(function(){
        dashBoardStat();
    });
    
    $("#refreshDeals").click(function(){
        dashBoardStat();
    });
    
    $("#refreshVendor").click(function(){
        dashBoardStat();
    });
    
    function dashBoardStat(){
        $.ajax({
            url : BASE_URL + "/Stat/DashBoard",
            type : "POST",
            headers : {"Api-Key": "1234"},
            
            success : function(data){
                data = JSON.parse(data);
                //console.log(data);
                $("#vendorCount > h3").text(data.data.totalVendor);
                $("#userCount > h3").text(data.data.totalUser);
                $("#categoryCount > h3").text(data.data.totalCategory);
                $("#dealCount > h3").text(data.data.totalDeal);
                
                donutChart(data.data.totalAndroid, data.data.totalIos);
                
                monthlyChart();
            },
           
            error : function(XMLHttpRequest, textStatus, errorThrown){ 
                console.log("Status: " + textStatus + ", Error: " + errorThrown); 
            }  
        });
    }
    dashBoardStat();
    
    function userStat(){
        $.ajax({
            url : BASE_URL + "/Stat/User",
            type : "POST",
            headers : {"Api-Key": "1234"},
            
            success : function(data){
                data = JSON.parse(data);
                //console.log(data);
                $("#userTable").empty();
                data.data.users.map(function(user){
                    $("#userTable").append("<tr id='" + user.id + "'><td>" + user.firstName + " " + user.lastName + "</td><td>" + user.mobile + "</td><td>" + user.city + "</td><td>" + user.os + "</td><td>" + user.subscribed + "</td></tr>");
                });
            },
           
            error : function(XMLHttpRequest, textStatus, errorThrown){ 
                console.log("Status: " + textStatus + ", Error: " + errorThrown); 
            }  
        });
    }
    userStat();
    
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
                    $("#categoryTable").append("<tr id='" + category.id + "'><td>" + category.displayName + "</td><td>" + category.createdOn.date.substring(0,category.createdOn.date.indexOf('.')) + "</td><td>" + category.subscribed + "</td><td>" + category.deals + "</td><td>" + category.totalViews + "</td><td>" + getStatusButton(category.status) + "</td></tr>");
                });
            },
           
            error : function(XMLHttpRequest, textStatus, errorThrown){ 
                console.log("Status: " + textStatus + ", Error: " + errorThrown); 
            }  
        });
    }
    categoryStat();
    
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
                    $(form_id + "_success").text(response.data[0]).fadeIn(2000).fadeOut(2000);
                    vendorStat();
                }
                else{
                    $(form_id + "_danger").text("Error Code #" + response.message.Code + ", " + response.message.Title).fadeIn(2000);
                }
                $(form_id + "input[type='reset']").trigger("click");
            },
           
            error : function(XMLHttpRequest, textStatus, errorThrown){ 
                console.log("Status: " + textStatus + ", Error: " + errorThrown); 
            }
        });
    });
});