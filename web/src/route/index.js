import React, {Component} from 'react';
import {Route, IndexRedirect} from 'react-router';

import App from '../views/App';
import Dashboard from '../views/Dashboard';

export default (store) => {

    // 按需加载组件, 不过有问题, 依赖的ant - design的组件还是在每一个子模块中都加载了
    // const Home = (location, cb) => {
    //     require.ensure([], require => {
    //         cb(null, require('../views/Home'))
    //     }, 'home')
    // };

    const validate = async (next, replace, callback) => {
        callback();
    };

    const validateApp = async (next, replace, callback) => {
        callback();
    };

    return (
        <Route path="/">
            <IndexRedirect to="dashboard"/>
            <Route component={App}>
                <Route path="dashboard" component={Dashboard}></Route>
            </Route>
            <Route path="*" component={Error}/>
        </Route>
    )
}