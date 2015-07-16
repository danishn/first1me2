$(document).ready(function(){
    var BASE_URL = "../index.php";                                              //cloud
    //var BASE_URL = "../first1me2/index.php";                                  //home
    
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
});