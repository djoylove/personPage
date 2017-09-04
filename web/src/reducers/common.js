/**
 * Created by liudonghui on 17/8/14.
 */
import {
    GETALLCITY_PENDING,
    GETALLCITY_SUCCESS
} from '../actions/common';


const initialState = {
    cities: []
};

export default function common(state = initialState, action = {}) {
    switch (action.type) {
        case GETALLCITY_PENDING:
            return Object.assign({}, state, {
                cities: []
            });
        case GETALLCITY_SUCCESS:
            return Object.assign({}, state, {
                cities: (action.payload.data && action.payload.data.data && action.payload.data.data.data) || []
            });

        default:
            return state;
    }
}
