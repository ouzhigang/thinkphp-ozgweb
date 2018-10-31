
import React from 'react';
import { Row, Col, Card, Timeline, Icon } from 'antd';
import BreadcrumbCustom from '../BreadcrumbCustom';
import { cfg, func } from '../../common';

export default class DataClassShow extends React.Component {
	constructor(props) {
		super(props);
    	
    	this.state = {
    		first_type_name: func.get_rest_param("type") == 2 ? "" : "分类管理",
    		second_type_name: func.get_rest_param("type") == 2 ? "" : "分类列表",
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
                                DataClassShow
                            </Card>
                        </div>
                    </Col>
                </Row>                
            </div>
        )
    }
}
