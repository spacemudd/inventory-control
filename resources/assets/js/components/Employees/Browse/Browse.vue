<template>
	<div class="columns">
		<div class="column is-6">
            <!-- Manage staff types modal... -->
			<b-modal :active.sync="showManageStaffTypesModal">
				<manage-staff-types></manage-staff-types>
			</b-modal>
			<simple-search
					:hyper-linked-results="true"
					placeholder-text="Find employees"
					size="is-small"
					search-endpoint="employees">
			</simple-search>
		</div>
		<div class="column is-6 has-text-right">
			<button class="button" @click.prevent="toggleManageStaffTypes()">Manage types</button>
			<create-employee v-if="canCreate"></create-employee>
			<slot name="buttons"></slot>
		</div>
	</div>
</template>

<script>
    import ManageStaffTypes from "../ManageStaffTypes";
	export default {
		components: {ManageStaffTypes},
		props: {
            canCreate: {
                type: Number,
                required: false,
                default: 0,
            },
        },
        data() {
            return {
                //
            }
        },
        mounted() {
            //
        },
		computed: {
			showManageStaffTypesModal() {
				return this.$store.getters['Employee/showManageStaffTypesModal'];
			},
		},
        methods: {
			toggleManageStaffTypes() {
				this.$store.commit('Employee/toggleManageStaffTypesModal', true);
			},
        }
    }
</script>
