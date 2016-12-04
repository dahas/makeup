$(document).ready(function () 
{
  /**
   * Show/hide the debug panel.
   */
  $("#dbg-handle").click(function () {
    if ($("#dbg-frame").is(":hidden")) {
      $("#dbg-frame").show(300);
      $("#dbg-handle i").attr("class", "fa fa-times");
    } else {
      $("#dbg-frame").slideUp();
      $("#dbg-handle i").attr("class", "fa fa-chevron-left");
    }
  });
});