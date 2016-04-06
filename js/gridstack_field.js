;(function ($, Drupal, Backbone, settings) {
  Drupal.behaviors.gridstackField = {
    attach: function (context, settings) {

      var collection,
          $body = $('body'),
          $fieldGridstack = $('.field-type-gridstack-field'),
          input,
          data;



      if ($body.hasClass('page-node-edit')) {
        input = ($fieldGridstack.find('input[name$="[json]"]').val() !== '') ? $fieldGridstack.find('input[name$="[json]"]').val() : '[]',
        data = JSON.parse(input);
      }
      else {
        data = '';
      }



      if (data.length > 0) {
        collection = new settings.GridstackField.Collections.GridItems(data);
      }
      else {
        collection = new settings.GridstackField.Collections.GridItems();
      }



      $fieldGridstack.find('.form-button').on('click', function (e) {
        e.preventDefault();
        var localId = $fieldGridstack.find('.form-text').val();
        collection.addItem(localId);
        return false;
      });
    }
  }

  $(document).ready(function () {
    var collection,
        input = '',
        data,
        $body = $('body'),
        $fieldGridstack = $('.field-type-gridstack-field'),
        options;

    // To do.
    if (!$body.hasClass('page-node-edit') && $fieldGridstack.length !== 0) {
      collection = new settings.GridstackField.Collections.GridItems();
      input = ($fieldGridstack.find('.field-item').text() !== '') ? $fieldGridstack.find('.field-item').text() : '[]';
    }

    // To do.
    if ($body.hasClass('page-node-edit') && $fieldGridstack.length !== 0) {
      collection = new settings.GridstackField.Collections.GridItems();
      input = ($fieldGridstack.find('input[name$="[json]"]').val() !== '') ? $fieldGridstack.find('input[name$="[json]"]').val() : '[]';

      // Gradstack plugin.
      options = {
        cell_height: 278,
        vertical_margin: 10,
        animate: true,
        width: 4
      };
      $('.gridstack-items .grid-stack').gridstack(options);
    }

    // TODO: need if.
    data = JSON.parse(input);

    // To do.
    if (data.length > 0) {
      collection.addItems(data);
    }
  });
})(jQuery, Drupal, Backbone, Drupal.settings);
