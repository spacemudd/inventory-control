<template>
    <div>
        <table class="table is-fullwidth is-narrow is-size-7">
            <colgroup>
                <col style="width:10px;">
                <col>
                <col style="width:300px;">
                <col style="width:300px;">
                <col style="width:100px;">
            </colgroup>
            <thead>
            <tr>
                <th>No.</th>
                <th>Description</th>
                <th>Tag number</th>
                <th>Serial No.</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr class="item" v-for="(item, index) in items">
                <td>{{index+1}}</td>
                <td><input disabled class="input is-small" v-model="item.description" /></td>
                <td><input disabled class="input is-small" type="text" v-model="item.serial_number" /></td>
                <td><input disabled class="input is-small" type="text" v-model="item.tag_number" /></td>
<!--                <td class="has-text-right">-->
<!--                    <button class="button is-danger is-small" @click="saveInvoiceItem(item)">Save</button>-->
<!--                </td>-->
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
      total() {
        let rate = 1.05;

        var SpecialToDate = '30/06/2020'; // DD/MM/YYYY
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

        var SpecialToDate = '30/06/2020'; // DD/MM/YYYY
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
      },
      saveInvoiceItem(item)
      {
        axios.put(this.apiUrl()+'/purchase-orders/'+this.purchaseOrderId+'/lines/'+item.id, {
          tag_number: item.tag_number,
          serial_number: item.serial_number,
        })
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
