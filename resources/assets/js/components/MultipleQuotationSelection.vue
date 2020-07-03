<template>
    <div class="is-fullwidth">
        <div class="label">Quotation #</div>
        <!--
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
        -->
        <div class="is-flex">
            <input type="text" v-model="quotationNumber" class="input"><button @click.prevent="addQuotation" class="button is-primary" style="border-top-left-radius: 0;border-bottom-left-radius: 0;">Add</button>
        </div>
        <span class="has-text-secondary is-help is-size-7">Add single or multiple quotations to the cost approval.</span>
        <!-- HTML helper -->
        <input v-for="(quote, index) in selectedQuotations" :key="index" type="hidden" name="quotation_numbers[]" :value="quote">
        <ul class="content">
            <li v-for="(quote, index) in selectedQuotations" :key="index">
                <div class="is-flex">
                    <div>
                        {{ quote }} <a @click="removeQuotation(index)" class="delete is-small"></a>
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
        quotationNumber: '',
      }
    },
    methods: {
      selectQuotation(quotation) {
        this.selectedQuotations.push(quotation);
      },
      addQuotation() {
        if (this.quotationNumber) {
          this.selectedQuotations.push(this.quotationNumber);
          this.quotationNumber = null;
        }
      },

      removeQuotation(index){
        this.selectedQuotations.splice(index, 1)         
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
