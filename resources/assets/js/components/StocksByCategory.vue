<template>
    <div>
        <button v-if="checkedRows.length"
                class="button is-small is-danger is-pulled-right"
                @click="deleteStocks"
                style="margin-bottom:10px;">
            Delete ({{ checkedRows.length }})
        </button>
        <b-table :data="stocks"
             :paginated="false"
             sort-icon-size="is-small"
             class="is-size-7"
             :striped="true"
             custom-row-key="id"
             :checked-rows.sync="checkedRows"
             checkable
             default-sort="description">
        <template slot-scope="props">
            <b-table-column field="code" label="Code" width="50" sortable>
                {{ props.row.code }}
            </b-table-column>
            <b-table-column field="description" label="Description" sortable>
                {{ props.row.description }}
            </b-table-column>
            <b-table-column field="rack_number" width="100" label="Rack No." sortable>
                {{ props.row.rack_number }}
            </b-table-column>
            <b-table-column field="on_hand_quantity" width="100" label="Avail. Qty" sortable>
                {{ props.row.on_hand_quantity }}
            </b-table-column>
            <b-table-column field="recommended_qty" width="150" label="Recommended Qty" sortable>
                {{ props.row.recommended_qty }}
            </b-table-column>
            <b-table-column field="actions" width="150" label="Actions" sortable numeric>
                <a :href="baseUrl()+'/stock/'+props.row.id+'/edit'"
                   class="button is-small is-warning">
                    Edit
                </a>
            </b-table-column>
        </template>
    </b-table>
    </div>
</template>

<script>
  export default {
    props: {
      categoryId: {
        type: Number,
        required: true,
      }
    },
    data() {
      return {
        loading: true,
        stocks: [],
        checkedRows: [],
      }
    },
    computed: {
      checkedRowsIds() {
        return this.checkedRows.map((row) => {
          return row.id;
        })
      },
    },
    mounted() {
      this.loadResults();
    },
    methods: {
      /**
       * Load stocks by a specific category.
       *
       */
      loadResults() {
        this.loading = true;
        axios.get(this.baseUrl()+'/categories/'+this.categoryId+'/stock')
          .then(response => {
            this.stocks = response.data;
            this.loading = false;
          }).catch(error => {
          alert('An error occurred during getting stocks data.');
          throw error;
        }).finally(() => {
          this.loading = false;
        })
      },
      /**
       * Deletes stocks in bulk.
       *
       */
      deleteStocks() {
        axios.delete(this.apiUrl()+'/stocks/bulk', {params: {ids: this.checkedRowsIds}})
          .then(response => {
            this.loadResults();
            this.checkedRows = [];
          }).catch(err => {
          alert(error.response.data.message);
        })
      },
    }
  }
</script>

<style lang="sass">
    //
</style>
