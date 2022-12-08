import JobOrderGetters from './getters';
import JobOrderMutations from './mutations';
import JobOrderActions from './actions';

export default {
    state: {
        jobOrder: null,
    },
    getters: JobOrderGetters,
    mutations: JobOrderMutations,
    actions: JobOrderActions,
};
