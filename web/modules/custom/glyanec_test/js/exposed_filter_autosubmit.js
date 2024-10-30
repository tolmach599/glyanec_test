(function ($, Drupal, once) {
  Drupal.behaviors.exposedFilterAutosubmit = {
    attach: function (context, settings) {
      $(once('exposed-filter-autosubmit', 'form#views-exposed-form-glyanec-autosubmit-page-1', context)).each(function () {
        var $form = $(this);

        $form.on('submit', function (e) {
          e.preventDefault();
        });

        $form.find('input, select').on('change', function () {
          $form.find('input[type="submit"]').trigger('click');
        });
      });
    }
  };
})(jQuery, Drupal, once);
