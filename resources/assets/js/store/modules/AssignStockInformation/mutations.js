export default {
    showModal(state, payload)
    {
        state.showModal = payload;
    },
    loadItem(state, payload)
    {
        state.item_id = payload.item_id;
        state.description = payload.description;
    }
}
