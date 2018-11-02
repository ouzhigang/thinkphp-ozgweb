
import React from 'react';
import { Row, Col, Card, Timeline, Icon, Table, Popconfirm, Button, Modal, Form, Input, Menu, Dropdown, message } from 'antd';
import BreadcrumbCustom from '../BreadcrumbCustom';
import axios from 'axios';
import { cfg, func } from '../../common';

import '../../style/css/common.css';
import '../../style/css/data/Show.css';

const SubMenu = Menu.SubMenu;

class DataShow_ extends React.Component {

	loadData(req_obj) {
		if(!req_obj || !req_obj.page) {
			req_obj = {
				page: 1
			};
		}
		
		var that = this;
		axios.get(cfg.web_server_root + "data/show?type=" + that.state.type + "&page=" + req_obj.page).then(function (response) {
            if(response.data.code == 0) {
                that.setState({
                	maindata: response.data.data.list,
    				page: response.data.data.page,
            		page_count: response.data.data.page_count,
            		page_size: response.data.data.page_size,
            		total: response.data.data.total,
                });
                
            }
            else {
                message.error(response.data.msg);
            }
        }).catch(function (error) {
            message.error(error);
        });
	}
	
	loadDataClassData() {		
		
		var that = this;
		
		if(parseInt(that.state.type) === 2) {
			return;
		}
		
		axios.get(cfg.web_server_root + "data_class/show?type=" + that.state.type).then(function (response) {
			if(response.data.code === 0) {
				that.setState({
					data_class_data: response.data.data,
				});
			}
			else {
				message.error(response.data.msg);				
			}
		}).catch(function (error) {
			message.error(error);
		});
	}
	
	onPage = (page, pageSize) => {
		
		this.loadData({
			page: page
		});
		
	};
	
	onManyDelete(event) {
		var that = this;
		if(that.state.selected_rows.length == 0) {
			message.error("请选择要删除的" + this.state.type_name);
		}
		else {
			var url = cfg.web_server_root + "data/del?ids=";
			for(var i = 0; i < that.state.selected_rows.length; i++) {
				url += that.state.selected_rows[i].id;
				if(i + 1 < that.state.selected_rows.length) {
					url += ",";
				}
			}
			
			axios.get(url).then(function (response) {
				if(response.data.code == 0) {
					that.loadData({
						page: that.state.page
					});
					
					message.info(response.data.msg);
				}
				else {
					message.error(response.data.msg);
				}
			}).catch(function (error) {
				message.error(error);
			});
		}
		
	}
	onDelete(id, event) {
		
		var that = this;
        var url = cfg.web_server_root + "data/del?ids=" + id;
		
        axios.get(url).then(function (response) {
            if(response.data.code == 0) {
                that.loadData({
					page: that.state.page
				});
                
                message.info(response.data.msg);
            }
            else {
                message.error(response.data.msg);
            }
        }).catch(function (error) {
            message.error(error);
        });
		
	}
	
	onAddBtnClick(event) {
		this.setState({
			is_add_visible: true,
		});
	}
	
	onAddSubmit(event) {
		this.setState({
			is_add_visible: false,
		});

        var that = this;
		
		this.props.form.validateFields((err, values) => {
            if (!err) {
                axios.post(cfg.web_server_root + "data/add", {
                    name: values.name,
                    pwd: values.pwd
                }).then(function (response) {					
					if(response.data.code === 0) {
                        
						that.props.form.setFieldsValue({
							name: "",
							pwd: "",
						});
						
						that.setState({
							page: 1
						});
						that.loadData();
						message.info(response.data.msg);
                    }                    
                    else {
                         message.info(response.data.msg);
                    }
				}).catch(function (error) {
					message.error(error);
				});                
            }
			else {
				message.error(err);
			}
        });
		
	}
	
	onAddCancel(event) {
		this.setState({
			is_add_visible: false,
		});
	}
	
	onDropdown(event) {
		
	}
	
	onSearchBtnClick(event) {
		this.setState({
			is_search_visible: true,
		});
	}
	
	onSearchSubmit(event) {
		this.setState({
			is_search_visible: false,
		});

        var that = this;
		
	}
	
	onSearchCancel(event) {
		this.setState({
			is_search_visible: false,
		});
	}
	
	constructor(props) {
		super(props);
    	
    	var type = parseInt(func.get_rest_param("type"));
    	this.state = {
    		first_type_name: type === 2 ? "新闻管理" : "产品管理",
    		second_type_name: type === 2 ? "新闻列表" : "产品列表",
    		type_name: type === 2 ? "新闻" : "产品",
    		type: type,
			
    		maindata: [],
    		page: 1,
            page_count: 1,
            page_size: 1,
            total: 1,
            is_add_visible: false,
			selected_rows: [],
			is_search_visible: false,
			data_class_data: [],
    	};
    	
    	document.title = cfg.web_title;
	}

	componentDidMount() {
		this.loadData();
		this.loadDataClassData();
		
		this.props.form.setFieldsValue({
			sort: "0",
		});
	}
	
