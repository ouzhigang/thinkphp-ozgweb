
import React from 'react';
import { Row, Col, Card, Timeline, Icon, Table, message } from 'antd';
import BreadcrumbCustom from '../BreadcrumbCustom';
import { cfg, func } from '../../common';

import axios from 'axios';

import '../../style/css/dashboard/Dashboard.css';

export default class Dashboard extends React.Component {
	constructor(props) {
		super(props);
    	
    	this.state = {
    		
    	};
    	
    	document.title = cfg.web_title;
	}
	
	componentDidMount() {
		
	}

    render() {
    
        return (
            <div>
                <BreadcrumbCustom first="系统首页" />
                <Row gutter={10}>
                    <Col className="gutter-row" md={24}>
                        <div className="gutter-box">
                            <Card bordered={false}>
                                <h3 id="dashboard_title_1">后台统计</h3>
						
                            </Card>
                        </div>
                    </Col>
                </Row>                
            </div>
        )
    }
}
