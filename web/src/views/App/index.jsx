import React from 'react';
import PropTypes from 'prop-types';
import {bindActionCreators} from 'redux';
import {connect} from 'react-redux';
import Loading from '../../components/Loading';

import './index.less';

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
                {this.props.children}
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
