
import React from 'react';
import { Row, Col, Card, Timeline, Icon, Table, Popconfirm, Button, Modal, Form, Input, message } from 'antd';
import BreadcrumbCustom from '../BreadcrumbCustom';
import axios from 'axios';
import { cfg, func } from '../../common';

import '../../style/css/common.css';

class UserShow_ extends React.Component {
	
	loadData() {
		var that = this;
		axios.get(cfg.web_server_root + "user/show?page=" + that.state.page).then(function (response) {
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
	
	onPage = (page, pageSize) => {
		this.setState({
			page: page,
			page_size: pageSize,
		});
		this.loadData();
	};
	
	onDelete(id, event) {
		
		var that = this;

        var url = null;
        /*if(that.doMany) {
            //删除多条数据
            if(that.selected_objs.length == 0) {
                        that.$message({
                            message: "请选择要删除的用户",
                            type: 'error'
                        });
                        this.delVisible = false;
                        return false;
                    }
                    else {
                        url = cfg.web_server_root + "user/del?ids=";
                        for(var i = 0; i < that.selected_objs.length; i++) {
                            url += that.selected_objs[i].id;
                            if(i + 1 < that.selected_objs.length) {
                                url += ",";
                            }
                        }
                    }
                }
                else {*/
                    //删除单条数据
            url = cfg.web_server_root + "user/del?ids=" + id;
                //}
        axios.get(url).then(function (response) {
            if(response.data.code == 0) {
                that.loadData();
                
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

        /*that.refs.mainform.validate((valid) => {
                    if (valid) {
                        that.$axios.post(cfg.web_server_root + "user/add", {
                            name: that.mainform.name,
                            pwd: that.mainform.pwd
                        }).then(function (response) {
                            if(response.data.code == 0) {
                                that.mainform.name = "";
                                that.mainform.pwd = "";
                                that.page = 1;
                                that.loadData();
                                that.$message({
                                    message: response.data.msg,
                                    type: 'success'
                                });
                            }
                            else {
                                that.$message({
                                    message: response.data.msg,
                                    type: 'error'
                                });
                            }
                        }).catch(function (error) {
                            that.$alert(error, '提示', {
                                confirmButtonText: '确定',
                                type: 'error'
                            });
                        });
                    }
                    else {
                        that.$alert('请正确输入完整信息', '提示', {
                            confirmButtonText: '确定',
                            type: 'error'
                        });
                        return false;
                    }
        });*/
	}
	
	onAddCancel(event) {
		this.setState({
			is_add_visible: false,
		});
	}
	
	constructor(props) {
		super(props);
    	
    	this.state = {
    		maindata: [],
    		page: 1,
            page_count: 1,
            page_size: 1,
            total: 1,
            doMany: false,
            is_add_visible: false,
            mainform: {
            	name: '',
            	pwd: ''
            }
    	};
    	
    	document.title = cfg.web_title;
	}
	
	componentDidMount() {
		this.loadData();
	}
	
    render() {
    	
    	const main_data_columns = [
    		{
				title: '用户名',
				dataIndex: 'name',
			},
			{
				title: '添加时间',
				dataIndex: 'add_time_s',
			},
			{
				title: '错误登录次数',
				dataIndex: 'err_login',
			},
			{
				title: '操作',
				key: 'action',
				render: (text, record) => (
					<span>
						<Popconfirm title="确定删除吗？" onConfirm={this.onDelete.bind(this, record.id)} okText="删除" cancelText="取消">
							<a href="#">删除</a>
						</Popconfirm>
					</span>
				),
			}
		];

		const rowSelection = {
			onChange: (selectedRowKeys, selectedRows) => {
				console.log(`selectedRowKeys: ${selectedRowKeys}`, 'selectedRows: ', selectedRows);
			},
			getCheckboxProps: record => ({
				name: "id_" + record.id,
			}),
		};
    	
    	const { getFieldDecorator } = this.props.form;
        return (        
            <div>
                <BreadcrumbCustom first="用户管理" firsturl="/app/user/show" second="用户列表" />
                <Row gutter={10}>
                    <Col className="gutter-row" md={24}>
                        <div className="gutter-box">
                            <Card bordered={false}>
                                <div style={ { padding: '5px 0' } }>
                                	<Modal title="添加用户" visible={this.state.is_add_visible} onOk={this.onAddSubmit.bind(this)} onCancel={this.onAddCancel.bind(this)} okText="添加" cancelText="取消">
										<Form ref="mainform">
											<table style={ { width: '100%' } }>
												<tr>
													<td style={ { width: '20%', textAlign: 'right', padding: '15px 10px 15px 0' } }>用户名</td>
													<td>
														<Form.Item style={ { margin: '0' } }>
															{getFieldDecorator('name', {
                                								rules: [{ required: true, message: '请输入用户名!' }],
                            								})(
																<Input placeholder="请输入用户名" />
															)}
														</Form.Item>
													</td>
												</tr>
												<tr>
													<td style={ { textAlign: 'right', padding: '15px 10px 15px 0' } }>密码</td>
													<td>
														<Form.Item style={ { margin: '0' } }>
															<Input placeholder="请输入密码" value={this.state.mainform.pwd} />
														</Form.Item>
													</td>
												</tr>
											</table>
										</Form>
									</Modal>
                                	<Button style={ { float: 'right' } } onClick={this.onAddBtnClick.bind(this)}>添加用户</Button>
                                	<div className="clear" />
                                </div>
                                <Table rowSelection={rowSelection} columns={main_data_columns} dataSource={this.state.maindata} locale={ { emptyText: "没有数据" } } pagination={ { current: this.state.page, pageSize: this.state.page_size, total: this.state.total, onChange: this.onPage } } />
                            	
                            </Card>
                        </div>
                    </Col>
                </Row>                
            </div>
        )
    }
}
const UserShow = Form.create()(UserShow_);
export default UserShow;
