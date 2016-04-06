/**
 * @file
 * A Backbone views.
 */

;(function ($, settings, Backbone) {
  "use strict";

  // View for adding new elements on add/edit page.
  settings.GridstackField.Views.GridField = Backbone.View.extend({
    rootElement: '.gridstack-items',
    el: '.gridstack-items',

    field: '.field-type-gridstack-field',

    initialize: function () {
      this.listenTo(this.collection, 'remove', this.updateJsonField);
      this.render();
    },

    updateJsonField: function (collection) {
      $(this.field).find('input[name$="[json]"]').val(JSON.stringify(this.collection));
    },

    render: function () {
      var item = new settings.GridstackField.Views.GridFieldItem({model: this.model, collection: this.collection});
      this.$el.append(item.render().el);
      this.updateJsonField();
      $(this.field).find('input[name$="[gridstack_group][gridstack_autocomplete]"]').val('');

      return this;
    }
  });


  // View for Gridstack items.
  settings.GridstackField.Views.GridFieldItems = Backbone.View.extend({
    className: 'grid-stack',
    tagName: 'div',
    rootElement: '.gridstack-items',

    initialize: function () {
      this.render();
    },

    render: function () {
      _.each(this.collection, function (element, index, list) {
        var item = new settings.GridstackField.Views.GridFieldItem({model: element});
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
      var self = this;

      $.ajax({
        url: href,
        success: function (data) {
          var grid = $('.grid-stack').data('gridstack');
          self.$el.append(data);
          self.$el.find('.node').wrap('<div class="grid-stack-item-content">')
          self.$el.find('.grid-stack-item-content').prepend('<button class="remove-item">REMOVE</button>');
           //Add events to button here because 'clean' drupal doesn't support 'on' method.
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

      return this;
    }
  });

}(jQuery, Drupal.settings, Backbone));
