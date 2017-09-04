/**
 * Created by liudonghui on 17/8/14.
 */
import api from '../api'

export const GETALLCITY = 'menu/reducer/GETALLCITY';
export const GETALLCITY_PENDING = 'menu/reducer/GETALLCITY_PENDING';
export const GETALLCITY_SUCCESS = 'menu/reducer/GETALLCITY_SUCCESS';

export function getAllCity() {
    return {
        type: GETALLCITY,
        payload: {
            promise: api.post('/app_update/get_all_city')
        }
    }
}
