import Getters from './getters';
import Mutations from './mutations';
import Actions from './actions';

export default {

    state: {
      showModal: false,
      item_id: '',
      description: '',

      form: {
        //code: '',
        //description: '',
        //rack_number: '',
        //recommended_qty: '',
        //category_id: '',
      },
    },

    getters: Getters,
    mutations: Mutations,
    actions: Actions,
};
