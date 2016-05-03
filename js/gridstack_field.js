/**
 * @file
 * Provides GridStack loaders.
 */

(function ($, Drupal, Backbone, settings) {
  'use strict';

  /**
   * Implements grid and backbone collections on node edit page.
   */
  Drupal.behaviors.gridstackField = {
    attach: function (context, settings) {
      var collection;
      var $body = $('body');
      var fieldGridstack = $('.field-type-gridstack-field');
      var input;
      var data;

      // Prepare data from json from field on node edit page.
      if ($body.hasClass('page-node-edit')) {
        input = (fieldGridstack.find('input[name$="[json]"]').val() !== '') ? fieldGridstack.find('input[name$="[json]"]').val() : '[]';
        data = JSON.parse(input);
      }
      else {
        data = '';
      }

      // Create backbone colletion of items.
      if (data.length > 0) {
        collection = new settings.GridstackField.Collections.GridItems(data);
      }
      else {
        // Just create new empty collection if we haven't data.
        collection = new settings.GridstackField.Collections.GridItems();
      }

      // Add new item into collection and grid.
      fieldGridstack.find('.form-button').on('click', function (e) {
        e.preventDefault();
        var localId = fieldGridstack.find('.form-text').val();
        collection.addItem(localId);
        return false;
      });
    }
  };

  /**
   * Implements grid and backbone collections on node view and edit page.
   */
  $(document).ready(function () {
    console.log('ROBEEEEEEEEE');
    var collection;
    var input = '';
    var data;
    var $body = $('body');
    var fieldGridstack = $('.field-type-gridstack-field');
    var $node_edit = false;
    var options = Drupal.settings.gridstack_field.row_setting;
    // Create backbone collection and get data from field on node edit page.
    if (!$body.hasClass('page-node-edit') && fieldGridstack.length !== 0) {
      collection = new settings.GridstackField.Collections.GridItems();
      input = (fieldGridstack.find('.field-item').text() !== '') ? fieldGridstack.find('.field-item').text() : '[]';
    }

    // Create backbone collection and get data from field on node view and add pages.
    if ($body.hasClass('page-node-edit') && fieldGridstack.length !== 0) {
      $node_edit = true;
      collection = new settings.GridstackField.Collections.GridItems();
      input = (fieldGridstack.find('input[name$="[json]"]').val() !== '') ? fieldGridstack.find('input[name$="[json]"]').val() : '[]';
    }

    data = JSON.parse(input);

    // If items exits pass them into backbone collection.
    if (data.length > 0) {
      collection.addItems(data);
    }

    // Implements gridstack plugin.
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
