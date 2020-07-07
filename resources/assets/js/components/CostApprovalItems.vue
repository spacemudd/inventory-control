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
            <tr class="item" v-for="(item, index) in items" :key="index">
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
                        <select @change="alterQuotations()"  name="quotation_number" v-model="newItem.quotation_number">
                            <option v-for="(quotation, index) in mutated_quotations" :key="index" v-if="!quotation.hasChosen" :value="quotation.quotation_number">
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
                <td><b>VAT ({{ currentRate }}): {{ vat | currency }} SAR</b></td>
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
  import moment from 'moment';
  import _ from 'lodash';
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
      },
      createdAt: {
        required: false,
        type: String,
      }
    },
    data () {

      let mutated_quotations = _.cloneDeep(this.quotations)
      return {
        items: [],
        mutated_quotations,
        newItem: { quotation_number: '', cost_approval_id: null, description: "", qty: 1, unit_price: 0, lump_sum: false },
        isAdding: false,
        selectedQuotation: null,
        groupOfSelectedQuotations: [],
      }
    },
    computed: {
      currentRate() {
        var SpecialToDate = '30/06/2020'; // DD/MM/YYYY
        var SpecialTo = moment(SpecialToDate, "DD/MM/YYYY");
        if (moment(this.createdAt) > SpecialTo) {
          return '15%';
        } else {
          return '5%';
        }
      },
      total() {
        var SpecialToDate = '30/06/2020'; // DD/MM/YYYY
        var SpecialTo = moment(SpecialToDate, "DD/MM/YYYY");
        if (moment(this.createdAt) > SpecialTo) {
          return 1.15 * this.items.reduce(
            (acc, item) => acc + item.unit_price * item.qty,
            0
          );
        } else {
          return 1.05 * this.items.reduce(
            (acc, item) => acc + item.unit_price * item.qty,
            0
          );
        }
      },
      vat() {
        var SpecialToDate = '30/06/2020'; // DD/MM/YYYY
        var SpecialTo = moment(SpecialToDate, "DD/MM/YYYY");
        if (moment(this.createdAt) > SpecialTo) {
          return 0.15 * this.items.reduce(
            (acc, item) => acc + item.unit_price * item.qty,
            0
          );
        } else {
          return 0.05 * this.items.reduce(
            (acc, item) => acc + item.unit_price * item.qty,
            0
          );
        }
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
            this.alterQuotations();
          })
      },
      addRow() {
        this.isAdding = true;
      },

      alterQuotations()
      {
        let holder = _.cloneDeep(this.mutated_quotations);
        this.mutated_quotations = [];

       _.forEach(holder, (value)=>{

          let isSelected = _.filter(this.items, itemval =>{
            return itemval.quotation_number == value.quotation_number
          }).length


          if(isSelected>=1)
           value.hasChosen = true;
            
          

          else
           value.hasChosen = false
            
          
          
          this.mutated_quotations.push(value);

       })


      },
      saveItem()
      {
        this.newItem.cost_approval_id = this.costApprovalId;
        if (this.mutated_quotations.length<=1) {
          this.newItem.quotation_number = this.mutated_quotations[0].quotation_number;
        }
        //this.newItem.quotation_number;
        axios.post(this.apiUrl()+'/cost-approvals/'+this.costApprovalId+'/lines', this.newItem)
          .then(response => {
            this.newItem = { quotation_number: '', description: "", qty: 1, unit_price: 0, lump_sum: false };
            this.items.push(response.data);
            this.addRow();
            this.alterQuotations()
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
