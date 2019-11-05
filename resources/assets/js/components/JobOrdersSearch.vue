<template>
    <div class="search-input">
        <div class="dropdown is-active" v-click-outside="onSearchBlur">
            <div class="dropdown-trigger">
                <p class="control has-icons-right has-icons-left is-expanded">
                    <input ref="search"
                            id="search"
                            class="input"
                            autocomplete="off"
                            type="text"
                            v-model="search"
                            @keyup.esc="onEscape"
                            @focus="onSearchFocus">
                    <span class="icon is-small is-left"><i class="fa fa-search"></i></span>
                    <span class="icon is-small is-right"><i :class="{'fa fa-circle-o-notch fa-spin fa-fw': loading}"></i></span>
                </p>
            </div>
        </div>

        <!--Results-->
        <div class="dropdown-menu" id="dropdown-menu" role="menu" v-if="open && results.length">
            <div class="dropdown-content">
                <div v-for="result in results">
                    <a
                       :href="baseUrl()+'/job-orders/'+result.job_order_number"
                       class="dropdown-item result">
                        <div class="stock-result">
                            <p class="stock-description">
                                <span class="stock-code" v-if="result.job_order_number">{{ result.human_date }}</span>
                                <b>{{ result.job_order_number }}</b>
                            </p>
                            <div class="stats">
                                <div class="stats-value" style="display:block;" v-if="result.department">
                                    <p>Cost Center</p>
                                    <p>{{ result.department.code }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
  export default {
    name: "StockSearch",
    mounted() {
      // this.loadStock();
    },
    data() {
      return {
        stock: [],
        results: [],
        stockFuse: null,
        target: null,
        search: '',
        loading: false,
        fuseOptions: {
          shouldSort: true,
          threshold: 0.6,
          location: 0,
          distance: 100,
          maxPatternLength: 32,
          minMatchCharLength: 1,
          keys: [
            "description",
          ],
        },
      }
    },
    watch: {
      //search() {
      //  if (this.search.trim() === '')
      //    this.results = this.stock;
      //  else
      //    this.results = this.stockFuse.search(this.search.trim());
      //},
      search() {
        if(this.search.length > 0) {
          this.sendSearchRequest(this.search);
          this.$emit('search', this.search);
          //this.open = true;
        }

        if(!this.search) {
          //this.open = false;
          this.searchResults = [];
          this.loading = false;
        }
      },
    },
    computed: {
      open() {
        return (this.results.length && this.search.trim().length);
      }
    },
    methods: {
      //loadStock() {
      //  axios.get(this.apiUrl()+'/stocks').then(response => {
      //    this.stock = response.data;
      //    this.results = response.data;
      //    this.stockFuse = new window.Fuse(this.stock, this.fuseOptions);
      //  })
      //},
      onSearchBlur() {
        this.$emit('search:blur');
        this.loading = false;
      },
      onEscape() {
        if(!this.search.length) {
          this.$refs.search.blur();
        } else {
          this.search = '';
        }
        this.loading = false;
      },
      onSearchFocus() {
        this.$emit('search:focus');
      },
      sendSearchRequest: _.debounce(function(search) {
        this.loading = true;
        this.results = [];
        axios.get(this.apiUrl() + '/search/job-orders', {
          params: {
            q: search
          },
        }).then(response => {
          this.results = response.data.data;
          this.loading = false;
        })
      }, 500),
    },

  }
</script>

<style lang="sass" scoped>
    .stats
        width: 100px
        font-family: "Lucida Console"
        font-size: 11px

    .stock-code
        display: block
        font-size: 12px
        font-weight: normal

    .stock-description
        max-width: 300px
        word-break: break-all
        white-space: normal

    .stats-value
        display: flex
        justify-content: space-between

    .stats-value:nth-child(2)
        border-top: 1px solid lightgrey

    .stock-result
        display: flex
        justify-content: space-between

    .dropdown-menu
        display: block
        top: unset
        left: unset

    .level
        -webkit-box-pack: justify

    .search-input
        min-width: 350px
    .left
        float: left
        min-width: 350px
    .right
        float: right
    .btn
        background-color: #f5f5f6
        height: 36px
        border-top-left-radius: 0
        border-bottom-left-radius: 0
        padding-left: 10px
    .dropdown-toggle, .clearfix
        border-top-right-radius: 0
        border-bottom-right-radius: 0

    .dropdown-content
        overflow: auto
        max-width: 500px
        max-height: 500px

</style>
