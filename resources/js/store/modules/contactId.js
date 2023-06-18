
const state = {
    contactId:0
};

const getters = {
    contactId: (state) => state.contactId
};

const mutations = {
    setContactId (state, payload) {
        state.contactId = payload;
    },
};

export default {
    state,
    getters,
    mutations
}
