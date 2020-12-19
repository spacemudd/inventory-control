<template>
    <div class="search-input">
        <div class="is-small select">
            <select v-model="searchby">
                <option value="po_number">Search by PO Number</option>
                <option value="vendor">Search by Vendor</option>
                <option value="location">Search by Location</option>
                <option value="cost_center_code">Search by Cost Center Code</option>
            </select>
        </div>

        <div class="dropdown is-active"  v-click-outside="onSearchBlur">
            <div class="dropdown-trigger">
                <p class="control has-icons-right has-icons-left is-expanded">
                    <input  ref="search"
                            id="search"
                            class="input is-small"
                            autocomplete="off"
                            type="text"
                            v-model="search"
                            @keyup.esc="onEscape"
                            @focus="onSearchFocus"
                            >
                    <span class="icon is-small is-left"><i class="fa fa-search"></i></span>
                    <span class="icon is-small is-right"><i :class="{'fa fa-circle-o-notch fa-spin fa-fw': loading}"></i></span>
                </p>
            </div>
        </div>


         <!--Results-->
        <div class="dropdown-menu" id="dropdown-menu" role="menu" v-if="open && results.length">
            <div class="dropdown-content">
                <div v-for="(result, index) in results" :key="index">
                    <a
                       :href="baseUrl()+'/purchase-orders/'+result.id"
                       class="dropdown-item result">
                        <div class="stock-result">
                            <p class="stock-description">
                                <span class="stock-code">{{result.remarks}}</span>
                                {{result.number}}
                            </p>
                            <div class="stats">
                                <div class="stats-value">
                                    <p><strong>Vendor </strong></p>
                                    <p>{{result.vendor.name}}</p>
                                </div>
                                <div class="stats-value">
                                    <p class="is-flex"><strong>Loc. </strong></p>
                                    <p>{{result.location.description}}</p>
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


    data()
    {
        return {
            search: '',
            loading: false,
            results: [],
            searchby: 'po_number'
        };
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
          this.results = [];
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
        onEscape() {
            if(!this.search.length) {
            this.$refs.search.blur();
            } else {
            this.search = '';
            }
            this.loading = false;
        },

         onSearchBlur() {
            this.$emit('search:blur');
            this.loading = false;
        },

        onSearchFocus() {
            this.$emit('search:focus');
        },

        sendSearchRequest: _.debounce(function(search) {
            this.loading = true;
             this.results = [];
             let vm = this;
            axios.get(this.apiUrl() + '/search/purchase-orders-custom-search', {
            params: {
                q: search,
                p: vm.searchby
            },
            }).then(response => {
                let res = response.data;
                
                if(res.length)
                {
       
                    if(vm.searchby=='po_number')
                        vm.results = res;
                    
                    else
                    {
                       _.forEach(res, function(value, key) {
                         
                           vm.results.push(value[0]);
                       })

                    }

                   

                }
              //  vm.results = response.data.data;
                vm.loading = false;
            })
        }, 1000),

     

    }

}
</script>

<style lang="sass" scoped>
    .stats
        
        font-family: "Lucida Console"
        font-size: 11px

    .stock-code
        display: block
        font-size: 12px
        font-weight: normal

    .stock-description
        
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