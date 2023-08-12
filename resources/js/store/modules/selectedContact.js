
const state = {
    selectedContact: null
};

const getters = {
    selectedContact: (state) => state.selectedContact
};

const mutations = {
    setSelectedContact (state, payload) {
        state.selectedContact = payload;
    },
};

export default {
    state,
    getters,
    mutations
}
