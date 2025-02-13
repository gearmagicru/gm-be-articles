/*!
 * Контроллер представления виджета формы.
 * Модуль "Материалы сайта".
 * Copyright 2015 Вeб-студия GearMagic. Anton Tivonenko <anton.tivonenko@gmail.com>
 * https://gearmagic.ru/license/
 */

Ext.define('Gm.be.articles.FormController', {
    extend: 'Gm.view.form.PanelController',
    alias: 'controller.gm-be-articles-form',

    /**
     * Изменение значения флажка "только для робота Google".
     * @param {Ext.form.field.Checkbox} me
     * @param {Object} newValue
     * @param {Object} oldValue
     * @param {Object} eOpts
     */
    onChangeMetaGoogle: function (me, newValue, oldValue, eOpts) {
        let cmp = this.getViewCmp('metagoogle'); // gm-articles-form__metagoogle
        cmp.setDisabled(!newValue);
    },

    /**
     * Изменение значения флажка "только для робота Яндекс".
     * @param {Ext.form.field.Checkbox} me
     * @param {Object} newValue
     * @param {Object} oldValue
     * @param {Object} eOpts
     */
    onChangeMetaYandex: function (me, newValue, oldValue, eOpts) {
        let cmp = this.getViewCmp('metayandex'); // gm-articles-form__metayandex
        cmp.setDisabled(!newValue);
    },

    /**
     * Изменение значения флажка "для всех роботов".
     * @param {Ext.form.field.Checkbox} me
     * @param {Object} newValue
     * @param {Object} oldValue
     * @param {Object} eOpts
     */
    onChangeMetaRobots: function (me, newValue, oldValue, eOpts) {
        let cmp = this.getViewCmp('metarobots'); // gm-articles-form__metarobots
        cmp.setDisabled(!newValue);
    }
});