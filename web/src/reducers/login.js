import {
    LOGOUT_SUCCESS
} from '../actions/login';

const initialState = {
};

export default function login(state = initialState, action = {}) {
    switch (action.type) {
        case LOGOUT_SUCCESS:
            return Object.assign({}, state);

        default:
            return state;
    }
}
