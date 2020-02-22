<template>
    <div>
        <table class="table is-fullwidth is-narrow is-size-7">
            <colgroup>
                <col v-if="quotations.length>1" style="width:100px;">
                <col style="width:100px;">
                <col style="width:100px;">
                <col style="width:100px;">
                <col style="width:100px;">
                <col style="width:100px;">
            </colgroup>
            <thead>
            <tr>
                <th v-if="quotations.length>1">Quote</th>
                <th>Description</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Price</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr class="item" v-for="item in items">
                <td v-if="quotations.length>1">
                    <input disabled type="text" class="input is-small" v-model="item.quotation_number">
                </td>
                <td><input disabled class="input is-small" v-model="item.description" /></td>
                <td><input disabled class="input is-small" type="number" v-model="item.unit_price" /></td>
                <td>
                    <input v-if="item.lump_sum" disabled class="input is-small" value="LS" />
                    <input disabled v-else class="input is-small" type="number" v-model="item.qty" />
                </td>
                <td>{{ item.unit_price * item.qty | currency }} SAR</td>
                <td>
                    <button class="button is-danger is-small" @click="deleteItem(item)">Delete</button>
                </td>
            </tr>
            <tr class="newItem" v-if="isAdding">
                <td v-if="quotations.length>1">
                    <div class="select is-fullwidth">
                        <select name="quotation_number" v-model="newItem.quotation_number">
                            <option v-for="quotation in quotations" :value="quotation.quotation_number">
                                {{ quotation.quotation_number }}
                            </option>
                        </select>
                    </div>
                </td>
                <td><input class="input is-small" v-model="newItem.description" /></td>
                <td><input class="input is-small" type="number" v-model="newItem.unit_price" /></td>
                <td class="d-flex"><input class="input is-small" type="number" v-if="!newItem.lump_sum" v-model="newItem.qty" /> LS: <input type="checkbox" v-model="newItem.lump_sum"></td>
                <td>{{ newItem.unit_price * newItem.qty | currency }} SAR</td>
                <td>
                    <button class="button is-success is-small" @click="saveItem(item)">Save</button>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <button v-if="!isAdding" class="button is-secondary is-small" @click="addRow">Add row</button>
                    <button v-else class="button is-secondary is-small" @click="isAdding=false">Cancel adding</button>
                </td>
            </tr>
            <tr class="vat">
                <td colspan="3"></td>
                <td><b>VAT (5%): {{ vat | currency }} SAR</b></td>
            </tr>
            <tr class="total">
                <td colspan="3"></td>
                <td><b>Total: {{ total | currency }} SAR</b></td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
  export default {
    props: {
      costApprovalId: {
        required: true,
      },
      multiVendorSupport: {
        required: false,
        default: false,
      },
      quotations: {
        required: false,
      }
    },
    data () {
      return {
        items: [],
        newItem: { quotation_number: '', cost_approval_id: null, description: "", qty: 1, unit_price: 0, lump_sum: false },
        isAdding: false,
      }
    },
    computed: {
      total() {
        return 1.05 * this.items.reduce(
          (acc, item) => acc + item.unit_price * item.qty,
          0
        );
      },
      vat() {
        return 0.05 * this.items.reduce(
          (acc, item) => acc + item.unit_price * item.qty,
          0
        );
      }
    },
    mounted() {
      this.loadItems();
    },
    methods: {
      loadItems() {
        axios.get(this.apiUrl()+'/cost-approvals/'+this.costApprovalId+'/lines')
          .then(response => {
            this.items = response.data;
          })
      },
      addRow() {
        this.isAdding = true;
      },
      saveItem()
      {
        this.newItem.cost_approval_id = this.costApprovalId;
        if (this.quotations.length<=1) {
          this.newItem.quotation_number = this.quotations[0].quotation_number;
        }
        axios.post(this.apiUrl()+'/cost-approvals/'+this.costApprovalId+'/lines', this.newItem)
          .then(response => {
            this.newItem = { quotation_number: '', description: "", qty: 1, unit_price: 0, lump_sum: false };
            this.items.push(response.data);
            this.addRow();
          })
      },
      deleteItem(item)
      {
        axios.delete(this.apiUrl()+'/cost-approvals/'+this.costApprovalId+'/lines/'+item.id)
          .then(response => {
            this.loadItems();
          })
      }
    },
    filters: {
      currency(value) {
        return value.toFixed(2);
      }
    }
  }
</script>
