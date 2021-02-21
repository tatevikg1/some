import Vue from 'vue';
import Vuex from 'vuex';
import likesystem from './modules/likesystem';

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    modules: {
        likesystem,
    },
    strict: debug,
})