    render() {
    	const menu = (
			<Menu>
				<Menu.Item>1st menu item</Menu.Item>
				<Menu.Item>2nd menu item</Menu.Item>
				<SubMenu title="sub menu">
					<Menu.Item>3rd menu item</Menu.Item>
					<Menu.Item>4th menu item</Menu.Item>
				</SubMenu>
				<SubMenu title="disabled sub menu" disabled>
					<Menu.Item>5d menu item</Menu.Item>
					<Menu.Item>6th menu item</Menu.Item>
				</SubMenu>
			</Menu>
		);
    
    	const main_data_columns = [
    		{
				title: '名称',
				dataIndex: 'name',
			},
			{
				title: '分类',
				dataIndex: 'dc_name',
			},
			{
				title: '点击',
				dataIndex: 'hits',
			},
			{
				title: '时间',
				dataIndex: 'add_time_s',
			},
			{
				title: '操作',
				key: 'action',
				render: (text, record) => (
					<span>
						<a href="javascript:void(0)" style={ { marginRight: '15px' } }>修改</a>
						<Popconfirm title="确定删除吗？" onConfirm={this.onDelete.bind(this, record.id)} okText="删除" cancelText="取消">
							<a href="javascript:void(0)">删除</a>
						</Popconfirm>
					</span>
				),
			}
		];
    
    	var that = this;
		const rowSelection = {
			onChange: (selectedRowKeys, selectedRows) => {
				//console.log(`selectedRowKeys: ${selectedRowKeys}`, 'selectedRows: ', selectedRows);
				that.setState({
					selected_rows: selectedRows
				});
			},
			getCheckboxProps: record => ({
				name: "id_" + record.id,
			}),
		};
		
    	const { getFieldDecorator } = that.props.form;
        return (
            <div>
                <BreadcrumbCustom first={this.state.first_type_name} second={this.state.second_type_name} />
                <Row gutter={10}>
                    <Col className="gutter-row" md={24}>
                        <div className="gutter-box">
                            <Card bordered={false}>
                                <div style={ { padding: '5px 0' } }>
                                	<Modal title={ '添加' + this.state.type_name } visible={this.state.is_add_visible} onOk={this.onAddSubmit.bind(this)} onCancel={this.onAddCancel.bind(this)} okText="添加" cancelText="取消" width="90%">
										<Form>
											<Form.Item style={ { margin: '0' } }>
												{getFieldDecorator('name', {
													rules: [{ required: true, message: '请输入' + this.state.type_name + '名称!' }],
												})(
													<Input placeholder={ '请输入' + this.state.type_name + '名称' } />
												)}
											</Form.Item>
											<Form.Item style={ { margin: '0', marginTop: '10px', display: this.state.type !== 2 ? 'block' : 'none' } }>
												<Dropdown.Button onClick={this.onDropdown.bind(this)} overlay={menu}>
												请选择分类
												</Dropdown.Button>
											</Form.Item>
											<Form.Item style={ { margin: '0', marginTop: '10px' } }>
												{getFieldDecorator('sort', {
													rules: [{ required: true, message: '请输入排序!' }],
												})(
													<Input placeholder="请输入排序" type="number" />
												)}
											</Form.Item>
											
										</Form>
									</Modal>
									<Modal title={ '搜索' + this.state.type_name } visible={this.state.is_search_visible} onOk={this.onSearchSubmit.bind(this)} onCancel={this.onSearchCancel.bind(this)} okText="搜素" cancelText="取消">
										<Form>
											<Form.Item style={ { margin: '0' } }>
												{getFieldDecorator('k_name', {													
												})(
													<Input placeholder={ '请输入搜索的' + this.state.type_name + '名称' } />
												)}
											</Form.Item>
											<Form.Item style={ { margin: '0', marginTop: '10px', display: this.state.type !== 2 ? 'block' : 'none' } }>
												<Dropdown.Button onClick={this.onDropdown.bind(this)} overlay={menu}>
												请选择分类
												</Dropdown.Button>
											</Form.Item>											
											
										</Form>
									</Modal>
									<Button style={ { float: 'right' } } onClick={this.onAddBtnClick.bind(this)}>添加</Button>
                                	<Button style={ { float: 'right', marginRight: '10px' } } onClick={this.onSearchBtnClick.bind(this)}>搜索</Button>
                                	<div className="clear" />
                                </div>
                                <Table rowSelection={rowSelection} columns={main_data_columns} dataSource={this.state.maindata} locale={ { emptyText: "没有数据" } } pagination={ { current: this.state.page, pageSize: this.state.page_size, total: this.state.total, onChange: this.onPage } } />
                                
                                <div ref="action_btn_div" style={ { position: 'absolute', marginTop: '-48px' } }>
									<Popconfirm title="确定删除吗？" onConfirm={this.onManyDelete.bind(this)} okText="删除" cancelText="取消">
										<Button>删除</Button>
									</Popconfirm>									
								</div>
								
                            </Card>
                        </div>
                    </Col>
                </Row>                
            </div>
        )
    }
}
const DataShow = Form.create()(DataShow_);
export default DataShow;
