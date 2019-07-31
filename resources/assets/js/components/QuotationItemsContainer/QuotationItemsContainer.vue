<template>
    <div>
        <div class="columns">
            <div class="column">
                <p class="is-uppercase"><b>Quotation Items</b></p>
            </div>
            <div class="column has-text-right">
                <button class="button has-icon is-small is-warning"
                        v-if="canEdit"
                        @click="openNewItem"
                >
                    <span class="icon"><i class="fa fa-plus"></i></span>
                    <span>New Item</span>
                </button>
            </div>
        </div>

        <div class="columns">
            <div class="column">
                <loading-screen v-if="$isLoading('DELETING_ITEM')"></loading-screen>
                <table v-else class="table is-fullwidth is-bordered is-size-7">
                    <thead>
                    <tr>
                        <th width="20px" class="has-text-centered">#</th>
                        <th>Items</th>
                        <th width="100px" class="has-text-right">Unit Price</th>
                        <th width="70px" class="has-text-right">Quantity</th>
                        <th width="70px" class="has-text-right">Boxes</th>
                        <th width="100px" class="has-text-right">Amount</th>
                        <th v-if="canEdit" width="50px"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-if="items.length > 0" v-for="(item, key) in items" :id="'mRequest_' + item.material_request_item_id">
                        <td>{{ ++key }}</td>
                        <td>{{ item.description }}</td>
                        <td class="has-text-right">{{ toMoney(item.unit_price) }}</td>
                        <td class="has-text-right">{{ item.qty }}</td>
                        <td class="has-text-right">{{ item.qty_boxes }}</td>
                        <td class="has-text-right">{{ toMoney(item.unit_price * item.qty) }}</td>
                        <td class="has-text-centered" v-if="canEdit">
                            <button v-if="canEdit"
                                    @click="deleteItem(item, key)"
                                    class="button is-outlined is-danger has-icon is-small"
                                    :class="{'is-loading': $isLoading('DELETE_QUOTATION_ITEM_'+key)}"
                            >
                                <span class="icon is-small"><i class="fa fa-times"></i></span>
                            </button>
                        </td>
                    </tr>
                    <tr v-for="(mrequest, key) in materialRequests" :id="'mRequest_'+mrequest.id" >
                        <td>{{ ++key }}</td>
                        <td>
                            <input v-model="mrequest.description" type="text" readonly class="input is-small"/>
                            <input v-model="mrequest.id" type="hidden"/>

                            <!-- <b-input ref="newItemDescription" size="is-small" v-model="form.description" autofocus></b-input> -->
                            <!--<input type="text" v-model="mrequest.description" readonly>-->
                        </td>
                        <td>
                            <b-input @keyup.enter="saveRequest" size="is-small" type="number" v-model="mrequest.unit_price"></b-input></td>
                        <td>
                            <b-input size="is-small" type="number" v-model="mrequest.qty"></b-input>
                        </td>
                        <td class="has-text-right">
                            <b-input size="is-small" type="number" v-model=" mrequest.qty_boxes"></b-input>
                        </td>
                        <td>
                            <input type="text" :value="mrequest.qty * mrequest.unit_price" class="input is-small" style="background-color: #d5d5d5;" readonly>
                        </td>
                        <td class="has-text-centered">
                            <button class="button is-primary is-small saveButton"
                                    :class="{'is-loading': $isLoading('SAVING_QUOTATION_ITEM')}"
                                    @click="saveRequest(mrequest, key)">
                                Save
                            </button>
                        </td>
                    </tr>
                    <tr v-if="isAdding"  @keyup.enter="saveNewItem">
                        <td colspan="2">
                           <!-- <b-input ref="newItemDescription" size="is-small" v-model="form.description" autofocus></b-input> -->
                            <select-material-request-items @quantity="quantityNumber" v-model="form.material_request_item_id" v-bind:materialNumber="materialNumber"/>
                        </td>
                        <td>
                            <b-input @keyup.enter="saveNewItem" size="is-small" type="number" v-model="form.unit_price"></b-input>
                        </td>
                        <td>
                            <b-input size="is-small" type="number" v-model="form.qty" class="quantity"></b-input>
                        </td>
                        <td>
                            <b-input size="is-small" type="number" v-model="form.qty_boxes"></b-input>
                        </td>
                        <td>
                            <input type="text" :value="form.qty * form.unit_price" class="input is-small" style="background-color: #d5d5d5;" readonly>
                        </td>
                        <td class="has-text-centered">
                            <button class="button is-primary is-small"
                                    :class="{'is-loading': $isLoading('SAVING_QUOTATION_ITEM')}"
                                    @click="saveNewItem">
                                Save
                            </button>
                        </td>
                    </tr>
                    <tr class="has-text-centered" v-if="items.length === 0">
                        <td colspan="6"><p class="has-text-centered"><i>No items</i></p></td>
                    </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="has-text-right">Total Amount</td>
                            <td class="has-text-right">{{ totalAmount }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</template>


<script>
  import { EventBus } from '../../event-bus.js';

  export default {
    props: {
      quotationId: {
        type: Number,
        required: true,
      },
      canEdit: {
        type: Boolean,
        required: false,
        default: false
      },
      materialNumber: {
        type: String,
        required: true
      },
    },
    data() {
      return {
        newItemModal: false,
        isAdding: false,
        materialRequests: [],
        items: [],
        form: {
          material_request_item_id: '',
          qty: 1,
          unit_price: 1,
          qty_boxes: 0,
        },
      }
    },
    async mounted() {
      await this.getItems();
      EventBus.$on('mRequestItem', data => {
        data.unit_price = 1;
        this.materialRequests.push(data)
      });
      EventBus.$on('quotationItems', item => {
        console.log(item);
        item.unit_price = 1;
        this.materialRequests.push(item)
      });
    },
    computed: {
      showAttachItemToPoItemModal: {
        get() {
          return this.$store.getters['PurchaseRequisitionItem/showModal'];
        },
        set(value) {
          this.$store.commit('PurchaseRequisitionItem/showModal', value)
        }
      },
      totalAmount() {
        let total = 0;
        this.items.map(item => {
          total += item.qty * item.unit_price
        });

        return this.toMoney(total);
      }
    },
    methods: {
      quantityNumber(quantity) {
        this.form.qty = quantity;
      },
      toMoney(money) {
        var formatter = new Intl.NumberFormat('en-SA', {
          style: 'currency',
          currency: 'SAR',
        });

        return formatter.format(money);
      },
      getItems() {
        axios.get(this.apiUrl() + `/quotations/${this.quotationId}/items`)
          .then(response => {
            this.items = response.data;
            this.$endLoading('DELETING_ITEM');
            EventBus.$emit('getQuotationItems', response.data);
          })

      },
      /**
       * Attach a Purchase Requisition item to a PO.
       *
       * @param item Object
       */
      attachItemToPo(item) {
        this.$store.commit('PurchaseRequisitionItem/setItem', item);
        this.$store.commit('PurchaseRequisitionItem/showModal', true);
      },
      openNewItem() {
        this.isAdding = true;
      },
      saveNewItem() {
        // Validation
        if (!this.form.material_request_item_id) {
          alert('Please select an item from material requests');
          return;
        }

        this.$startLoading('SAVING_QUOTATION_ITEM');
        this.form.quotation_id = this.quotationId;
        this.form.unit_price = parseFloat(this.form.unit_price);
        axios.post(this.apiUrl() + `/quotations/`+this.quotationId+`/items/store`, this.form)
          .then(response => {
            this.items.push(response.data);
            this.form.material_request_item_id = null;
            this.form.qty = 1;
            this.form.unit_price = 1;
            this.form.qty_boxes = 0;
            this.$endLoading('SAVING_QUOTATION_ITEM');
            document.getElementById("quotationSaveItems").disabled = false

            //this.$refs.newItemDescription.focus();
          })
          .catch(error => {
            alert(error.response.data.message);
            this.$endLoading('SAVING_QUOTATION_ITEM');
          })
      },
      /**
       * @param item Object
       */
      deleteItem(item, key) {
        var newItem = []
        newItem.push(item)
        EventBus.$emit('itemId', item);
        this.$startLoading('DELETE_QUOTATION_ITEM_' + key)
        axios.delete(this.apiUrl() + `/quotations/${this.quotationId}/items/${item.id}`)
          .then((response) => {
            this.items.splice(key - 1, 1);
            console.log(newItem);
            this.$endLoading('DELETE_QUOTATION_ITEM_' + key);
          });

      },

      saveRequest(mrequest, key) {
        if (!mrequest.description) {
          alert('Please select an item from material requests');
          return false;
        }
        this.materialRequests.splice(key - 1, 1);
        this.$startLoading('SAVING_QUOTATION_ITEM');
        mrequest.quotation_id = this.quotationId;
        mrequest.material_request_item_id = mrequest.id;
        axios.post(this.apiUrl() + `/quotations/`+this.quotationId+`/items/store`, mrequest)
          .then(response => {
            this.items.push(response.data);
            this.$endLoading('SAVING_QUOTATION_ITEM');
            document.getElementById("quotationSaveItems").disabled = false
          })
          .catch(error => {
            alert(error.response.data.message);
            this.$endLoading('SAVING_QUOTATION_ITEM');
          })
      },
      clearNewItemForm() {
        this.form.description = '';
        this.form.qty = 1;
        this.form.unit_price = 1;
        this.form.qty_boxes = 0;
        this.isAdding = false;
      },
    }
  }
</script>
