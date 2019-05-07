import JobOrderGetters from './getters';
import JobOrderMutations from './mutations';
import JobOrderActions from './actions';

export default {
    state: {
        previewPdf: false
    },
    getters: JobOrderGetters,
    mutations: JobOrderMutations,
    actions: JobOrderActions,
};
