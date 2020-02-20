<template>
    <div class="is-fullwidth">
        <div class="label">Quotation #</div>
        <b-autocomplete
                v-model="search"
                :data="quotations"
                :loading="isLoading"
                field="code"
                @input="getData"
                @select="selectQuotation">
            <template slot="empty" v-if="!isLoading">No results found</template>
            <template slot-scope="props">
                <a class="dropdown-item">
                    <span v-if="props.option.vendor">({{ props.option.vendor.name }}) -</span> {{ props.option.vendor_quotation_number }}
                </a>
            </template>
        </b-autocomplete>
        <!-- HTML helper -->
        <input v-for="quote in selectedQuotations" type="hidden" name="quotation_ids[]" :value="quote.id">
        <ul class="content">
            <li v-for="quote in selectedQuotations">
                <div class="is-flex">
                    <div>
                        <span v-if="quote.vendor">
                            ({{ quote.vendor.name }}) -
                        </span>
                        {{ quote.vendor_quotation_number }}
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
  import debounce from "lodash/debounce";

  export default {
    name: "MultipleQuotationSelection",
    data() {
      return {
        isLoading: false,
        selectedQuotations: [],
        search: null,
        quotations: [],
      }
    },
    methods: {
      selectQuotation(quotation) {
        this.selectedQuotations.push(quotation);
      },
      getData: debounce(function () {
        this.quotations = []
        this.isLoading = true
        axios.get(this.apiUrl() + '/search/quotations' + '?q=' + this.search)
          .then(response => {
            response.data.data.forEach((item) => this.quotations.push(item))
            this.isLoading = false
          })
          .catch((error) => {
            this.isLoading = false
            throw error
          })
      }, 500),
    }
  }
</script>

<style scoped>

</style>
