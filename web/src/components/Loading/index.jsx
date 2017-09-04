import React from 'react';
import PropTypes from 'prop-types';
import './index.less';

const Loading = ({loading}) => {
    console.log("loading");
    let display = 'none';
    if(loading) {
        display = 'block';
    }
    return (
        <div className="loading-wrap" style={{display:display}}>
            <div className="loading">
                <img src={require('../../assets/img/loading_circle.gif')} alt=""/>
                <span>玩命加载中...</span>
            </div>
        </div>
    )
}

Loading.propTypes = {
    loading: PropTypes.bool.isRequired
}

export default Loading;