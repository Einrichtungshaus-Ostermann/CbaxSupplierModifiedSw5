//{block name="backend/supplier/controller/main"}
//{$smarty.block.parent}
Ext.define('Shopware.apps.Supplier.controller.Main-SupplierModified', {
	override: 'Shopware.apps.Supplier.controller.Main',

	constructor: function () {
		var me = this;

		me.refs = (me.refs || []).concat([{
			ref: 'editWindow',
			selector: 'supplier-main-edit'
		}]);
		me.callParent(arguments);
	},

	/**
	 * Creates the necessary event listener for this
	 * specific controller and opens a new Ext.window.Window
	 * to display the sub-application
	 *
	 * beware: there is some controller logic in edit.js view.
	 *          This is because of the special handling of the
	 *          upload method.
	 * @return void
	 */
	init: function () {
		var me = this;

		me.control({
			'supplier-main-edit':{
				recordloaded : me.onRecordLoaded
			}
		});

		Shopware.app.Application.on('supplier-save-successfully', me.onSupplierSaveSuccessfully);

		me.callParent(arguments);
	},

	/**
	 * Reacts if the event recordloaded is fired and hides or shows the template selection based
	 * on the parent ID of the loaded record.
	 *
	 * @event recordloaded
	 * @param record [Ext.data.Model]
	 * @return void
	 */
	onRecordLoaded: function (record) {
		var me = this,
			editWindow = me.getEditWindow(),
			cbaxSupplierIsPremiumField = editWindow.down('checkbox[name=cbaxSupplierIsPremium]'),
			cbaxSupplierBannerField = editWindow.down('mediaselectionfield[name=cbaxSupplierBanner]'),
			cbaxSupplierIsHiddenField = editWindow.down('checkbox[name=cbaxSupplierIsHidden]');
			cbaxSupplierUrlField = editWindow.down('textfield[name=cbaxSupplierUrl]');

		if (record.getId()) {
			Ext.Ajax.request({
				url: '{url controller=AttributeData action=loadData}',
				params: {
					_foreignKey: record.getId(),
					_table: 's_articles_supplier_attributes'
				},
				success: function (responseData) {
					var response = Ext.JSON.decode(responseData.responseText),
						isPremium = response.data['__attribute_cbax_supplier_is_premium'],
						banner = response.data['__attribute_cbax_supplier_banner'],
						isHidden = response.data['__attribute_cbax_supplier_is_hidden'];
						url = response.data['__attribute_cbax_supplier_url'];

					if (isPremium !== null && isPremium !== undefined) {
						cbaxSupplierIsPremiumField.setValue(isPremium);
					}
					if (banner !== null && banner !== undefined) {
						cbaxSupplierBannerField.setValue(banner);
					}
					if (isHidden !== null && isHidden !== undefined) {
						cbaxSupplierIsHiddenField.setValue(isHidden);
					}
					if (url !== null && url !== undefined) {
						cbaxSupplierUrlField.setValue(url);
					}
				}
			});
		}
	},

	/**
	 * This method will be called if the user hits the save button either in the edit window or
	 * in the add supplier window
	 *
	 * @param btn Ext.button.Button
	 * @return void
	 */
	onSupplierSave: function(btn) {
		var me = this,
			win = btn.up('window'), // Get Window
			form = win.down('form'), // Get the DOM Form used in that window
			formBasis = form.getForm(), // Extract the form from the DOM
			cbaxSupplierIsPremiumField = form.down('checkbox[name=cbaxSupplierIsPremium]'),
			cbaxSupplierBannerField = form.down('mediaselectionfield[name=cbaxSupplierBanner]'),
			cbaxSupplierIsHiddenField = form.down('checkbox[name=cbaxSupplierIsHidden]'),
			cbaxSupplierUrlField = form.down('textfield[name=cbaxSupplierUrl]'),
			record = form.getRecord();

		if (!(record instanceof Ext.data.Model)) {
			me.callParent(arguments);
			return;
		}

		formBasis.updateRecord(record);

		if (formBasis.isValid()) {
			Ext.Ajax.request({
				method: 'POST',
				url: '{url controller=AttributeData action=saveData}',
				params: {
					_foreignKey: record.getId(),
					_table: 's_articles_supplier_attributes',
					__attribute_cbax_supplier_is_premium: (cbaxSupplierIsPremiumField.getValue()) ? 1 : 0,
					__attribute_cbax_supplier_banner: cbaxSupplierBannerField.getValue(),
					__attribute_cbax_supplier_is_hidden: (cbaxSupplierIsHiddenField.getValue()) ? 1 : 0,
					__attribute_cbax_supplier_url: cbaxSupplierUrlField.getValue()
				}
			});
		}

		me.callParent(arguments);
	},

	onSupplierSaveSuccessfully: function (view, record, form) {
		var formBasis = form.getForm(),
			cbaxSupplierIsPremiumField = form.down('checkbox[name=cbaxSupplierIsPremium]'),
			cbaxSupplierBannerField = form.down('mediaselectionfield[name=cbaxSupplierBanner]'),
			cbaxSupplierIsHiddenField = form.down('checkbox[name=cbaxSupplierIsHidden]'),
			cbaxSupplierUrlField = form.down('textfield[name=cbaxSupplierUrl]');

		if (!(record instanceof Ext.data.Model)) {
			return;
		}

		if (formBasis.isValid()) {
			Ext.Ajax.request({
				method: 'POST',
				url: '{url controller=AttributeData action=saveData}',
				params: {
					_foreignKey: record.getId(),
					_table: 's_articles_supplier_attributes',
					__attribute_cbax_supplier_is_premium: (cbaxSupplierIsPremiumField.getValue()) ? 1 : 0,
					__attribute_cbax_supplier_banner: cbaxSupplierBannerField.getValue(),
					__attribute_cbax_supplier_is_hidden: (cbaxSupplierIsHiddenField.getValue()) ? 1 : 0,
					__attribute_cbax_supplier_url: cbaxSupplierUrlField.getValue()
				}
			});
		}
	},

	/**
	 * Opens the Ext.window.window which displays
	 * the Ext.form.Panel to modify an existing supplier
	 *
	 * @event click
	 * @param [object]  view - The view. Is needed to get the right store
	 * @param [object]  item - The row which is affected
	 * @param [integer] rowIndex - The row number
	 * @return void
	 */
	onEditSupplier: function (view, item, rowIndex) {
		/*{if {acl_is_allowed privilege=update}}*/
		var store = view.getStore(),
			me = this,
			record = store.getAt(rowIndex),
			newStore = Ext.create('Shopware.apps.Supplier.store.Supplier');

		newStore.load({
			id: record.getId(),
			callback: function(records, operation, success) {
				if (success) {
					var newRecord = records[0];

					var editForm = me.getView('main.Edit').create({
						record: newRecord,
						mainStore: store
					}).show();

					editForm.fireEvent('recordloaded', newRecord);
				}
			}
		});
		/* {/if} */
	}
});
//{/block}
