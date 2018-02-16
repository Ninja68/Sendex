vkroulette.grid.winners = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		id: 'vkroulette-grid-winners'
		,url: vkroulette.config.connector_url
		,baseParams: {
			action: 'mgr/winner/getlist'
		}
		,fields: ['id','uid','first_name','last_name','screen_name','photo','link','data','summa','mmbrscount','mmbrsrepost']
		,autoHeight: true
		,paging: true
		,remoteSort: true
		,columns: [
			{header: _('id'),dataIndex: 'id',width: 30}
			,{header: _('vkroulette_winner_uid'),dataIndex: 'uid',width: 50}
			,{header: _('vkroulette_winner_first_name'),dataIndex: 'first_name',width: 80}
			,{header: _('vkroulette_winner_last_name'),dataIndex: 'last_name',width: 80}
			//,{header: _('vkroulette_winner_screen_name'),dataIndex: 'screen_name',width: 50}
			,{header: _('vkroulette_winner_photo'),dataIndex: 'photo',width: 60, renderer: this.renderImage}
			//,{header: _('vkroulette_winner_link'),dataIndex: 'link',width: 80}
			,{header: _('vkroulette_winner_data'),dataIndex: 'data',width: 30}
			,{header: _('vkroulette_winner_summa'),dataIndex: 'summa',width: 30}
			,{header: _('vkroulette_winner_mmbrscount'),dataIndex: 'mmbrscount',width: 30}
			,{header: _('vkroulette_winner_mmbrsrepost'),dataIndex: 'mmbrsrepost',width: 30}
		]
		,tbar: [{
			text: _('vkroulette_winner_find')
			,handler: this.AutoCreateWinner
			,scope: this
		},{
			text: _('vkroulette_winner_create')
			,handler: this.CreateWinner
			,scope: this
		}]
		,listeners: {
			rowDblClick: function(grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.updateItem(grid, e, row);
			}
		}
	});
	vkroulette.grid.winners.superclass.constructor.call(this,config);
};
Ext.extend(vkroulette.grid.winners,MODx.grid.Grid,{
	windows: {}

	,getMenu: function() {
		var m = [];
		m.push({
			text: _('vkroulette_winner_update')
			,handler: this.updateItem
		});
		m.push('-');
		m.push({
			text: _('vkroulette_winner_autocreate')
			,handler: this.AutoCreateWinner
		});
		m.push('-');
		m.push({
			text: _('vkroulette_winner_remove')
			,handler: this.removeItem
		});
		this.addContextMenuItem(m);
	}

	,AutoCreateWinner: function(btn,e) {
		// спросим подтверждение нужного действия
		MODx.msg.confirm({
			title: _('vkroulette_winner_autocreate')
			,text: _('vkroulette_winner_autocreate_confirm')
			,url: this.config.url
			,params: {
				action: 'mgr/winner/autocreate'
				//,id: this.menu.record.id
			}
			,listeners: {
				'success': {fn:function(r) { this.refresh(); },scope:this}
			}
		});

		// if (!this.windows.AutoCreateWinner) {
		//     this.windows.AutoCreateWinner = MODx.load({
		//         xtype: 'vkroulette-window-winner-autocreate'
		//         ,listeners: {
		//             'success': {fn:function() { this.refresh(); },scope:this}
		//         }
		//     });
		// }
		// this.windows.AutoCreateWinner.fp.getForm().reset();
		// this.windows.AutoCreateWinner.show(e.target);
	}

	,CreateWinner: function(btn,e) {
		if (!this.windows.CreateWinner) {
			this.windows.CreateWinner = MODx.load({
				xtype: 'vkroulette-window-winner-create'
				,listeners: {
					'success': {fn:function() { this.refresh(); },scope:this}
				}
			});
		}
		this.windows.CreateWinner.fp.getForm().reset();
		this.windows.CreateWinner.show(e.target);
	}

	,updateItem: function(btn,e,row) {
		if (typeof(row) != 'undefined') {this.menu.record = row.data;}
		var id = this.menu.record.id;

		MODx.Ajax.request({
			url: vkroulette.config.connector_url
			,params: {
				action: 'mgr/winner/get'
				,id: id
			}
			,listeners: {
				success: {fn:function(r) {
					if (!this.windows.updateItem) {
						this.windows.updateItem = MODx.load({
							xtype: 'vkroulette-window-winner-update'
							,record: r
							,listeners: {
								'success': {fn:function() { this.refresh(); },scope:this}
							}
						});
					}
					this.windows.updateItem.fp.getForm().reset();
					this.windows.updateItem.fp.getForm().setValues(r.object);
					this.windows.updateItem.show(e.target);
				},scope:this}
			}
		});
	}

	,removeItem: function(btn,e) {
		if (!this.menu.record) return false;

		MODx.msg.confirm({
			title: _('vkroulette_winner_remove')
			,text: _('vkroulette_winner_remove_confirm')
			,url: this.config.url
			,params: {
				action: 'mgr/winner/remove'
				,id: this.menu.record.id
			}
			,listeners: {
				'success': {fn:function(r) { this.refresh(); },scope:this}
			}
		});
	}

	,renderBoolean: function(val,cell,row) {
		return val == '' || val == 0
			? '<span style="color:red">' + _('no') + '<span>'
			: '<span style="color:green">' + _('yes') + '<span>';
	}

	,renderImage: function(val,cell,row) {
		return val != ''
			? '<img src="' + val + '" alt="" height="50" />'
			: '';
	}
});
Ext.reg('vkroulette-grid-winners',vkroulette.grid.winners);

