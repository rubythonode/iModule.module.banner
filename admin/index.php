<script>
var panel = new Ext.TabPanel({
	id:"ModuleBanner",
	border:false,
	tabPosition:"bottom",
	activeTab:0,
	items:[
		new Ext.grid.Panel({
			id:"ModuleBannerList",
			title:Banner.getText("admin/list/title"),
			border:false,
			tbar:[
				new Ext.form.ComboBox({
					id:"ModuleBannerGroupSelect",
					store:new Ext.data.JsonStore({
						proxy:{
							type:"ajax",
							url:ENV.getProcessUrl("banner","@getGroups"),
							extraParams:{is_all:"true"},
							reader:{type:"json"}
						},
						autoLoad:true,
						remoteSort:false,
						sorters:[{property:"sort",direction:"ASC"}],
						fields:["idx","title",{name:"sort",type:"int"}]
					}),
					width:140,
					editable:false,
					displayField:"title",
					valueField:"idx",
					value:"0",
					listeners:{
						change:function(form,value) {
							Ext.getCmp("ModuleBannerList").getStore().getProxy().setExtraParam("gidx",value);
							Ext.getCmp("ModuleBannerList").getStore().reload();
						}
					}
				}),
				new Ext.Button({
					iconCls:"fa fa-cog",
					handler:function() {
						Banner.group.manager();
					}
				}),
				"-",
				new Ext.Button({
					iconCls:"xi xi-coupon",
					text:Banner.getText("admin/banner/add"),
					handler:function() {
						Banner.add();
					}
				}),
				new Ext.Button({
					text:"선택 배너삭제",
					handler:function() {
						Banner.delete();
					}
				})
			],
			store:new Ext.data.JsonStore({
				proxy:{
					type:"ajax",
					simpleSortMode:true,
					url:ENV.getProcessUrl("banner","@getBanners"),
					extraParams:{gidx:"0"},
					reader:{type:"json"}
				},
				remoteSort:true,
				sorters:[{property:"reg_date",direction:"DESC"}],
				autoLoad:true,
				pageSize:50,
				fields:["idx","group_title","text","url","target","permission","sort","reg_date"],
				listeners:{
					load:function(store,records,success,e) {
						if (success == false) {
							if (e.getError()) {
								Ext.Msg.show({title:Admin.getText("alert/error"),msg:e.getError(),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR})
							} else {
								Ext.Msg.show({title:Admin.getText("alert/error"),msg:Admin.getErrorText("LOAD_DATA_FAILED"),buttons:Ext.Msg.OK,icon:Ext.Msg.ERROR})
							}
						}
					}
				}
			}),
			width:"100%",
			columns:[{
				text:Banner.getText("admin/list/columns/group_title"),
				width:150,
				dataIndex:"group_title",
				sortable:true
			},{
				text:Banner.getText("admin/list/columns/text"),
				width:200,
				dataIndex:"text",
				sortable:true
			},{
				text:Banner.getText("admin/list/columns/url"),
				minWidth:200,
				dataIndex:"url",
				flex:1,
				sortable:true
			},{
				text:Banner.getText("admin/list/columns/target"),
				width:160,
				dataIndex:"target",
				align:"center",
				renderer:function(value) {
					return Banner.getText("target/"+value);
				}
			},{
				text:Banner.getText("admin/list/columns/sort"),
				width:80,
				dataIndex:"sort",
				hideable:false,
				sortable:true,
				align:"right"
			},{
				text:Banner.getText("admin/list/columns/permission"),
				width:200,
				hideable:false,
				dataIndex:"permission"
			},{
				text:Banner.getText("admin/list/columns/reg_date"),
				width:160,
				hideable:false,
				dataIndex:"reg_date",
				renderer:function(value) {
					return moment(value * 1000).format("YYYY-MM-DD HH:mm");
				}
			}],
			selModel:new Ext.selection.CheckboxModel(),
			bbar:new Ext.PagingToolbar({
				store:null,
				displayInfo:false,
				items:[
					"->",
					{xtype:"tbtext",text:Admin.getText("text/grid_help")}
				],
				listeners:{
					beforerender:function(tool) {
						tool.bindStore(Ext.getCmp("ModuleBannerList").getStore());
					}
				}
			}),
			listeners:{
				itemdblclick:function(grid,record) {
					Banner.add(record.data.idx);
				},
				itemcontextmenu:function(grid,record,item,index,e) {
					var menu = new Ext.menu.Menu();
					
					menu.add('<div class="x-menu-title">'+record.data.text+'</div>');
					
					menu.add({
						iconCls:"xi xi-form",
						text:"대상 URL로 이동",
						handler:function() {
							window.open(record.data.url)
						}
					});
					
					menu.add("-");
					
					menu.add({
						iconCls:"xi xi-form",
						text:"배너수정",
						handler:function() {
							Banner.add(record.data.idx);
						}
					});
					
					menu.add({
						iconCls:"xi xi-form",
						text:"배너삭제",
						handler:function() {
							Banner.delete();
						}
					});
					
					e.stopEvent();
					menu.showAt(e.getXY());
				}
			}
		})
	]
});
</script>