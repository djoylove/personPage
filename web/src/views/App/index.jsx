import React from 'react';
import PropTypes from 'prop-types';
import {bindActionCreators} from 'redux';
import {connect} from 'react-redux';
import Loading from '../../components/Loading';
import { Layout, Menu, Breadcrumb } from 'antd';
import { Carousel } from 'antd';

import './index.less';


const { Header, Content, Footer } = Layout;

class App extends React.Component {
    constructor(props) {
        super(props);
    }

    componentWillMount() {

    }

    componentDidMount() {
    }

    componentWillReceiveProps(nextProps, nextState) {
    }

    componentWillUnmount() {
    }

    render() {
        console.log("App");
        const {httpLength} = this.props;
        return (
            <div className="app">
                <Layout className="layout">
                    <Header>
                        <div className="logo" >
                            <h1>So Be It</h1>
                        </div>
                        <Menu
                            mode="horizontal"
                            defaultSelectedKeys={['2']}
                            style={{ lineHeight: '64px' }}
                        >
                            <Menu.Item key="1">首页</Menu.Item>
                            <Menu.Item key="2">little tric</Menu.Item>
                            <Menu.Item key="3">bread</Menu.Item>
                        </Menu>
                    </Header>
                    <Content style={{ padding: '20 50px'}}>
                        <Carousel adaptiveHeight autoplay>
                            <div><img src="http://app.guazistatic.com/test_dhl1.jpg" /></div>
                            <div><img src="http://app.guazistatic.com/test_dhl2.jpg" /></div>
                            <div><img src="http://app.guazistatic.com/test_dhl3.jpg" /></div>
                            <div><img src="http://app.guazistatic.com/test_dhl4.jpg" /></div>
                        </Carousel>
                        {this.props.children}
                    </Content>
                    <Footer style={{ textAlign: 'center' }}>
                       so be it ©2016 Created by djoy
                    </Footer>
                </Layout>
                <Loading loading={httpLength == 0 ? false: true}></Loading>
            </div>
        );
    }
}

App.propTypes = {
    children: PropTypes.node,
    httpLength: PropTypes.number
};

const mapStateToProps = (state) => {
    return {
        httpLength: state.load.httpLength
    };
};

function mapDispatchToProps(dispatch) {
    return {
    };
}

export default connect(mapStateToProps, mapDispatchToProps)(App);
