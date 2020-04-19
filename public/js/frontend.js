//SMOOTH SCROLL-TO-TOP ACTION
$('a.btn-scroll-top').on('click', e => {
  $('a.btn-scroll-top').hide();
  $("html, body").animate({
    scrollTop: $("#top").offset().top
  }, 500);
});

//TOGGLE SCROLL-TO-TOP BUTTON
$('a.btn-scroll-top').hide();
$(window).scroll(() => {
  let scrollTop = $(window).scrollTop();

  if (scrollTop > 200) {
    $('a.btn-scroll-top').fadeIn();
  } else {
    $('a.btn-scroll-top').fadeOut();
  }
});
