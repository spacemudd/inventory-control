<template>
    <div>
        <b-tabs>
            <b-tab-item label="All">
                <button v-if="checkedRows.length"
                        class="button is-small is-danger is-pulled-right"
                        @click="deleteStocks"
                        style="margin-bottom:10px;">
                    Delete ({{ checkedRows.length }})
                </button>
                <div v-if="loading">
                    <loading-screen size="is-large"></loading-screen>
                </div>
                <b-table :data="stocks"
                         :loading="loadingResults"

                         paginated
                         backend-pagination

                         :total="total"
                         :per-page="perPage"
                         @page-change="onPageChange"

                         aria-next-label="Next page"
                         aria-previous-label="Previous page"
                         aria-page-label="Page"
                         aria-current-label="Current page"

                         backend-sorting
                         backend-sorting
                         :default-sort-direction="defaultSortOrder"
                         :default-sort="[sortField, sortOrder]"
                         @sort="onSort"

                         sort-icon-size="is-small"
                         custom-row-key="id"
                         class="is-size-7"
                         :striped="true"
                         default-sort="description"
                         :checked-rows.sync="checkedRows"
                         checkable>
                    <template slot-scope="props">
                        <b-table-column field="code" label="Code" width="50" sortable>
                            {{ props.row.code }}
                        </b-table-column>
                        <b-table-column field="category.name" width="150" label="Category" sortable>
                            {{ props.row.category ? props.row.category.name : '' }}
                        </b-table-column>
                        <b-table-column field="description" label="Description" sortable>
                            {{ props.row.description }}
                        </b-table-column>
                        <b-table-column field="rack_number" width="100" label="Rack No." sortable>
                            {{ props.row.rack_number }}
                        </b-table-column>
                        <b-table-column field="on_hand_quantity" width="100" label="Avail. Qty">
                            {{ props.row.on_hand_quantity }}
                        </b-table-column>
                        <b-table-column field="recommended_qty" width="150" label="Recommended Qty">
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
            </b-tab-item>
            <b-tab-item v-for="category in categories"
                        v-bind:key="category.id"
                        :label="category.name">
                <div v-if="loading">
                    <loading-screen size="is-large"></loading-screen>
                </div>
                <stocks-by-category :category-id="category.id"></stocks-by-category>
            </b-tab-item>
        </b-tabs>
    </div>
</template>

<script>
  export default {
    props: {
      categories: {
        type: Array,
        required: false,
      },
    },
    data() {
      return {
        loading: false,
        stocks: [],
        checkedRows: [],
        loadingResults: false,
        total: 0,
        page: 1,
        perPage: 15,
        defaultSortOrder: 'desc',
        sortField: 'description',
        sortOrder: 'desc',
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
      // Load stocks data.
      this.loadResults();
    },
    methods: {
      /**
       * Load stocks data.
       *
       */
      loadResults() {
        this.loadingResults = true;

        const params = [
          `sort_by=${this.sortField}.${this.sortOrder}`,
          `page=${this.page}`
        ].join('&');

        axios.get(this.apiUrl()+`/stocks?${params}`)
          .then(response => {
            //this.stocks = response.data;
            this.stocks = [];
            this.total = response.data.total;
            //let currentTotal = response.data.total
            //if (response.data.total / this.page > 1000) {
            //  currentTotal = this.page * 1000
            //}
            //this.total = currentTotal

            this.page = response.data.current_page
            response.data.data.forEach((item) => {
              this.stocks.push(item)
            })
            this.loadingResults = false;
          }).catch(error => {
            alert('An error occurred during getting stocks data.');
            throw error;
        }).finally(() => {
          this.loadingResults = false;
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
      onPageChange(page) {
        this.page = page
        this.loadResults()
      },
      onSort(field, order) {
        this.sortField = field
        this.sortOrder = order
        this.loadResults()
      },
    }
  }
</script>
