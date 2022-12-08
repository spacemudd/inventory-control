import { createActionHelpers } from 'vuex-loading';
const { startLoading, endLoading } = createActionHelpers({
    moduleName: 'loading'
});

export default {
    newDepartment(context) {
        startLoading(context.dispatch, 'CREATING_DEPARTMENT');
    },
}
