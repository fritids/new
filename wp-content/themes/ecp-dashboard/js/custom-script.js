/*==========================
TOUCHY SCROLL FOR SIDEBAR
==========================*/
jQuery("#sidebar").niceScroll({
    cursorcolor: "#2f2e2e",
    cursoropacitymax: 0.7,
    boxzoom: false,
    touchbehavior: true
});

jQuery(function () {
	jQuery('.accordion_mnu').initMenu();
});