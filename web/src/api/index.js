import {addHttpLoadLength, delHttpLoadLength} from '../actions/load';
import axios from 'axios';
import {store} from '../store';

let pending = []; //声明一个数组用于存储每个ajax请求的取消函数和ajax标识
let cancelToken = axios.CancelToken;
let removePending = (config) => {
    for(let p in pending){
        if(pending[p].u === config.url + '&' + config.method) { //当当前请求在数组中存在时执行函数体
            pending[p].f(); //执行取消操作
            pending.splice(p, 1); //把这条记录从数组中移除
        }
    }
}

var normalAxios = axios.create({
    baseURL: process.env.NODE_ENV == 'development' ? '/api' : 'https://wx-bg.guazi.com/api',
    withCredentials: true,
    timeout: 4000
});

///////统一处理所有http请求和响应, 在请求发出与返回时进行拦截, 在这里可以做loading页面的展示与隐藏, token失效是否跳转到登录页等事情;
normalAxios.interceptors.request.use(config => {
    // Do something before request is sent
    // if(config.url && config.url.indexOf('/app_role/find_user') < 0) {
    //     store && store.dispatch(addHttpLoadLength());
    // }
    store && store.dispatch(addHttpLoadLength());
    removePending(config); //在一个ajax发送前执行一下取消操作
    config.cancelToken = new cancelToken((c)=>{
        // 这里的ajax标识我是用请求地址&请求方式拼接的字符串，当然你可以选择其他的一些方式
        pending.push({ u: config.url + '&' + config.method, f: c });
    });
    return config;
}, error => {
    // Do something with request error
    // if(error && error.config && error.config.url && error.config.url.indexOf('/app_role/find_user') < 0) {
    //     store && store.dispatch(delHttpLoadLength());
    // }
    store && store.dispatch(delHttpLoadLength());
    return Promise.reject(error);
});

normalAxios.interceptors.response.use(response => {
    // Do something with response data
    // if(response && response.config && response.config.url && response.config.url.indexOf('/app_role/find_user') < 0) {
    //     store && store.dispatch(delHttpLoadLength());
    // }
    store && store.dispatch(delHttpLoadLength());
    removePending(response.config);  //在一个ajax响应后再执行一下取消操作，把已经完成的请求从pending中移除
    if(response.data && response.data.code && response.data.code != 0) {
        alert(response.data.message || response.data.data || '操作失败!');
    }else if(response.data && response.data.code == 0) {
        if(response.config && response.config.params && response.config.params.showMsg) {
            alert(response.data.message || '操作成功!')
        }
    }
    return {
        data: response.data
    };
}, error => {
    // Do something with response error
    // if(error && error.config && error.config.url && error.config.url.indexOf('/app_role/find_user') < 0) {
    //     store && store.dispatch(delHttpLoadLength());
    // }
    store && store.dispatch(delHttpLoadLength());
    if(error && error.response && error.response.status == '401') {
        var ssoURL = (error && error.response && error.response.data && error.response.data.data) || '';
        location.href = ssoURL + encodeURIComponent(location.href);
    }else if(error && error.response && error.response.data && error.response.data.message) {
        alert(error.response.data.message);
    }else if(error && error.message) {
        alert(error.message);
    }
    return Promise.reject(error.response && error.response.data);
});

export default normalAxios;
