jQuery(document).ready(function ($) {
  data = { url: document.URL };

  $.ajax({
    type: "POST",
    url:  window.location.origin + "/wp-content/themes/canvas/track.php",
    data: data,
  });
});