import React from 'react';
import PropTypes from 'prop-types';
import {bindActionCreators} from 'redux';
import {connect} from 'react-redux';
import { Menu, Icon } from 'antd';

class Dashboard extends React.Component {
    constructor(props) {
        super(props);
    }

    componentDidMount() {
    }

    render() {
        console.log("Dashboard");
        return (
            <div className="dashboard">
              
            </div>
        )
    }
}


Dashboard.propTypes = {
};


const mapStateToProps = (state) => {
    return {
    };
};

function mapDispatchToProps(dispatch) {
    return {
    };
}

export default connect(mapStateToProps, mapDispatchToProps)(Dashboard);