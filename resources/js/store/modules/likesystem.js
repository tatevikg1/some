import axios from 'axios';

const state = {
    likeCount:0
};

const getters = {
    likeCount: (state) => state.likeCount
};

const actions = {
    async getLikeCount({ commit }, postId) {
        const response = await axios.get('/api/get-like-count/' + postId);
        commit('updateLikeCount', response.data);
    },

};

const mutations = {
    updateLikeCount : (state, likeCount) => (state.likeCount = likeCount)

};

export default {
    namespaced:true,
    state,
    getters,
    actions,
    mutations
};
