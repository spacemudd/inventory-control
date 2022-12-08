export default {
    showNewModal(state, payload)
    {
        state.showNewModal = payload;
    },
    toggleManageLocationsModal(state)
    {
        state.showManageLocationsModal = !state.showManageLocationsModal;
    }
}
