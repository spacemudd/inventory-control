<template>
        <div>
            <p class="is-uppercase"><b>General PO</b></p>
            <div>
                <div class="is-flex">
                    <div class="column is-3">
                        <label for="ref" class="label">Ref</label>
                        <input type="text" placeholder="Auto-generated if left bank." v-model="ref" class="input" id="ref" name="ref">
                    </div>
                    <div class="column is-3">
                        <div class="field">
                            <label class="label">Date <span class="has-text-danger">*</span></label>
                            <b-datepicker
                                    v-model="date"
                                    placeholder="Click to select..."
                                    name="date"
                                    required>
                            </b-datepicker>
                        </div>
                    </div>
                </div>
                <div class="is-flex">
                    <div class="column is-3">
                        <label for="supplier" class="label">Supplier</label>
                        <input type="text" class="input" id="Supplier" name="supplier" v-model="supplier">
                    </div>
                    <div class="column is-3">
                        <b-field label="Cost Center">
                            <!-- If selected. -->
                            <b-autocomplete v-if="!cost_center"
                                            v-model="costCenterSearchCode"
                                            field="code"
                                            :data="filteredCostCenters"
                                            @select="option => cost_center = option">
                                <template slot="empty">No results found</template>
                            </b-autocomplete>
                            <!-- When selected -->
                            <input v-else
                                   type="text"
                                   class="input"
                                   name="costCenter"
                                   :value="cost_center.code + ' - ' + cost_center.description"
                                   readonly>
                        </b-field>
                    </div>
                </div>
            </div>
            <div>
                <label class="label">Items</label>
                <table class="table fullwidth is-bordered is-6-touch ">
                    <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Description</th>
                        <th colspan="2">U/Price <span class="fa-pull-right">Qty</span></th>
                        <th scope="col">T/Price(SAR)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="padding: 30px;"></td>
                        <td></td>
                        <td colspan="2"></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding: 100px;"></td>
                        <td></td>
                        <td colspan="2"></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
   </template>

<script>

  export default {
    data() {
      return {
        supplier:'',
        ref: '',
        date:  new Date(),
        cost_center: '',
        costCenterSearchCode: '',
        costCenters: [],
        data: ''
      }
    },
    computed: {
      filteredCostCenters() {
        return this.costCenters.filter((option) => {
          return option.code
            .toString()
            .toLowerCase()
            .indexOf(this.costCenterSearchCode.toLowerCase()) >= 0
        })
      }
    },
    mounted() {
      this.loadCostCenters();
    },
    methods: {
      loadCostCenters() {
        axios.get(this.apiUrl() + '/departments').then(response => {
          this.costCenters = response.data;
        })
      },

    }
  }
</script>

<style scoped>

</style>