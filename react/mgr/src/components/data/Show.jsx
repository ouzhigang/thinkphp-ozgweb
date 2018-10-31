
import React from 'react';
import { Row, Col, Card, Timeline, Icon } from 'antd';
import BreadcrumbCustom from '../BreadcrumbCustom';
import { cfg, func } from '../../common';

export default class DataShow extends React.Component {
	constructor(props) {
		super(props);
    	
    	this.state = {
    		first_type_name: func.get_rest_param("type") == 2 ? "新闻管理" : "产品管理",
    		second_type_name: func.get_rest_param("type") == 2 ? "新闻列表" : "产品列表",
    	};
    	
    	document.title = cfg.web_title;
	}

    render() {
        return (
            <div>
                <BreadcrumbCustom first={this.state.first_type_name} second={this.state.second_type_name} />
                <Row gutter={10}>
                    <Col className="gutter-row" md={24}>
                        <div className="gutter-box">
                            <Card bordered={false}>
                                DataShow
                            </Card>
                        </div>
                    </Col>
                </Row>                
            </div>
        )
    }
}
