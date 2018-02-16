vkroulette.grid.members = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		id: 'vkroulette-grid-members'
		,url: vkroulette.config.connector_url
		,baseParams: {
			action: 'mgr/member/getlist'
		}
		,fields: ['id','uid','first_name','last_name','screen_name','photo','link','signed','repost','manager']
		,autoHeight: true
		,paging: true
		,remoteSort: true
		,columns: [
			{header: _('id'),dataIndex: 'id',width: 50}
			,{header: _('vkroulette_member_uid'),dataIndex: 'uid',width: 70}
			,{header: _('vkroulette_member_first_name'),dataIndex: 'first_name',width: 150}
			,{header: _('vkroulette_member_last_name'),dataIndex: 'last_name',width: 150}
			//,{header: _('vkroulette_member_screen_name'),dataIndex: 'screen_name',width: 100}
			,{header: _('vkroulette_member_photo'),dataIndex: 'photo',width: 60, renderer: this.renderImage}
			//,{header: _('vkroulette_member_link'),dataIndex: 'link',width: 100}
			,{header: _('vkroulette_member_signed'),dataIndex: 'signed',width: 50, renderer: this.renderBoolean}
			,{header: _('vkroulette_member_repost'),dataIndex: 'repost',width: 50, renderer: this.renderBoolean}
			,{header: _('vkroulette_member_manager'),dataIndex: 'manager',width: 50, renderer: this.renderBoolean}
		]
		,tbar: [{
			text: _('vkroulette_btn_create')
			,handler: this.CreateMember
			,scope: this
		},{
			text: _('vkroulette_btn_updatelist')
			,handler: this.UpdateList
			,scope: this
		},{
			text: _('vkroulette_btn_rewritelist')
			,handler: this.ReWriteList
			,scope: this
		}]
		,listeners: {
			rowDblClick: function(grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.updateItem(grid, e, row);
			}
		}
	});
	vkroulette.grid.members.superclass.constructor.call(this,config);
};
Ext.extend(vkroulette.grid.members,MODx.grid.Grid,{
	windows: {}

	,getMenu: function() {
		var m = [];
		m.push({
			text: _('vkroulette_member_update')
			,handler: this.updateItem
		});
		m.push('-');
		m.push({
			text: _('vkroulette_member_remove')
			,handler: this.removeItem
		});
		this.addContextMenuItem(m);
	}

	,CreateMember: function(btn,e) {
		if (!this.windows.CreateMember) {
			this.windows.CreateMember = MODx.load({
				xtype: 'vkroulette-window-member-create'
				,listeners: {
					'success': {fn:function() { this.refresh(); },scope:this}
				}
			});
		}
		this.windows.CreateMember.fp.getForm().reset();
		this.windows.CreateMember.show(e.target);
	}

	,updateItem: function(btn,e,row) {
		if (typeof(row) != 'undefined') {this.menu.record = row.data;}
		var id = this.menu.record.id;

		MODx.Ajax.request({
			url: vkroulette.config.connector_url
			,params: {
				action: 'mgr/member/get'
				,id: id
			}
			,listeners: {
				success: {fn:function(r) {
					if (!this.windows.updateItem) {
						this.windows.updateItem = MODx.load({
							xtype: 'vkroulette-window-member-update'
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

	,UpdateList: function(btn,e) {
		// спросим подтверждение нужного действия
		MODx.msg.confirm({
			title: _('vkroulette_member_updatelist')
			,text: _('vkroulette_member_updatelist_confirm')
			,url: this.config.url
			,params: {
				action: 'mgr/member/updatelist'
				//,id: this.menu.record.id
			}
			,listeners: {
				'success': {fn:function(r) { this.refresh(); },scope:this}
			}
		});

	}

	,ReWriteList: function(btn,e) {
		// спросим подтверждение нужного действия
		MODx.msg.confirm({
			title: _('vkroulette_member_rewritelist')
			,text: _('vkroulette_member_rewritelist_confirm')
			,url: this.config.url
			,params: {
				action: 'mgr/member/rewritelist'
				//,id: this.menu.record.id
			}
			,listeners: {
				'success': {fn:function(r) { this.refresh(); },scope:this}
			}
		});

	}

	,removeItem: function(btn,e) {
		if (!this.menu.record) return false;

		MODx.msg.confirm({
			title: _('vkroulette_member_remove')
			,text: _('vkroulette_member_remove_confirm')
			,url: this.config.url
			,params: {
				action: 'mgr/member/remove'
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
Ext.reg('vkroulette-grid-members',vkroulette.grid.members);




vkroulette.window.CreateMember = function(config) {
	config = config || {};
	this.ident = config.ident || 'mecmember'+Ext.id();
	Ext.applyIf(config,{
		title: _('vkroulette_member_create')
		,id: this.ident
		,height: 200
		,width: 475
		,url: vkroulette.config.connector_url
		,action: 'mgr/member/create'
		,fields: [
			{xtype: 'numberfield',fieldLabel: _('vkroulette_member_uid'),name: 'uid',id: 'vkroulette-'+this.ident+'-uid',anchor: '99%'}
			,{xtype: 'textfield',fieldLabel: _('vkroulette_member_first_name'),name: 'first_name',id: 'vkroulette-'+this.ident+'-first_name',anchor: '99%'}
			,{xtype: 'textfield',fieldLabel: _('vkroulette_member_last_name'),name: 'last_name',id: 'vkroulette-'+this.ident+'-last_name',anchor: '99%'}

			//,{xtype: 'textfield',fieldLabel: _('vkroulette_member_screen_name'),name: 'screen_name',id: 'vkroulette-'+this.ident+'-screen_name',anchor: '99%'}
			,{xtype: 'textfield',fieldLabel: _('vkroulette_member_photo'),name: 'photo',id: 'vkroulette-'+this.ident+'-photo',anchor: '99%'}
			//,{xtype: 'textfield',fieldLabel: _('vkroulette_member_link'),name: 'link',id: 'vkroulette-'+this.ident+'-link',anchor: '99%'}

			//,{xtype: 'combo-boolean',fieldLabel: _('vkroulette_member_signed'),name: 'signed',hiddenName: 'signed',id: 'vkroulette-'+this.ident+'-signed',anchor: '40%'}
			//,{xtype: 'combo-boolean',fieldLabel: _('vkroulette_member_repost'),name: 'repost',hiddenName: 'repost',id: 'vkroulette-'+this.ident+'-repost',anchor: '40%'}
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
						{xtype: 'textfield',fieldLabel: _('vkroulette_member_screen_name'),name: 'screen_name',id: 'vkroulette-'+this.ident+'-screen_name',anchor: '99%'}
						,{xtype: 'combo-boolean',fieldLabel: _('vkroulette_member_signed'),name: 'signed',hiddenName: 'signed',id: 'vkroulette-'+this.ident+'-signed',anchor: '40%'}
					]
				},{
					columnWidth: .5
					,layout: 'form'
					,defaults: { msgTarget: 'under' }
					,border:false
					,items: [
						{xtype: 'textfield',fieldLabel: _('vkroulette_member_link'),name: 'link',id: 'vkroulette-'+this.ident+'-link',anchor: '99%'}
						,{xtype: 'combo-boolean',fieldLabel: _('vkroulette_member_repost'),name: 'repost',hiddenName: 'repost',id: 'vkroulette-'+this.ident+'-repost',anchor: '40%'}
					]
				}]
			}
		]
		,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: function() {this.submit() },scope: this}]
	});
	vkroulette.window.CreateMember.superclass.constructor.call(this,config);
};
Ext.extend(vkroulette.window.CreateMember,MODx.Window);
Ext.reg('vkroulette-window-member-create',vkroulette.window.CreateMember);


vkroulette.window.UpdateMember = function(config) {
	config = config || {};
	this.ident = config.ident || 'meumember'+Ext.id();
	Ext.applyIf(config,{
		title: _('vkroulette_member_update')
		,id: this.ident
		,height: 200
		,width: 475
		,url: vkroulette.config.connector_url
		,action: 'mgr/member/update'
		,fields: [
			{xtype: 'numberfield',fieldLabel: _('vkroulette_member_uid'),name: 'uid',id: 'vkroulette-'+this.ident+'-uid',anchor: '99%'}
			,{xtype: 'textfield',fieldLabel: _('vkroulette_member_first_name'),name: 'first_name',id: 'vkroulette-'+this.ident+'-first_name',anchor: '99%'}
			,{xtype: 'textfield',fieldLabel: _('vkroulette_member_last_name'),name: 'last_name',id: 'vkroulette-'+this.ident+'-last_name',anchor: '99%'}

			//,{xtype: 'textfield',fieldLabel: _('vkroulette_member_screen_name'),name: 'screen_name',id: 'vkroulette-'+this.ident+'-screen_name',anchor: '99%'}
			,{xtype: 'textfield',fieldLabel: _('vkroulette_member_photo'),name: 'photo',id: 'vkroulette-'+this.ident+'-photo',anchor: '99%'}
			//,{xtype: 'textfield',fieldLabel: _('vkroulette_member_link'),name: 'link',id: 'vkroulette-'+this.ident+'-link',anchor: '99%'}

			//,{xtype: 'combo-boolean',fieldLabel: _('vkroulette_member_signed'),name: 'signed',hiddenName: 'signed',id: 'vkroulette-'+this.ident+'-signed',anchor: '40%'}
			//,{xtype: 'combo-boolean',fieldLabel: _('vkroulette_member_repost'),name: 'repost',hiddenName: 'repost',id: 'vkroulette-'+this.ident+'-repost',anchor: '40%'}
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
						{xtype: 'textfield',fieldLabel: _('vkroulette_member_screen_name'),name: 'screen_name',id: 'vkroulette-'+this.ident+'-screen_name',anchor: '99%'}
						,{xtype: 'combo-boolean',fieldLabel: _('vkroulette_member_signed'),name: 'signed',hiddenName: 'signed',id: 'vkroulette-'+this.ident+'-signed',anchor: '40%'}
					]
				},{
					columnWidth: .5
					,layout: 'form'
					,defaults: { msgTarget: 'under' }
					,border:false
					,items: [
						{xtype: 'textfield',fieldLabel: _('vkroulette_member_link'),name: 'link',id: 'vkroulette-'+this.ident+'-link',anchor: '99%'}
						,{xtype: 'combo-boolean',fieldLabel: _('vkroulette_member_repost'),name: 'repost',hiddenName: 'repost',id: 'vkroulette-'+this.ident+'-repost',anchor: '40%'}
					]
				}]
			}
		]
		,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: function() {this.submit() },scope: this}]
	});
	vkroulette.window.UpdateMember.superclass.constructor.call(this,config);
};
Ext.extend(vkroulette.window.UpdateMember,MODx.Window);
Ext.reg('vkroulette-window-member-update',vkroulette.window.UpdateMember);