export default {
    showNewModal(state, payload)
    {
        state.showNewModal = payload;
    },
    
    showEditInvoiceUpdateModal(state, payload)
    {
        state.showEditInvoiceUpdateModal = payload;
    },

    id(state, payload)
    {  
        state.id = payload

    }
}
