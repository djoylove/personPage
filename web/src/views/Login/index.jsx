import React from 'react';
import PropTypes from 'prop-types';
import {Form, Input, Button, Row, Col, Icon, message} from 'antd';
import {bindActionCreators} from 'redux';
import {connect} from 'react-redux';
import {login} from '../../actions/login';

const FormItem = Form.Item;
const InputGroup = Input.Group;

import './index.less';

class Login extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            loading: false
        }
    }

    componentWillReceiveProps(nextProps) {
        const error = nextProps.loginErrors;
        const isLoggingIn = nextProps.loggingIn;
        const user = nextProps.user;

        this.setState({
            loading: false
        });

        if (error != this.props.loginErrors && error) {
            message.error(error);
        }

        if (!isLoggingIn && !error && user) {
            message.success('Welcome ' + user.name);
        }

        if (user) {
            this.context.router.replace('/pages1/home');
        }
    }

    handleSubmit(e) {
        e.preventDefault();
        this.setState({
            loading: true
        });
        const data = this.props.form.getFieldsValue();
        this.props.login(data.user, data.password);
    }

    render() {
        console.log("login");
        const {getFieldDecorator} = this.props.form;
        return (
            <Row className="login-row" type="flex" justify="space-around" align="middle">
                <Col span={8} md={12} xs={16}>
                    <Form layout="horizontal" onSubmit={this.handleSubmit.bind(this)} className="login-form">
                        <h2 className="logo"><span>logo</span></h2>
                        <FormItem>
                            <InputGroup>
                                {getFieldDecorator('user')(
                                    <Input placeholder='admin'/>
                                )}
                                <div className="ant-input-group-addon">
                                    <Icon type="user"/>
                                </div>
                            </InputGroup>
                        </FormItem>

                        <FormItem>
                            <InputGroup>
                                {getFieldDecorator('password')(
                                    <Input type='password'
                                           placeholder='123456'/>
                                )}
                                <div className="ant-input-group-addon">
                                    <Icon type="lock"/>
                                </div>
                            </InputGroup>
                        </FormItem>
                        <p>
                            <Button className="btn-login" type='primary' size="large" icon="poweroff"
                                    loading={this.state.loading} htmlType='submit'>确定</Button>
                        </p>
                    </Form>
                </Col>
            </Row>
        )
    }
}

Login.contextTypes = {
    router: PropTypes.object.isRequired,
    store: PropTypes.object.isRequired
};

Login.propTypes = {
    user: PropTypes.object,
    loggingIn: PropTypes.bool,
    loginErrors: PropTypes.string
};

Login = Form.create()(Login);

function mapStateToProps(state) {
    const {login} = state;
    if (login.user) {
        return {user: login.user, loggingIn: login.loggingIn, loginErrors: ''};
    }

    return {user: null, loggingIn: login.loggingIn, loginErrors: login.loginErrors};
}

function mapDispatchToProps(dispatch) {
    return {
        login: bindActionCreators(login, dispatch)
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(Login);


