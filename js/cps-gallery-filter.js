jQuery(document).ready(function ($) {
  function loadGalleryItems(category_id, paged) {
    var data = {
      action: "cps_gallery_filter",
      category_id: category_id,
      paged: paged,
    };

    $.ajax({
      url: cps_gallery_ajax.ajax_url,
      type: "POST",
      data: data,
      beforeSend: function () {
        $(".filter-gallery-item-container").append(
          '<div class="loading"></div>'
        );
      },
      success: function (response) {
        $("#gallery-container").html(response);
        lightbox.init();
        $(".loading").remove();
        $(".pagination a").removeClass("current");
        $(".pagination a[data-page=" + paged + "]").addClass("current");
      },
    });
  }

  // Get default category from shortcode
  var defaultCategory = $("#gallery-container").data("cat-id") || "";

  // Handle category filter click
  $(document).on("click", ".filter-gallery-nav li", function () {
    $(".filter-gallery-nav li").removeClass("nav-active");
    $(this).addClass("nav-active");

    var category_id = $(this).data("gallery-cat-id") || "";
    loadGalleryItems(category_id, 1);
  });

  // Handle pagination click
  $(document).on("click", ".pagination a", function (e) {
    e.preventDefault();
    var paged = $(this).data("page");
    var category_id =
      $(".filter-gallery-nav .nav-active").data("gallery-cat-id") ||
      defaultCategory;

    loadGalleryItems(category_id, paged);
  });
});
