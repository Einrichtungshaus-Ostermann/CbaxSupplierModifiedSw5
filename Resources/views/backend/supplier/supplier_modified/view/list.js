//{block name="backend/supplier/view/main/list" append}
Ext.define('Shopware.apps.Supplier.view.main.List-SupplierModified', {
	override: 'Shopware.apps.Supplier.view.main.List',

	snippets: {
		columns: {
			isPremium: '{s namespace="backend/plugins/supplier_modified/view/list" name=columnIsPremium}Top Hersteller{/s}'
		},
		success: {
			title: '{s namespace="backend/plugins/supplier_modified/view/list" name=successTitle}Erfolgreich{/s}',
			editIsPremium: '{s namespace="backend/plugins/supplier_modified/view/list" name=successEditIsPremium}Die Änderungen wurden erfolgreich gespeichert{/s}'
		},
		failure: {
			title: '{s namespace="backend/plugins/supplier_modified/view/list" name=failureTitle}Fehler{/s}',
			editIsPremium: '{s namespace="backend/plugins/supplier_modified/view/list" name=failureEditIsPremium}Die Änderungen wurden NICHT gespeichert{/s}'
		}
	},

	/**
	 * Return an array of objects (grid columns)
	 *
	 * @return array of grid columns
	 */
	getGridColumns: function () {
		var me = this,
			columns = me.callParent(arguments);

		columns.splice((columns.length - 1), 0, me.getSupplierModifiedGridColumns());

		/*{if {acl_is_allowed privilege=update}}*/
		me.editor = me.createCellEditor();
		me.plugins = [me.editor];
		/*{/if}*/

		return columns;
	},

	getSupplierModifiedGridColumns: function () {
		var me = this;

		return {
			header: me.snippets.columns.isPremium,
			dataIndex: 'cbaxSupplierIsPremium',
			renderer: me.createBooleanRenderer,
			align: 'center',
			sortable: false,
			tdCls: 'x-action-col-cell',
			width: 80,
			editor: {
				xtype: 'checkbox',
				inputValue: true,
				uncheckedValue: false
			}
		};
	},

	createCellEditor: function () {
		var me = this;

		me.cellEditor = Ext.create('Ext.grid.plugin.CellEditing', {
			clicksToMoveEditor: 1,
			autoCancel: true
		});

		me.cellEditor.on('edit', function (editor, e) {
			var oldValue = e.originalValue,
				newValue = e.value;

			if (oldValue === newValue) {
				return;
			}

			var store = me.store;

			Ext.Ajax.request({
				url: '{url controller="SupplierModified" action="updatePremiumSupplier"}',
				params: {
					supplierID: e.record.data.id,
					isPremium: e.record.data.cbaxSupplierIsPremium
				},
				success: function () {
					Shopware.Notification.createGrowlMessage(me.snippets.success.title, me.snippets.success.editIsPremium);
					store.load();
				},
				failure: function (response) {
					resp = Ext.JSON.decode(response.responseText);

					Shopware.Notification.createGrowlMessage(me.snippets.failure.title, me.snippets.failure.editIsPremium + '\n' + resp.message);
				}
			});

		});

		return me.cellEditor;
	},

	createBooleanRenderer: function (value) {
		var checked = (value == true) ? 'sprite-ui-check-box' : 'sprite-ui-check-box-uncheck';

		return '<span style="display:block; margin: 0 auto; height:16px; width:16px;" class="' + checked + '"></span>';
	}
});
//{/block}