// vkroulette.window.AutoCreateWinner = function(config) {
// 	config = config || {};
// 	this.ident = config.ident || 'meacwinner'+Ext.id();
// 	Ext.applyIf(config,{
// 		title: _('vkroulette_winner_autocreate')
// 		,id: this.ident
// 		,height: 200
// 		,width: 475
// 		,url: vkroulette.config.connector_url
// 		,action: 'mgr/winner/autocreate'
// 		,fields: [
// 			{xtype: 'numberfield',fieldLabel: _('vkroulette_winner_uid'),name: 'uid',id: 'vkroulette-'+this.ident+'-uid',anchor: '99%'}
// 			,{xtype: 'textfield',fieldLabel: _('vkroulette_winner_first_name'),name: 'first_name',id: 'vkroulette-'+this.ident+'-first_name',anchor: '99%'}
// 			,{xtype: 'textfield',fieldLabel: _('vkroulette_winner_last_name'),name: 'last_name',id: 'vkroulette-'+this.ident+'-last_name',anchor: '99%'}
//
// 			//,{xtype: 'textfield',fieldLabel: _('vkroulette_winner_screen_name'),name: 'screen_name',id: 'vkroulette-'+this.ident+'-screen_name',anchor: '99%'}
// 			,{xtype: 'textfield',fieldLabel: _('vkroulette_winner_photo'),name: 'photo',id: 'vkroulette-'+this.ident+'-photo',anchor: '99%'}
// 			//,{xtype: 'textfield',fieldLabel: _('vkroulette_winner_link'),name: 'link',id: 'vkroulette-'+this.ident+'-link',anchor: '99%'}
//
// 			//,{xtype: 'combo-boolean',fieldLabel: _('vkroulette_winner_signed'),name: 'signed',hiddenName: 'signed',id: 'vkroulette-'+this.ident+'-signed',anchor: '40%'}
// 			//,{xtype: 'combo-boolean',fieldLabel: _('vkroulette_winner_repost'),name: 'repost',hiddenName: 'repost',id: 'vkroulette-'+this.ident+'-repost',anchor: '40%'}
// 			,{
// 				layout:'column'
// 				,border: false
// 				,anchor: '100%'
// 				,items: [{
// 					columnWidth: .5
// 					,layout: 'form'
// 					,defaults: { msgTarget: 'under' }
// 					,border:false
// 					,items: [
// 						{xtype: 'textfield',fieldLabel: _('vkroulette_winner_screen_name'),name: 'screen_name',id: 'vkroulette-'+this.ident+'-screen_name',anchor: '99%'}
// 						,{xtype: 'combo-boolean',fieldLabel: _('vkroulette_winner_signed'),name: 'signed',hiddenName: 'signed',id: 'vkroulette-'+this.ident+'-signed',anchor: '40%'}
// 					]
// 				},{
// 					columnWidth: .5
// 					,layout: 'form'
// 					,defaults: { msgTarget: 'under' }
// 					,border:false
// 					,items: [
// 						{xtype: 'textfield',fieldLabel: _('vkroulette_winner_link'),name: 'link',id: 'vkroulette-'+this.ident+'-link',anchor: '99%'}
// 						,{xtype: 'combo-boolean',fieldLabel: _('vkroulette_winner_repost'),name: 'repost',hiddenName: 'repost',id: 'vkroulette-'+this.ident+'-repost',anchor: '40%'}
// 					]
// 				}]
// 			}
// 		]
// 		,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: function() {this.submit() },scope: this}]
// 	});
// 	vkroulette.window.AutoCreateWinner.superclass.constructor.call(this,config);
// };
// Ext.extend(vkroulette.window.AutoCreateWinner,MODx.Window);
// Ext.reg('vkroulette-window-winner-autocreate',vkroulette.window.AutoCreateWinner);

