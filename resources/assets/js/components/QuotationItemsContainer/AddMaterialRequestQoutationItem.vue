<template>
    <div>
        <div class="columns">
            <div class="column">
                <p class="is-uppercase"><b>Request items</b></p>
            </div>
        </div>

        <div class="columns">
            <div class="column">
                <loading-screen class="is-small" v-if="$isLoading('DELETING_ITEM') || $isLoading('LOADING_ITEMS')"></loading-screen>
                <table class="table is-fullwidth is-bordered is-size-7">
                    <thead>
                    <tr>
                        <th width="50px" class="has-text-centered">#</th>
                        <th>Items</th>
                        <th width="50px" class="has-text-right">Quantity</th>
                        <th width="50px">
                            <button class="button is-danger is-small saveButton" id="saveAllItems" v-on:click="addAllItem" >
                                All
                            </button>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, key) in items">
                            <td>{{ ++key }}</td>
                            <td>{{ item.description }}</td>
                            <td class="has-text-right">{{ item.qty }}</td>
                            <td>
                                <button class="button is-primary is-small saveButton" v-on:click="addQuotation(item, key)" :id="'item_'+item.id" :disabled="item.added">
                                    +
                                </button>
                            </td>
                        </tr>
                        <tr class="has-text-centered" v-if="items.length === 0">
                            <td colspan="5"><p class="has-text-centered"><i>No items</i></p></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
  import { EventBus } from '../../event-bus.js';
  import Vue from 'vue';

  export default {
    props: {
      materialRequestId: {
        type: Number,
        required: true,
      },
      quotationItemId: {
        type: Array,
        required: false,
      }

    },
    data() {
      return {
        items: [],
        newItems: [],
        itemsCount: 0,
        form: {
          material_request_id: this.material_request_id,
          description: '',
          qty: 1,
        },
      }
    },
    async mounted() {
      await this.getItems();
      this.sendItems();

      if(this.items.length == this.quotationItemId.length) {
        document.getElementById('saveAllItems').disabled = true;
      }

      let itemLength = this.items.length;
      for(var i = 0; i < itemLength; i++ ) {
        var check = await this.checkExistence(this.items[i].id);
        Vue.set(this.items[i], 'added', check);
      }
    },
    methods: {
      checkExistence(id) {
          var index = this.quotationItemId.findIndex(x => x.material_request_item_id == id);
        if (index === -1) {
          return false;
        } else {
          return true;
        }
      },
      getItems() {
        this.$startLoading('LOADING_ITEMS');
        return axios.get(this.apiUrl() + `/material-requests/${this.materialRequestId}/items`)
          .then(response => {
            this.items = response.data;
            this.$endLoading('LOADING_ITEMS');
            EventBus.$emit('getQuoteItem', response.data);
          })

      },
      sendItems() {
        EventBus.$on('itemId', item => {
          for(var i = 0; i <= this.items.length; i++) {
            if(item.material_request_item_id == this.items[i].id) {
              this.items[i].added = false
              document.getElementById('saveAllItems').disabled = false;
              document.getElementById('item_'+item.material_request_item_id).disabled = false;
              break;

            }
          }
        });
      },
      addQuotation(item, key) {
        this.itemsCount++;
        document.getElementById('item_'+item.id).disabled = true;
        axios.get('makeQuotationItem/change/'+item.id) .then(response => {
          response.data.added = true;
          this.items[key -1].added = true;
          EventBus.$emit('mRequestItem', response.data);
        });
        if(this.items.length == this.itemsCount) {
          document.getElementById('saveAllItems').disabled = true;
        }
      },
       addAllItem() {
         console.log(this.items);
         for (var i = 0; i <= this.items.length; i++) {
          document.getElementsByClassName('saveButton')[i].disabled = true;
          if(this.items[i].added === false){
            this.items[i].added = true
            EventBus.$emit('quotationItems', this.items[i])
            console.log(this.items[i]);
          }
        }
      }
    }
  }
</script>

