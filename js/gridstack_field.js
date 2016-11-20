/**
 * @file
 * Provides GridStack loaders.
 */

;(function ($, Drupal, Backbone, settings) {
  'use strict';

  /**
   * Implements grid and backbone collections on node edit page.
   */
  Drupal.behaviors.gridstackField = {
    attach: function (context, settings) {
      var collection;
      //var $body = $('body');
      //var $node_form = $('.node-form');
      var $node_edit_form = $('form[id$="edit-form"]');
      var fieldGridstack = $('.field--type-gridstack-field');
      var input;
      var data;
      console.log($node_edit_form.length);

      // Prepare data from json from field on node edit page.
      //if ($body.hasClass('page-node-edit')) {
      //  input = (fieldGridstack.find('input[name$="[json]"]').val() !== '') ? fieldGridstack.find('input[name$="[json]"]').val() : '[]';
      //  data = JSON.parse(input);
      //}
      //else {
      //  data = '';
      //}
      if ($node_edit_form.length) {
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
      fieldGridstack.find('.form-submit').on('click', function (e) {
        e.preventDefault();
        console.log('gggggggggggggg');
        var localId = fieldGridstack.find('.form-autocomplete').val();
        //var regexp = /\([0-9]+\)$/g;
        //var regexp = /(?:\()[0-9]+(?:\))$/g;
        var regexp = /.+\s\(([\w.]+)\)/;
        console.log(localId);
        localId = _.last(regexp.exec(localId));
        console.log(localId);
        collection.addItem(localId);
        return false;
      });
    }
  };

  /**
   * Implements grid and backbone collections on node view and edit page.
   */
  $(document).ready(function () {
    var collection;
    var input = '';
    var data;
    //var $body = $('body');
    var $node_form = $('.node-form');
    var $node_edit_form = $('form[id$="edit-form"]');
    var fieldGridstack = $('.field--type-gridstack-field');
    var $node_edit = false;
    var options = settings.gridstack_field.settings;
    // Create backbone collection and get data from field on node edit page.
    if (!$node_edit_form.length && fieldGridstack.length !== 0) {
      console.log('NODE NE EDIT');
      collection = new settings.GridstackField.Collections.GridItems();
      input = (fieldGridstack.find('.field__item').text() !== '') ? fieldGridstack.find('.field__item').text() : '[]';
    }

    // Create backbone collection and get data from field on node view and add pages.
    if ($node_edit_form.length && fieldGridstack.length !== 0) {
      console.log('NODE EDIT');
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
      console.log('EDIT');
      $('.gridstack-items .grid-stack').gridstack(options);
    }
    else if (!$node_form.length) {
      console.log('VIEW');
      options.disableDrag = true;
      options.disableResize = true;
      $('.grid-stack').gridstack(options);
    }
  });
})(jQuery, Drupal, Backbone, drupalSettings);
