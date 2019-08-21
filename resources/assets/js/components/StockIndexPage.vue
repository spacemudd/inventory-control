<template>
    <div>
        <b-tabs>
            <b-tab-item label="All">
                <b-table :data="stocks"
                         :paginated="false"
                         sort-icon-size="is-small"
                         class="is-size-7"
                         :striped="true"
                         default-sort="description">
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
            </b-tab-item>
            <b-tab-item v-for="category in categories"
                        v-bind:key="category.id"
                        :label="category.name">
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
      }
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
        axios.get(this.apiUrl()+'/stocks')
          .then(response => {
            this.stocks = response.data;
          }).catch(error => {
            alert('An error occurred during getting stocks data.');
            throw error;
        })
      },
    }
  }
</script>
