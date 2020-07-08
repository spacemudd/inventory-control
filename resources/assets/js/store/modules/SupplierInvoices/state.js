/*
Recreated this Vuex Store 
*/


import Getters from './getters';
import Mutations from './mutations';
import Actions from './actions';

export default {

    state: {
      showNewModal: false,
      showEditInvoiceUpdateModal: false, //for testing
      form: {
        code: '',
        description: '',
        head_department: '',
        location_id: '',
      },
      id: '',
    },

    getters: Getters,
    mutations: Mutations,
    actions: Actions,
};
