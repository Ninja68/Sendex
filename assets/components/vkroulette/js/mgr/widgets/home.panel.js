vkroulette.panel.Home = function(config) {
	config = config || {};
	Ext.apply(config,{
		border: false
		,baseCls: 'modx-formpanel'
		,items: [{
			html: '<h2>'+_('vkroulette')+'</h2>'
			,border: false
			,cls: 'modx-page-header container'
		},{
			xtype: 'modx-tabs'
			,bodyStyle: 'padding: 10px'
			,defaults: { border: false ,autoHeight: true }
			,border: true
            //,stateful: true
            //,stateId: 'sendex-panel-home'
            //,stateEvents: ['tabchange']
            //,getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};}
			,activeItem: 0
			,hideMode: 'offsets'
			,items: [{
				title: _('vkroulette_members')
				,items: [{
					html: _('vkroulette_members_intro')
					,border: false
					,bodyCssClass: 'panel-desc'
					,bodyStyle: 'margin-bottom: 10px'
				},{
					xtype: 'vkroulette-grid-members'
					,preventRender: true
				}]
			},{
                title: _('vkroulette_winners')
                ,items: [{
                    html: _('vkroulette_winners_intro')
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                    ,bodyStyle: 'margin-bottom: 10px'
                },{
                    xtype: 'vkroulette-grid-winners'
                    ,preventRender: true
                }]
			}]
		}]
	});
	vkroulette.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(vkroulette.panel.Home,MODx.Panel);
Ext.reg('vkroulette-panel-home',vkroulette.panel.Home);
