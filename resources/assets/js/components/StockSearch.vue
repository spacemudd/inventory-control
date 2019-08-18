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
        <div class="dropdown-menu" id="dropdown-menu" role="menu" v-if="open">
            <div class="dropdown-content">
                <div v-for="result in results" v-key="result.id">
                    <a
                       :href="baseUrl()+'/stock/'+result.id"
                       class="dropdown-item result">
                        {{ result.description }}
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
      this.loadStock();
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
      search() {
        if (this.search.trim() === '')
          this.results = this.stock;
        else
          this.results = this.stockFuse.search(this.search.trim());
      },
    },
    computed: {
      open() {
        return (this.results.length && this.search.trim().length);
      }
    },
    methods: {
      loadStock() {
        axios.get(this.apiUrl()+'/stocks').then(response => {
          this.stock = response.data;
          this.results = response.data;
          this.stockFuse = new window.Fuse(this.stock, this.fuseOptions);
        })
      },
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
        axios.get(this.apiUrl() + '/stocks', {
          params: {
            q: search
          },
        }).then(response => {
          this.results = response.data;
          this.loading = false;
        })
      }, 500),
    },

  }
</script>

<style lang="sass" scoped>
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
