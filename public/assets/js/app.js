$(document).ready(function() {
    $(".menu-icon").on("click", function() {
          $("nav ul").toggleClass("showing");
    });

    $('body').removeClass('fade-out');


    $('.custom-file-input').on("change", function () {
        var fileLabel = $(this).next('.custom-file-label');
        var files = $(this)[0].files;
        var fileNames = '';
        if (files.length > 1) {
            for (i = 0; i < files.length; i++) {
                fileNames += files[i].name + ' ; ';
            }
            fileLabel.html(files.length + ' Files selected | ' + fileNames);
        }
        else if (files.length == 1) {
            fileLabel.html(files[0].name);
        }
    });
});

$(window).on("load" ,function () {
    $(".loader-wrapper").fadeOut("slow");
});

// Scrolling Effect
$(window).on("scroll", function() {
    if($(window).scrollTop()) {
          $('nav').addClass('black');
    }

    else {
          $('nav').removeClass('black');
    }
})


var amountScrolled = 200;

$(window).scroll(function () {
    if ($(window).scrollTop() > amountScrolled) {
        $('a.backToTop').fadeIn('slow');
    } else {
        $('a.backToTop').fadeOut('slow');
    }
});

$('a.backToTop').click(function () {
    $('html, body').animate({
        scrollTop: 0
    }, 700);
    return false;
});

