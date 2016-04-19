/**
 * @file
 * Provides GridStack admin loader.
 */

(function ($, settings, Backbone) {

  'use strict';

  /**
   * Backbone collections.
   */
  settings.GridstackField.Collections.GridItems = Backbone.Collection.extend({
    addItems: function (data) {
      var model;
      _.each(data, function (el) {
        model = new settings.GridstackField.Models.GridItem({
          id: el.id,
          height: el.height,
          positionX: el.positionX,
          positionY: el.positionY,
          width: el.width
        });
        this.add(model);
      }, this);

      new settings.GridstackField.Views.GridFieldItems({collection: this});
    },

    addItem: function (nid) {
      var itemModel = new settings.GridstackField.Models.GridItem({id: nid});
      this.add(itemModel);
      new settings.GridstackField.Views.GridField({model: itemModel, collection: this});
    }
  });

}(jQuery, Drupal.settings, Backbone));
