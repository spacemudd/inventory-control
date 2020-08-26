<template>
    <a @click="showConfirm()" class="button is-small is-danger">{{button_label}} </a>
    
</template>

<script>
export default {
props: {

            button_label: {
                type: String,
                required: true,
            },
            message: {
                type: String,
                required: true,
            },

            id: {
                type: String,
                required: true,
            }

        },

methods: {

    showConfirm() {
        let vm = this;
        this.$dialog.confirm({
                    type: 'is-danger',
                    message: vm.message,
                    confirmText: vm.button_label,
                    onConfirm: () => {
                        document.getElementById(vm.id).click();
                    }
                })
    }

}

    
}
</script>
