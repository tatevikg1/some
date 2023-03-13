import axios from 'axios';


const state = {
    likeCount:0
};

const getters = {
    likeCount: (state) => state.likeCount
};

const actions = {
    async getLikeCount({ commit },postId) {
        // const response = await axios.get('/api/get-like-count/' + postId);
        // console.log(response.data);
        
        // commit('updateLikeCount', response.data);

        return new Promise((resolve, reject) => {

            axios.get('/api/get-like-count/' + postId).then(response => {

                resolve(response);  
            }, error => {

                reject(error);
            })
        })
    },

    

};

const mutations = {
    // updateLikeCount (state, payload) {
    // state.likeCount = payload;
    // }

};

export default {
    state,
    getters,
    actions,
    mutations
}