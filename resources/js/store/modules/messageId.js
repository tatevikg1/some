
const state = {
    messageId:0
};

const getters = {
    messageId: (state) => state.messageId
};

const mutations = {
    setMessageId (state, payload) {
        state.messageId = payload;
    },
};

export default {
    state,
    getters,
    mutations
}
