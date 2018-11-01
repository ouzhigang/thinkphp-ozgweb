
import React from 'react';
import { Row, Col, Card, Timeline, Icon, Form, Tree, Button } from 'antd';
import BreadcrumbCustom from '../BreadcrumbCustom';
import { cfg, func } from '../../common';

import '../../style/css/data_class/Show.css';

class DataClassShow_ extends React.Component {
	constructor(props) {
		super(props);
    	
    	this.state = {
    		first_type_name: func.get_rest_param("type") === 2 ? "" : "分类管理",
    		second_type_name: func.get_rest_param("type") === 2 ? "" : "分类列表",
			treeData: [
				{ title: 'Expand to load', key: '0' },
				{ title: 'Expand to load', key: '1' },
				{ title: 'Tree Node', key: '2',
					children: [
						{ title: 'Expand to load456547', key: '3' },
						{ title: 'Expand to load6789679', key: '4' }
					]
				},
			],
    	};
		
    	document.title = cfg.web_title;
	}
	
	renderTreeNodes = (data) => {
		return data.map((item) => {
			var item_html = (
				<div>
					<span>{item.title}</span>					 
					<Button icon="delete" size="small">删除</Button>
					<Button icon="edit" size="small" style={ { marginRight: '10px' } }>修改</Button>					
				</div>
			);
			
			if (item.children) {				
				return (					
					<Tree.TreeNode title={item_html} key={item.key} dataRef={item}>
					{this.renderTreeNodes(item.children)}
					</Tree.TreeNode>
				);
			}
			return <Tree.TreeNode {...item} title={item_html} dataRef={item} />;
		});
	}

    render() {
        return (
            <div>
                <BreadcrumbCustom first={this.state.first_type_name} second={this.state.second_type_name} />
                <Row gutter={10}>
                    <Col className="gutter-row" md={24}>
                        <div className="gutter-box">
                            <Card bordered={false}>
                                
								<Tree defaultExpandAll>
								{this.renderTreeNodes(this.state.treeData)}
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
