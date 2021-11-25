//{namespace name=backend/plugins/supplier_modified/view/create}
//{block name="backend/supplier/view/main/create"}
//{$smarty.block.parent}
Ext.define('Shopware.apps.Supplier.view.main.Create-SupplierModified', {
	override : 'Shopware.apps.Supplier.view.main.Create',

	snippets: {
		titleFieldSet: {
			title: '{s name=columnIsPremium}Hersteller Professional{/s}'
		},
		isPremium: {
			label: '{s name=fieldLabelIsPremium}Top Hersteller{/s}',
			help: '{s name=helpTextIsPremium}Wenn aktiv, dann wird der Hersteller prominent in einem Slider über den anderen Herstellern angezeigt.{/s}',
		},
		isHidden: {
			label: '{s name=fieldLabelIsHidden}Hersteller ausblenden{/s}',
			help: '{s name=helpTextIsHidden}Wenn aktiv, dann wird der Hersteller nicht in der Hersteller Übersicht angezeigt.{/s}',
		},
		banner: {
			label: '{s name=fieldLabelBanner}Banner{/s}',
			help: '{s name=helpTextBanner}Hier haben Sie die Möglichkeit ein Banner für den Hersteller zu hinterlegen.{/s}',
		},
		url: {
			label: '{s name=fieldLabelUrl}Alternative URL{/s}',
			help: '{s name=helpTextUrle}Die hier hinterlegte Url ersetzt die normale Url bei dem jeweiligen Hersteller. So können Sie an Stelle der normalen Artikel Übersicht z.B. auch auf eine Shopseite oder Landingpage verweisen.{/s}',
		}
	},

	/**
	 * Returns the whole form to edit the supplier
	 *
	 * @returns Ext.form Panel
	 */
	getFormPanel: function () {
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
				name: 'cbaxSupplierIsHidden',
				inputValue: true,
				uncheckedValue: false,
				helpText: me.snippets.isHidden.help
			}, {
				fieldLabel: me.snippets.url.label,
				xtype: 'textfield',
				name: 'cbaxSupplierUrl',
				vtype: 'url',
				helpText: me.snippets.url.help
			}, {
				fieldLabel: me.snippets.isPremium.label,
				xtype: 'checkbox',
				name: 'cbaxSupplierIsPremium',
				inputValue: true,
				uncheckedValue: false,
				helpText: me.snippets.isPremium.help
			}, {
				fieldLabel: me.snippets.banner.label,
				xtype: 'mediaselectionfield',
				name: 'cbaxSupplierBanner',
				readOnly: false,
				multiSelect: false,
				validTypes: me.getAllowedExtensions(),
				validTypeErrorFunction: me.getExtensionErrorCallback(),
				helpText: me.snippets.banner.help
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