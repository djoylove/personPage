import api from '../api'

export const LOGOUT = 'login/reducer/LOGOUT';
export const LOGOUT_SUCCESS = 'login/reducer/LOGOUT_SUCCESS';

export function logout() {
    return {
        type: LOGOUT,
        payload: {
            promise: api.post('/user_permission/logout')
        }
    }
}
