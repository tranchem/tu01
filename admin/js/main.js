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