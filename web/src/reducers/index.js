import { combineReducers } from 'redux';
import login from './login';
import load from './load';
import common from './common';

const rootReducer = combineReducers({
  login,
  load,
  common
});

export default rootReducer;
