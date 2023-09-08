$(document).ready(function() {
    $("#upload-pop-up").click(function() {
        $(".upload-pop-up").show(200);
    });
    $("#close-upload-pop-up").click(function() {
        $(".upload-pop-up").hide(200);
    });
});

$(document).ready(function(e){
    // Submit form data via Ajax
    $("#fupForm").on('submit', function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'server.php',
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
                $('.submitBtn').attr("disabled","disabled");
                $('#fupForm').css("opacity",".5");
            },
            success: function(response){
                $('.statusMsg').html('');
                if(response.status == 1){
                    $('#fupForm')[0].reset();
                    $('.statusMsg').html('<p style="color: gray; font-size: 13px;">'+response.message+'</p>');
                }else{
                    $('.statusMsg').html('<p style="color: gray; font-size: 13px;>'+response.message+'</p>');
                }
                $('#fupForm').css("opacity","");
                $(".submitBtn").removeAttr("disabled");
            }
        });
    });
});

// File type validation
$("#file").change(function() {
    var file = this.files[0];
    var fileType = file.type;
    var match = ['application/pdf', 'application/msword', 'application/vnd.ms-office', 'image/jpeg', 'image/png', 'image/jpg'];
    if(!((fileType == match[0]) || (fileType == match[1]) || (fileType == match[2]) || (fileType == match[3]) || (fileType == match[4]) || (fileType == match[5]))){
        alert('Sorry, only PDF, DOC, JPG, JPEG, & PNG files are allowed to upload.');
        $("#file").val('');
        return false;
    }
});

$(document).ready(function(){
    $("#user_search").keyup(function() {
        var user_search = $('#user_search').val();
        var filter_option = $('#filter_option').val();
        $.ajax({
            url: "server.php",
            method: "POST",
            data: { 
                user_search:user_search,
                filter_option:filter_option
            },
            dataType: "text",
            success: function(data)
            {
                $(".body").html(data);
                $(".view-document").html(data);
                $(".update-document").html(data);
            }
        })
    });
});

$(document).ready(function(){
    $("#admin_search").keyup(function() {
        var admin_search = $('#admin_search').val();
        var filter_option = $('#filter_option').val();
        $.ajax({
            url: "server.php",
            method: "POST",
            data: { 
                admin_search:admin_search,
                filter_option:filter_option
            },
            dataType: "text",
            success: function(data)
            {
                $(".body").html(data);
                $(".view-document").html(data);
                $(".update-document").html(data);
            }
        })
    });
});

$(document).ready(function() {
    $(".login-box").click(function() {
        var panel = document.getElementById("log-in");
        if (panel.style.display == "block") { $(".log-in").fadeOut(150); } 
        else { $(".log-in").fadeIn(150); }
    });
});

$(document).ready(function(e){
    // Submit form data via Ajax
    $("#logAUTH").on('submit', function(e){
        var username = $("#username").val();
        var password = $("#password").val();
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'server.php',
            data: {
                username:username,
                password:password
            },
            success: function(response){
                $('.responseError').html(response);
            }
        });
    });
});

$(document).ready(function(){
    // Submit form data via Ajax
    $(".log-out").click(function() {
        var destroy_session = 0;
        $.ajax({
            type: 'POST',
            url: 'server.php',
            data: { destroy_session:destroy_session },
            success: function() { window.location.href = "guest"; }
        });
    });
});

$(document).ready(function(e){
    // Submit form data via Ajax
    $("#updateForm").on('submit', function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'server.php',
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            success: function(response){
                $('.responseMsg').show(200);
            }
        });
    });
});

function printPageArea(areaID)
{
    var printContent = document.getElementById(areaID).innerHTML;
    var originalContent = document.body.innerHTML;
    document.body.innerHTML = printContent;
    window.print();
    document.body.innerHTML = originalContent;
}