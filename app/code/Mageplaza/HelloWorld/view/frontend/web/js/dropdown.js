define([
    'jquery',
    'uiComponent',
    'ko',
    'underscore'
], function (
    $,
    Component,
    ko,
    _
) {
    'use strict';

    return Component.extend({
        availableOptions: ko.observableArray([]),
        availableOptions2: ko.observableArray([]),
        selectedItem: ko.observable(),
        /**
         * Init component
         */
        initialize: function (config) {
            this._super();
            var self = this;
            _.each(config.dropdown, function (dropdownValue, key) {
                var value = dropdownValue.value;
                var label = dropdownValue.label;
                var option = {
                    'value': value,
                    'label': label
                };
                self.availableOptions.push(option);
            });
            self.selectedItem.subscribe(function(value) {
                if (value) {
                    self.availableOptions2([]);
                    _.each(config.dropdown, function (dropdownValue, key) {
                        if (dropdownValue.value == value) {
                            _.each(dropdownValue.dropdown, function (dropdown1Value) {
                                var value = dropdown1Value.value;
                                var label = dropdown1Value.label;
                                var option = {
                                    'value': value,
                                    'label': label
                                };
                                self.availableOptions2.push(option);
                            });
                        }
                    });
                } else {
                    self.availableOptions2([]);
                }
            });
            return this;
        }
    });
});