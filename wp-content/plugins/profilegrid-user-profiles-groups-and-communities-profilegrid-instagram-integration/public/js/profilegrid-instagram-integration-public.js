function pg_instagram_disconnect(a)
{
    var uid = jQuery('#pg_instagram_uid').val();
    jQuery('#pg-ig-preload').html('<div class="pm-loader"></div>');
    var pmDomColor = jQuery(".pmagic").children("a").css('color');
    jQuery(".pm-loader").css('border-top-color', pmDomColor);

    params = {action: 'pg_instagram_disconnect',uid:uid}
    jQuery.post(pm_ajax_object.ajax_url, params, function(response) 
    {
        window.location.hash = '#pg_instagram_integration_tab_content';
        window.location.reload(true);
    });	
}

jQuery(document).ready(function($) {
    var a = jQuery('#pg-ig-show_photos .pg-insta-column');
    for (var i = 0; i < a.length; i += 9) {
        a.slice(i, i+9).wrapAll('<div class="pg-insta-column-wrap"></div>');
    }
});

var slideIndex = 1;
showDivs(slideIndex);

function plusDivs(n) {
  showDivs(slideIndex += n);
}

function showDivs(n) {
  var i;
  var x = jQuery('.pg-insta-column-wrap');
  if (n > x.length) {slideIndex = 1}
  if (n < 1) {slideIndex = x.length}
  for (i = 0; i < x.length; i++) {
    jQuery('.pg-insta-column-wrap').eq(i).hide();
  }
  jQuery('.pg-insta-column-wrap').eq(slideIndex-1).fadeIn(1000); 
//  alert(slideIndex);
}


jQuery(document).ready(function() {
    jQuery('#pg-ig-show_photos .pg-insta-column a.pg-insta-photo').click(function(e) {
        e.preventDefault();
        jQuery('.pg-insta-photo-modal').hide();
        jQuery(this).parent('.pg-insta-column').children('.pg-insta-photo-modal, .pg-insta-photo-modal-overlay').show();
    });
    
    jQuery('.pg-insta-photo-modal-overlay, .pg-insta-photo-modal-close').click(function(e) {
        e.preventDefault();
        jQuery('.pg-insta-photo-modal').hide();
    });
});

function openModal() {
  document.getElementById("pg-insta-modal").style.display = "block";
}

function closeModal() {
  document.getElementById("pg-insta-modal").style.display = "none";
}

var slideIndex = 1;

// ✅ Only call showSlides if slides exist
if (document.getElementsByClassName("pg-insta-photo-slides").length > 0) {
    showSlides(slideIndex);
}

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("pg-insta-photo-slides");
  var dots = document.getElementsByClassName("demo");
  var captionText = document.getElementById("caption");

  // ✅ Guard: if there are no slides, do nothing
  if (!slides || slides.length === 0) {
      return;
  }

  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
/*  dots[slideIndex-1].className += " active"; 
  captionText.innerHTML = dots[slideIndex-1].alt;*/
}
