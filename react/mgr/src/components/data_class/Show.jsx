
import React from 'react';
import { Row, Col, Card, Timeline, Icon, Form, Tree, Button, Modal, Input, Popconfirm, message } from 'antd';
import BreadcrumbCustom from '../BreadcrumbCustom';
import axios from 'axios';
import { cfg, func } from '../../common';

import '../../style/css/common.css';
import '../../style/css/data_class/Show.css';

class DataClassShow_ extends React.Component {
	
	loadData() {

		var that = this;
		var type = parseInt(func.get_rest_param("type"));
		axios.get(cfg.web_server_root + "data_class/show?type=" + type).then(function (response) {
			if(response.data.code === 0) {
				that.setState({
					maindata: response.data.data
				});
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
			add_btn_text: "添加"
		});
	}
	onEditBtnClick(event) {
		this.setState({
			is_add_visible: true,
			add_btn_text: "修改"
		});
	}
	onDeleteBtnClick(id, event) {
		console.log(id);
	}
	onAddSubmit(event) {
		this.setState({
			is_add_visible: false,			
		});

        var that = this;
		
		
		
	}
	
	onAddCancel(event) {
		this.setState({
			is_add_visible: false,
		});
	}
		
	constructor(props) {
		super(props);
    	
		var type = parseInt(func.get_rest_param("type"));
    	this.state = {
    		first_type_name: type === 2 ? "" : "分类管理",
    		second_type_name: type === 2 ? "" : "分类列表",
			type_name: type === 2 ? "" : "分类",
			maindata: [],
			is_add_visible: false,
			add_btn_text: "",
    	};
		
    	document.title = cfg.web_title;
	}
	
	renderTreeNodes = (data) => {
		return data.map((item) => {
			var item_html = (
				<div>
					<span>{item.name}</span>
					<Popconfirm title="确定删除吗？" onConfirm={this.onDeleteBtnClick.bind(this, item.id)} okText="删除" cancelText="取消">
						<Button icon="delete" size="small">删除</Button>
					</Popconfirm>					
					<Button icon="edit" size="small" style={ { marginRight: '10px' } } onClick={this.onEditBtnClick.bind(this)}>修改</Button>					
				</div>
			);
			
			if (item.children && item.children.length > 0) {				
				return (					
					<Tree.TreeNode title={item_html} key={item.id} dataRef={item}>
					{this.renderTreeNodes(item.children)}
					</Tree.TreeNode>
				);
			}
			return <Tree.TreeNode {...item} title={item_html} dataRef={item} />;
		});
	}

	componentDidMount() {
		this.loadData();
		
	}
	
    render() {
		
		var that = this;
		const { getFieldDecorator } = that.props.form;
        return (
            <div>
                <BreadcrumbCustom first={this.state.first_type_name} second={this.state.second_type_name} />
                <Row gutter={10}>
                    <Col className="gutter-row" md={24}>
                        <div className="gutter-box">
                            <Card bordered={false}>
                                <div style={ { padding: '5px 0' } }>
                                	<Modal title={ this.state.type_name + this.state.add_btn_text } visible={this.state.is_add_visible} onOk={this.onAddSubmit.bind(this)} onCancel={this.onAddCancel.bind(this)} okText={ this.state.add_btn_text } cancelText="取消">
										<Form>
											<Form.Item style={ { margin: '0' } } label="分类名称">
												{getFieldDecorator('name', {
													rules: [{ required: true, message: '请输入分类名称!' }],
												})(
													<Input placeholder="请输入分类名称" />
												)}
											</Form.Item>
											<Form.Item style={ { margin: '0', marginTop: '20px' } } label="排序">
												{getFieldDecorator('sort', {
													rules: [{ required: true, message: '请输入排序!' }],
												})(
													<Input placeholder="请输入排序" type="number" value="0" />
												)}
											</Form.Item>
										</Form>
									</Modal>
                                	<Button style={ { float: 'right' } } onClick={this.onAddBtnClick.bind(this)}>添加分类</Button>
                                	<div className="clear" />
                                </div>
								<Tree>
								{this.renderTreeNodes(this.state.maindata)}
								</Tree>
								
                            </Card>
                        </div>
                    </Col>
                </Row>                
            </div>
        )
    }
}
const DataClassShow = Form.create()(DataClassShow_);
export default DataClassShow;
