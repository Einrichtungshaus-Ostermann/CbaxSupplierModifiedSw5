/*{namespace name=backend/plugins/supplier_modified/view/edit}*/
//{block name="backend/supplier/view/main/edit"}
//{$smarty.block.parent}
Ext.define('Shopware.apps.Supplier.view.main.Edit-SupplierModified', {
	override: 'Shopware.apps.Supplier.view.main.Edit',
	
	snippets: {
		titleFieldSet: {
			title: '{s name=columnIsPremium}Hersteller Professional{/s}'
		},
		isPremium: {
			label: '{s name=fieldLabelIsPremium}Top Hersteller{/s}'
		},
		isHidden: {
			label: '{s name=fieldLabelIsHidden}Hersteller ausblenden{/s}'
		},
		banner: {
			label: '{s name=fieldLabelBanner}Banner{/s}'
		}
	},

	/**
	 * Initialize the component
	 * @return void
	 */
	initComponent : function () {
		var me = this;

		/*{if $isShopwareVersion52}*/
		me.registerEvents();
		/*{/if}*/

		me.callParent(arguments);
	},

	/**
	 * Defines additional events which will be
	 * fired from the component
	 *
	 * @return void
	 */
	registerEvents: function() {
		var me = this;

		me.addEvents('recordloaded');
	},
	
	/**
	 * Return the entire form
	 *
	 *  @return Ext.form.Panel
	 */
	getInfoForm: function () {
		var me = this,
			form = me.callParent(arguments);

		form.insert(1, me.getSupplierModifiedFieldSet());

		return form;
	},

	getSupplierModifiedFieldSet: function () {
		var me = this;

		me.SupplierModifiedFieldSet = Ext.create('Ext.form.FieldSet', {
			alias:'widget.supplier-modified-field-set',
			cls: Ext.baseCSSPrefix + 'supplier-modified-field-set',
			defaults: {
				labelWidth: 155,
				anchor: '100%'
			},
			title: me.snippets.titleFieldSet.title,
			items: [{
				fieldLabel: me.snippets.isHidden.label,
				xtype: 'checkbox',
				/*{if $isShopwareVersion52}*/
				name: 'cbaxSupplierIsHidden',
				/*{else}*/
				name: 'attribute[cbaxSupplierIsHidden]',
				/*{/if}*/
				inputValue: true,
				uncheckedValue: false
			}, {
				fieldLabel: me.snippets.isPremium.label,
				xtype: 'checkbox',
				/*{if $isShopwareVersion52}*/
				name: 'cbaxSupplierIsPremium',
				/*{else}*/
				name: 'attribute[cbaxSupplierIsPremium]',
				/*{/if}*/
				inputValue: true,
				uncheckedValue: false
			}, {
				fieldLabel: me.snippets.banner.label,
				xtype: 'mediaselectionfield',
				/*{if $isShopwareVersion52}*/
				name: 'cbaxSupplierBanner',
				/*{else}*/
				name: 'attribute[cbaxSupplierBanner]',
				/*{/if}*/
				readOnly: false,
				multiSelect: false,
				validTypes: me.getAllowedExtensions(),
				validTypeErrorFunction: me.getExtensionErrorCallback()
			}]
		});

		return me.SupplierModifiedFieldSet;
	},

	/**
	 * Helper Method which returns the method which should be called if some selected image file has a wrong extension.
	 *
	 * @return string
	 */
	getExtensionErrorCallback :  function() {
		return 'onExtensionError';
	},

	/**
	 * Helper method to show an error if the user selected an wrong file type
	 */
	onExtensionError : function() {
		var me = this;
		Shopware.Notification.createGrowlMessage(me.snippets.failure.title, me.snippets.failure.wrongFileType);
	},

	/**
	 * Helper method to set the allowed file extension for the media manager
	 *
	 * @return array of strings
	 */
	getAllowedExtensions : function() {
		return [ 'gif', 'png', 'jpeg', 'jpg' ]
	}

});
//{/block}
