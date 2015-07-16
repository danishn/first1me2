$(document).ready(function(){
    var BASE_URL = "../index.php";                                              //cloud
    //var BASE_URL = "../first1me2/index.php";                                  //home
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