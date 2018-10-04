$(document).ready(function() {
        $(window).bind("scroll", function() {
                parallax();
        });
});
// Function tạo parallax effect
// tốc độ được quy định bởi biến speed - cái này thay đổi theo ý muốn
// scrollPos lấy vị trí hiện tại của thanh cuộn
function parallax() {
        var scrollPos = $(window).scrollTop(),
                speed = 0.2;
        $(".bg").css("top", (0 - (scrollPos * speed)) + 'px');
}

window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        $('#topBtn').fadeIn(200);
    } 
    else {
        $('#topBtn').fadeOut(200);
    }
}

function topFunction() {
 
     $('html, body').animate({scrollTop:0}, 'slow');
}

function personAdd(selector) {
    var selectorId='#'+selector;
    var value = $(selectorId).text();
    var num = parseInt(value); 
    var newNum= num+1;
    $(selectorId).html(newNum);
}

function personSubtr(selector) {
    var selectorId='#'+selector;
    var value = $(selectorId).text();
    var num = parseInt(value);
    if(num>0)
    {
        var newNum= num-1;
    }
    $(selectorId).html(newNum);
}


$(".addBtn, .subtrBtn").click(function () {
    var adultVal = $("#adultNum").text();
    var adultNum = parseInt(adultVal);
    
    var childrenVal = $("#childrenNum").text();
    var childrenNum = parseInt(childrenVal);

    if(adultNum>0) {
        $("#ticketNo2").show(250);
    }
    else {
        $("#ticketNo2").hide(250);
        $("#ticketNo3").hide(500);
        $("#ticketNo4").hide(750);
        $(".placeCheck").prop("checked", false);
        $("#confirmPlaces").empty();
        $("#confirmPrice").empty();
    }
    
    $("#confirmAdult").text(adultNum);
    $("#confirmChildren").text(childrenNum);
    
    $('#adultCount').val(adultNum);
    $('#childrenCount').val(childrenNum);
});


$("#ticketDate").datepicker({
    onSelect: function() {

        $("#confirmPlaces").empty();
        $("#confirmPrice").empty();

        var txtDate = $(this).val();
        var currentDate = $.datepicker.formatDate('mm/dd/yy', new Date());
        
        if(txtDate.length>0 && txtDate > currentDate) {
            $("#date_err").text("");
            $("#confirmDate").text(txtDate);
            $("#ticketNo3").show(250);
            $("#ticketNo4").show(250);
            
        }
        else {
            $("#date_err").text("Your ticket must be in the future!");
            $("#ticketNo3").hide(250);
            $("#ticketNo4").hide(250);
            $(".placeCheck").prop("checked", false);
            $("#confirmPlaces").empty();
            $("#confirmPrice").empty();
        }
        
        $.ajax({
            url:'sites/tickethandle.php',
            type:'POST',
            data:{
                ticketDate: txtDate
            },
            success:function(data){
                $("#allPlaces").html(data);
                
            }
        });
    }
});


$("button[name=bookTicket]").click(function(e){
        
    var checkedPlaces = $('input:checkbox:checked.placeCheck').map(function () {
        return this.value;
    }).get();

    var adultVal = $("#adultNum").text();
    var adultNum = parseInt(adultVal);
    
    var noti='';
    if(checkedPlaces.length==0)
    {
        noti+= "You must select at least one zone!";
    }
    if(adultNum==0)
    {
        noti+="\nThere must be at least one adult in the ticket!";
    }
    if(noti!='')
    {
        alert(noti);
        e.preventDefault();
    }
});


$(document).on('change click', '.placeCheck, .addBtn, .subtrBtn', function() {
        
    var checkedPlaces = $('input:checkbox:checked.placeCheck').map(function () {
    return this.value;
    }).get();

    var adultVal = $("#adultNum").text();
    var adultNum = parseInt(adultVal);
    
    var childrenVal = $("#childrenNum").text();
    var childrenNum = parseInt(childrenVal);

    var txtDate = $("#ticketDate").val();

    if(checkedPlaces.length>0)
    {
        $.ajax({
            url:'sites/ticketplaces.php',
            type:'POST',
            data:{
                places: checkedPlaces,
                adultNum: adultNum,
                childrenNum: childrenNum,
                ticketDate: txtDate
            },
            success:function(data){
                var splitData=data.split('---');
                $("#confirmPlaces").html(splitData[0]);
                $("#confirmPrice").text(splitData[1]);
            }
        });
    }
    else
    {
        $("#confirmPlaces,#confirmPrice").empty();
    }
});


$(".cancelTicket").click(function(e){
    var confirmCancel=confirm("Do you really want to cancel this ticket?");
    
    if(confirmCancel==false)
    {
        e.preventDefault();
    }
});

    $(window).load(function(){
      $('.flexslider').flexslider({
        animation: "slide",
        controlNav: "thumbnails"
      });
    });

$("#urlSort").click(function() {
    var sortDate=$("#sortDate").val();
    var sortStatus=$("#sortStatus").val();
    var urlSort=$("#urlSort").attr("href");
    $("#urlSort").attr("href",urlSort+"&date="+sortDate+"&status="+sortStatus);
});




