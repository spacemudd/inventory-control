<template>
    <div>
        <table class="table is-fullwidth is-narrow is-size-7">
            <colgroup>
                <col style="width:10px;">
                <col>
                <col style="width:100px;">
                <col style="width:130px;">
                <col style="width:100px;">
                <col style="width:100px;">
            </colgroup>
            <thead>
            <tr>
                <th>No.</th>
                <th>Description</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th class="has-text-right">Price (SAR)</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr class="item" v-for="(item, index) in items">
                <td>{{index+1}}</td>
                <td><input disabled class="input is-small" v-model="item.description" /></td>
                <td><input disabled class="input is-small" type="number" v-model="item.unit_price" /></td>
                <td>
                    <input v-if="item.lump_sum" disabled class="input is-small" value="LS" />
                    <input disabled v-else class="input is-small" type="number" v-model="item.qty" />
                </td>
                <td class="has-text-right">{{ item.lump_sum ? item.unit_price : item.unit_price * item.qty | currency }}</td>
                <td class="has-text-right">
                    <button class="button is-danger is-small" @click="deleteItem(item)" v-if="editable">Delete</button>
                </td>
            </tr>
            <tr class="newItem" v-if="isAdding">
                <td></td>
                <td><input class="input is-small" v-model="newItem.description" /></td>
                <td><input class="input is-small" type="number" v-model="newItem.unit_price" /></td>
                <td class="is-flex">
                    <div style="width:50px;height:27px;">
                        <input class="input is-small" type="number" v-if="!newItem.lump_sum" v-model="newItem.qty" />
                    </div>
                    <div style="width:60px;margin-left:10px;">
                        <span>LS:</span> <input type="checkbox" v-model="newItem.lump_sum">
                    </div>
                </td>
                <td class="has-text-right">{{ newItem.lump_sum ? newItem.unit_price : newItem.unit_price * newItem.qty | currency }}</td>
                <td class="has-text-right">
                    <button class="button is-success is-small"
                            :class="{'is-loading': savingItemLoading}"
                            @click="saveItem(item)">
                        Save
                    </button>
                </td>
            </tr>
            <tr v-if="editable">
                <td colspan="6">
                    <button v-if="!isAdding" class="button is-secondary is-small" @click="addRow">Add row</button>
                    <button v-else class="button is-secondary is-small" @click="isAdding=false">Cancel adding</button>
                </td>
            </tr>
            <tr class="vat">
                <td colspan="4" class="has-text-right">Subtotal:</td>
                <td colspan="1" class="has-text-right"><b>{{ subtotal | currency }}</b></td>
                <td></td>
            </tr>
            <tr class="vat">
                <td colspan="4" class="has-text-right">VAT ({{ currentRate }}%):</td>
                <td colspan="1" class="has-text-right"><b>{{ vat | currency }}</b></td>
                <td></td>
            </tr>
            <tr class="total">
                <td colspan="4" class="has-text-right">Total</td>
                <td colspan="1" class="has-text-right"><b>{{ total | currency }}</b></td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
  import moment from 'moment';
  export default {
    props: {
      purchaseOrderId: {
        required: true,
      },
      lines: {
        required: false,
      },
      editable: {
        required: false,
        default: true,
        type: Boolean,
      }
    },
    data () {
      return {
        savingItemLoading: false,
        items: [],
        newItem: { purchase_order_id: null, description: "", qty: 1, unit_price: 0, lump_sum: false },
        isAdding: false,
      }
    },
    computed: {
      currentRate() {
        var SpecialToDate = '29/06/2020'; // DD/MM/YYYY
        var SpecialTo = moment(SpecialToDate, "DD/MM/YYYY");
        if (moment() > SpecialTo) {
          return '15%';
        } else {
          return '5%';
        }
      },
      total() {
        let rate = 1.05;

        var SpecialToDate = '29/06/2020'; // DD/MM/YYYY
        var SpecialTo = moment(SpecialToDate, "DD/MM/YYYY");
        if (moment() > SpecialTo) {
          rate = 1.15;
        }

        return rate * this.items.reduce(
          (acc, item) => acc + item.unit_price * (item.lump_sum ? 1 : item.qty),
          0
        );
      },
      subtotal() {
        return this.items.reduce(
          (acc, item) => acc + item.unit_price * (item.lump_sum ? 1 : item.qty),
          0
        );
      },
      vat() {
        let rate = 0.05;

        var SpecialToDate = '29/06/2020'; // DD/MM/YYYY
        var SpecialTo = moment(SpecialToDate, "DD/MM/YYYY");
        if (moment() > SpecialTo) {
          rate = 0.15;
        }

        return rate * this.items.reduce(
          (acc, item) => acc + item.unit_price * (item.lump_sum ? 1 : item.qty),
          0
        );
      }
    },
    mounted() {
      this.loadItems();
    },
    methods: {
      loadItems() {
        axios.get(this.apiUrl()+'/purchase-orders/'+this.purchaseOrderId+'/lines')
          .then(response => {
            this.items = response.data;
          })
      },
      addRow() {
        this.isAdding = true;
      },
      saveItem()
      {
        this.newItem.purchase_order_id = this.purchaseOrderId;
        this.savingItemLoading = true;
        axios.post(this.apiUrl()+'/purchase-orders/'+this.purchaseOrderId+'/lines', this.newItem)
          .then(response => {
            this.newItem = { description: "", qty: 1, unit_price: 0, lump_sum: false };
            this.items.push(response.data);
            this.addRow();
          }).finally(() => {
            this.savingItemLoading = false;
        })
      },
      deleteItem(item)
      {
        axios.delete(this.apiUrl()+'/purchase-orders/'+this.purchaseOrderId+'/lines/'+item.id)
          .then(response => {
            this.loadItems();
          })
      }
    },
    filters: {
      currency(value) {
        return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2 }).format(value);
      }
    }
  }
</script>
