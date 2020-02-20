<template>
    <span>
        <input type="hidden" v-if="selectedQuotation" :name="name" :value="selectedQuotation.id">

        <input v-if="selectedQuotation"
               type="text"
               class="input"
               :value="selectedQuotation.vendor_quotation_number"
               @click="selectedQuotation=search=null"
               readonly>
        <!-- When searching -->
        <b-autocomplete
                    v-else
                    v-model="search"
                    :data="quotations"
                    :loading="isLoading"
                    field="code"
                    @input="getData"
                    @select="selectQuotation">
            <template slot="empty" v-if="!isLoading">No results found</template>
            <template slot-scope="props">
                <a class="dropdown-item">
                    {{ props.option.vendor_quotation_number }} <span v-if="props.option.vendor">- ({{ props.option.vendor.name }})</span>
                </a>
            </template>
        </b-autocomplete>
    </span>
</template>

<script>
    import debounce from "lodash/debounce";

    export default {
        props: {
            /**
             * api endpoint to search for quotations.
             */
            url: {
                type: String,
                required: true,
            },
            name: {
                type: String,
                required: false,
            }
        },
        data() {
            return {
                isLoading: false,
                selectedQuotation: null,
                search: null,
                quotations: [],
            }
        },
        mounted() {

        },
        methods: {
            getData: debounce(function () {
                this.quotations = []
                this.isLoading = true
                axios.get(this.url + '?q=' + this.search)
                    .then(response => {
                        response.data.data.forEach((item) => this.quotations.push(item))
                        this.isLoading = false
                    })
                    .catch((error) => {
                        this.isLoading = false
                        throw error
                    })
            }, 500),
            selectQuotation(quotation) {
                this.selectedQuotation = quotation;
                this.$emit('selected', quotation);
            }
        }
    }
</script>
