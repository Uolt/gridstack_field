/**
 * @file
 * Provides GridStack admin loader.
 */

(function ($, Drupal, Backbone, settings) {

  'use strict';

  Drupal.behaviors.gridstackField = {
    attach: function (context, settings) {
      var collection;
      var $body = $('body');
      var fieldGridstack = $('.field-type-gridstack-field');
      var input;
      var data;
      if ($body.hasClass('page-node-edit')) {
        input = (fieldGridstack.find('input[name$="[json]"]').val() !== '') ? fieldGridstack.find('input[name$="[json]"]').val() : '[]';
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
      fieldGridstack.find('.form-button').on('click', function (e) {
        e.preventDefault();
        var localId = fieldGridstack.find('.form-text').val();
        collection.addItem(localId);
        return false;
      });
    }
  };

  $(document).ready(function () {
    var collection;
    var input = '';
    var data;
    var $body = $('body');
    var fieldGridstack = $('.field-type-gridstack-field');
    var $node_edit = false;
    var options = Drupal.settings.gridstack_field.row_setting;
    // To do.
    if (!$body.hasClass('page-node-edit') && fieldGridstack.length !== 0) {
      collection = new settings.GridstackField.Collections.GridItems();
      input = (fieldGridstack.find('.field-item').text() !== '') ? fieldGridstack.find('.field-item').text() : '[]';
    }

    // To do.
    if ($body.hasClass('page-node-edit') && fieldGridstack.length !== 0) {
      $node_edit = true;
      collection = new settings.GridstackField.Collections.GridItems();
      input = (fieldGridstack.find('input[name$="[json]"]').val() !== '') ? fieldGridstack.find('input[name$="[json]"]').val() : '[]';
    }
    data = JSON.parse(input);

    // To do.
    if (data.length > 0) {
      collection.addItems(data);

    }
    if ($node_edit === true) {
      $('.gridstack-items .grid-stack').gridstack(options);
    }
    else if (!($body.hasClass('page-node-add'))) {
      options.disableDrag = true;
      options.disableResize = true;
      $('.grid-stack').gridstack(options);
    }
  });
})(jQuery, Drupal, Backbone, Drupal.settings);
