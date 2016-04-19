/**
 * @file
 * Provides GridStack admin loader.
 */

(function ($, settings, Backbone) {
  'use strict';

  // View for adding new elements on add/edit page.
  settings.GridstackField.Views.GridField = Backbone.View.extend({
    rootElement: '.gridstack-items',
    el: '.grid-stack',

    field: '.field-type-gridstack-field',

    events: {
      change: 'changeItems'
    },

    initialize: function () {
      this.listenTo(this.collection, 'remove', this.updateJsonField);
      this.render();
    },

    changeItems: function (event, items) {
      var self = this;
      $(items).each(function () {
        var model = self.collection.where({id: this.el.data('id').toString()});
        if (model[0]) {
          model[0].set({height: this.height, positionX: this.x, positionY: this.y, width: this.width});
        }
      });
      this.updateJsonField();
    },
    updateJsonField: function (collection) {
      $(this.field).find('input[name$="[json]"]').val(JSON.stringify(this.collection));
    },

    render: function () {
      var item = new settings.GridstackField.Views.GridFieldItem({model: this.model, collection: this.collection});
      var x = this.model.toJSON().positionX;
      var y = this.model.toJSON().positionY;
      var width = this.model.toJSON().width;
      var height = this.model.toJSON().height;
      this.updateJsonField();
      var grid = $('.grid-stack').data('gridstack');
      if (grid) {
        grid.addWidget(item.render().el, x, y, width, height, true);
      }
      else {
        this.$el.append(item.render().el);
        // Gradstack plugin.
        var options = Drupal.settings.gridstack_field.row_setting;
        $('.gridstack-items .grid-stack').gridstack(options);
      }
      $(this.field).find('input[name$="[gridstack_group][gridstack_autocomplete]"]').val('');

      return this;
    }
  });
  // View for Gridstack items.
  settings.GridstackField.Views.GridFieldItems = Backbone.View.extend({
    className: 'grid-stack',
    tagName: 'div',
    rootElement: '.gridstack-items',
    field: '.field-type-gridstack-field',

    events: {
      change: 'changeItems'
    },

    initialize: function () {
      this.listenTo(this.collection, 'remove', this.updateJsonField);
      this.render();
    },

    updateJsonField: function (collection) {
      $(this.field).find('input[name$="[json]"]').val(JSON.stringify(this.collection));
    },

    changeItems: function (event, items) {
      var self = this;
      $(items).each(function () {
        var model = self.collection.where({id: this.el.data('id').toString()});
        if (model[0]) {
          model[0].set({height: this.height, positionX: this.x, positionY: this.y, width: this.width});
        }
      });
      this.updateJsonField();
    },

    render: function () {
      var self = this;
      _.each(this.collection.models, function (element, index, list) {
        var item = new settings.GridstackField.Views.GridFieldItem({model: element, collection: self.collection});
        this.$el.append(item.render().el);
      }, this);

      $(this.rootElement).html(this.$el);
      $('.field-type-gridstack-field').find('.field-item').html(this.$el);

      return this;
    }
  });

  // View for single Gridstack items.
  settings.GridstackField.Views.GridFieldItem = Backbone.View.extend({
    className: 'grid-stack-item',
    tagName: 'div',

    removeItem: function () {
      this.collection.remove(this.model);
      this.remove();
    },

    render: function () {

      var href = this.model.url;
      var x = this.model.toJSON().positionX;
      var y = this.model.toJSON().positionY;
      var width = this.model.toJSON().width;
      var height = this.model.toJSON().height;
      var id = this.model.toJSON().id;
      var self = this;
      var $body = $('body');
      self.$el.append('<div class="grid-stack-item-content"></div>');

      $.ajax({
        url: href,
        success: function (data) {
          self.$el.find('.grid-stack-item-content').append(data);
          if ($body.hasClass('page-node-edit') || $body.hasClass('page-node-add')) {
            self.$el.find('.grid-stack-item-content').prepend('<button class="remove-item">REMOVE</button>');
          }
          // Add events to button here because 'clean' drupal doesn't support 'on' method.
          self.$el.delegate('.remove-item', 'click', function (e) {
            e.preventDefault();
            self.removeItem();
          });
        }
      });
      this.$el.attr('data-gs-x', x);
      this.$el.attr('data-gs-y', y);
      this.$el.attr('data-gs-width', width);
      this.$el.attr('data-gs-height', height);
      this.$el.attr('data-id', id);

      return this;
    }
  });

}(jQuery, Drupal.settings, Backbone));