vkroulette.window.CreateWinner = function(config) {
	config = config || {};
	this.ident = config.ident || 'mecwinner'+Ext.id();
	Ext.applyIf(config,{
		title: _('vkroulette_winner_create')
		,id: this.ident
		,height: 200
		,width: 475
		,url: vkroulette.config.connector_url
		,action: 'mgr/winner/create'
		,fields: [
			{xtype: 'numberfield',fieldLabel: _('vkroulette_winner_uid'),name: 'uid',id: 'vkroulette-'+this.ident+'-uid',anchor: '99%'}
			,{xtype: 'textfield',fieldLabel: _('vkroulette_winner_first_name'),name: 'first_name',id: 'vkroulette-'+this.ident+'-first_name',anchor: '99%'}
			,{xtype: 'textfield',fieldLabel: _('vkroulette_winner_last_name'),name: 'last_name',id: 'vkroulette-'+this.ident+'-last_name',anchor: '99%'}

			//,{xtype: 'textfield',fieldLabel: _('vkroulette_winner_screen_name'),name: 'screen_name',id: 'vkroulette-'+this.ident+'-screen_name',anchor: '99%'}
			,{xtype: 'textfield',fieldLabel: _('vkroulette_winner_photo'),name: 'photo',id: 'vkroulette-'+this.ident+'-photo',anchor: '99%'}
			//,{xtype: 'textfield',fieldLabel: _('vkroulette_winner_link'),name: 'link',id: 'vkroulette-'+this.ident+'-link',anchor: '99%'}

			//,{xtype: 'combo-boolean',fieldLabel: _('vkroulette_winner_signed'),name: 'signed',hiddenName: 'signed',id: 'vkroulette-'+this.ident+'-signed',anchor: '40%'}
			//,{xtype: 'combo-boolean',fieldLabel: _('vkroulette_winner_repost'),name: 'repost',hiddenName: 'repost',id: 'vkroulette-'+this.ident+'-repost',anchor: '40%'}
			,{
				layout:'column'
				,border: false
				,anchor: '100%'
				,items: [{
					columnWidth: .5
					,layout: 'form'
					,defaults: { msgTarget: 'under' }
					,border:false
					,items: [
						{xtype: 'textfield',fieldLabel: _('vkroulette_winner_screen_name'),name: 'screen_name',id: 'vkroulette-'+this.ident+'-screen_name',anchor: '99%'}
						,{xtype: 'combo-boolean',fieldLabel: _('vkroulette_winner_signed'),name: 'signed',hiddenName: 'signed',id: 'vkroulette-'+this.ident+'-signed',anchor: '40%'}
					]
				},{
					columnWidth: .5
					,layout: 'form'
					,defaults: { msgTarget: 'under' }
					,border:false
					,items: [
						{xtype: 'textfield',fieldLabel: _('vkroulette_winner_link'),name: 'link',id: 'vkroulette-'+this.ident+'-link',anchor: '99%'}
						,{xtype: 'combo-boolean',fieldLabel: _('vkroulette_winner_repost'),name: 'repost',hiddenName: 'repost',id: 'vkroulette-'+this.ident+'-repost',anchor: '40%'}
					]
				}]
			}
		]
		,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: function() {this.submit() },scope: this}]
	});
	vkroulette.window.CreateWinner.superclass.constructor.call(this,config);
};
Ext.extend(vkroulette.window.CreateWinner,MODx.Window);
Ext.reg('vkroulette-window-winner-create',vkroulette.window.CreateWinner);

