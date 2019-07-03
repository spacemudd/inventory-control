import Getters from './getters';
import Mutations from './mutations';
import Actions from './actions';

export default {

  state: {
    showManageLocationsModal: false,
    showNewModal: false,
    form: {
     location: '',
    },
  },

  getters: Getters,
  mutations: Mutations,
  actions: Actions,
};
