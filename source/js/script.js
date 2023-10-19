var menuIcon = document.querySelector(".menu-icon");
var sidebar = document.querySelector(".sidebar");
var container = document.querySelector(".container");

menuIcon.onclick = function() {
    sidebar.classList.toggle("small-sidebar");
    container.classList.toggle("large-container");
}

function viewUpdate(id) {
    $.ajax({
        method : "get",
        url: './api/VideoController.php?action=view_update&vid=' + id,
        data : null
    })
}
$(window).resize(function() {
    if($(window).width() <= 900) {
        $(".sidebar").addClass("small-sidebar")
    }
    else {
        $(".sidebar").removeClass("small-sidebar")
    }
})