vkroulette.window.UpdateWinner = function(config) {
	config = config || {};
	this.ident = config.ident || 'meuwinner'+Ext.id();
	Ext.applyIf(config,{
		title: _('vkroulette_winner_update')
		,id: this.ident
		,height: 200
		,width: 475
		,url: vkroulette.config.connector_url
		,action: 'mgr/winner/update'
		,fields: [
			{xtype: 'numberfield',fieldLabel: _('vkroulette_winner_uid'),name: 'uid',id: 'vkroulette-'+this.ident+'-uid',anchor: '99%'}
			,{xtype: 'textfield',fieldLabel: _('vkroulette_winner_first_name'),name: 'first_name',id: 'vkroulette-'+this.ident+'-first_name',anchor: '99%'}
			,{xtype: 'textfield',fieldLabel: _('vkroulette_winner_last_name'),name: 'last_name',id: 'vkroulette-'+this.ident+'-last_name',anchor: '99%'}

			//,{xtype: 'textfield',fieldLabel: _('vkroulette_winner_screen_name'),name: 'screen_name',id: 'vkroulette-'+this.ident+'-screen_name',anchor: '99%'}
			,{xtype: 'textfield',fieldLabel: _('vkroulette_winner_photo'),name: 'photo',id: 'vkroulette-'+this.ident+'-photo',anchor: '99%'}
			//,{xtype: 'textfield',fieldLabel: _('vkroulette_winner_link'),name: 'link',id: 'vkroulette-'+this.ident+'-link',anchor: '99%'}

			//,{xtype: 'combo-boolean',fieldLabel: _('vkroulette_winner_signed'),name: 'signed',hiddenName: 'signed',id: 'vkroulette-'+this.ident+'-signed',anchor: '40%'}
			//,{xtype: 'combo-boolean',fieldLabel: _('vkroulette_winner_repost'),name: 'repost',hiddenName: 'repost',id: 'vkroulette-'+this.ident+'-repost',anchor: '40%'}
			,{
				layout:'column'
				,border: false
				,anchor: '100%'
				,items: [{
					columnWidth: .5
					,layout: 'form'
					,defaults: { msgTarget: 'under' }
					,border:false
					,items: [
						{xtype: 'textfield',fieldLabel: _('vkroulette_winner_screen_name'),name: 'screen_name',id: 'vkroulette-'+this.ident+'-screen_name',anchor: '99%'}
						,{xtype: 'combo-boolean',fieldLabel: _('vkroulette_winner_signed'),name: 'signed',hiddenName: 'signed',id: 'vkroulette-'+this.ident+'-signed',anchor: '40%'}
					]
				},{
					columnWidth: .5
					,layout: 'form'
					,defaults: { msgTarget: 'under' }
					,border:false
					,items: [
						{xtype: 'textfield',fieldLabel: _('vkroulette_winner_link'),name: 'link',id: 'vkroulette-'+this.ident+'-link',anchor: '99%'}
						,{xtype: 'combo-boolean',fieldLabel: _('vkroulette_winner_repost'),name: 'repost',hiddenName: 'repost',id: 'vkroulette-'+this.ident+'-repost',anchor: '40%'}
					]
				}]
			}
		]
		,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: function() {this.submit() },scope: this}]
	});
	vkroulette.window.UpdateWinner.superclass.constructor.call(this,config);
};
Ext.extend(vkroulette.window.UpdateWinner,MODx.Window);
Ext.reg('vkroulette-window-winner-update',vkroulette.window.UpdateWinner);