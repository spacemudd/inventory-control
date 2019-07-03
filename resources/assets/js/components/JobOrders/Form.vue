<template>
    <section>
        <form class="form" method="post" style="margin-top:2rem" @submit.prevent="submitOrder">
            <div class="columns">
                <div class="column">
                    <div class="field">
                        <label class="label">Date <span class="has-text-danger">*</span></label>
                        <b-datepicker
                                v-model="date"
                                size="is-small"
                                placeholder="Click to select..."
                                required>
                        </b-datepicker>
                    </div>

                    <b-field label="Cost Center">
                        <!-- If selected. -->
                        <b-autocomplete v-if="!cost_center"
                                        v-model="costCenterSearchCode"
                                        field="code"
                                        size="is-small"
                                        :data="filteredCostCenters"
                                        @select="option => cost_center = option"
                                        :loading="$isLoading('FETCHING_COST_CENTERS')">
                            <template slot="empty">No results found</template>
                        </b-autocomplete>
                        <!-- When selected -->
                        <input v-else
                               type="text"
                               class="input is-small"
                               :value="cost_center.code + ' - ' + cost_center.description"
                               @click="emptyCostCenter"
                               readonly>
                    </b-field>

                    <b-field label="Ext">
                        <b-input v-model="ext" size="is-small"></b-input>
                    </b-field>
                    <div class="field">
                        <label class="label">Job description <span class="has-text-danger">*</span></label>
                        <b-input v-model="job_description" maxlength="200" type="textarea" size="is-small" required></b-input>
                    </div>

                    <b-field label="Remark">
                        <textarea class="textarea" v-model="remark" rows="2" cols="2"></textarea>
                    </b-field>
                </div>

                <div class="column">
                    <b-field label="Requester">
                        <!-- If selected. -->
                        <b-autocomplete v-if="!employee"
                                        v-model="employeeSearchCode"
                                        field="code"
                                        size="is-small"
                                        :data="filteredEmployees"
                                        @select="option => employee = option"
                                        :loading="$isLoading('FETCHING_EMPLOYEES')">
                            <template slot="empty">No results found</template>
                        </b-autocomplete>
                        <!-- When selected -->
                        <input v-else
                               type="text"
                               class="input is-small"
                               :value="employee.code + ' - ' + employee.name"
                               @click="emptyEmployee"
                               readonly>
                    </b-field>

                    <div class="field"
                         v-click-outside="loseLocationFocus"
                         tabindex="1">
                        <label class="label">Location <span class="has-text-danger">*</span></label>
                        <input type="text"
                               class="input is-small"
                               v-model="locationSearch"
                               ref="locationField"
                               id="location-field"
                               @click="selectLocationField"
                               @keydown="goDownLocation"
                               required>
                        <div class="dropdown-box-container box" v-if="showLocationsModal">
                            <div class="is-flex" style="justify-content: space-between">
                                <p><b>Locations</b></p>
                                <p>Manage locations</p>
                            </div>
                            <ul class="dropdown-box-container-list" id="locations-list">
                                <li class="user-data user-data-add-new" v-if="!newLocationEntry">
                                    <button class="button has-text-success"
                                            @click.prevent="addNewLocationEntry"
                                            tabindex="1">
                                        <i>+ Add '{{ locationSearch }}'</i>
                                    </button>
                                </li>
                                <li class="user-data" v-for="loc in locationsResult">
                                    <button @click.prevent="selectLocation(loc)"
                                            tabindex="1"
                                            class="button">
                                        {{ loc.name }}
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Requested through <span class="has-text-danger">*</span></label>
                        <div class="block">
                            <b-radio v-model="requested_through_type"
                                     size="is-small"
                                     native-value="email">
                                Email
                            </b-radio>
                            <b-radio v-model="requested_through_type"
                                     size="is-small"
                                     native-value="phone_call">
                                Phone Call
                            </b-radio>
                            <b-radio v-model="requested_through_type"
                                     size="is-small"
                                     native-value="breakdown">
                                Breakdown
                            </b-radio>
                            <b-radio v-model="requested_through_type"
                                     size="is-small"
                                     native-value="ppm">
                                PPM
                            </b-radio>
                        </div>
                    </div>

                        <!--
                        <div class="field">
                            <label class="label">Regions <span class="has-text-danger">*</span></label>
                            <select name="region_id" class="input is-small" v-model="region_id" required>
                                <option v-for="(region, key) in regions" :value="region.id" >
                                    {{ region.name }}
                                </option>
                            </select>
                        </div>
                        -->

                    <b-field label="Job duration">
                        <div class="columns">
                            <div class="column">
                                <b-input type="time"
                                         v-model="time_start"
                                         placeholder="Select start time"
                                         size="is-small"
                                         required>
                                </b-input>
                            </div>
                            <div class="column">
                                <b-input type="time"
                                         v-model="time_end"
                                         size="is-small"
                                         placeholder="Select end time">
                                </b-input>
                            </div>
                        </div>
                    </b-field>

                    <div class="field">
                        <div class="label anb-label">
                            Materials
                            <!--
                            <button style="margin-left:5px;" class="button is-small" @click.prevent="">
                                <span><i class="fa fa-plus fa-xs" style="color: #454545;"></i></span>
                            </button>
                            -->
                        </div>
                        <table class="table is-narrow is-size-7 is-fullwidth">
                            <thead>
                                <tr>
                                    <th>Name <span class="has-text-danger">*</span></th>
                                    <th>Available</th>
                                    <th>Quantity</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(material, i) in materials">
                                <td>
                                    <b-autocomplete
                                            size="is-small"
                                            :data="material.material_options"
                                            placeholder="Material"
                                            field="description"
                                            :loading="material.isFetching"
                                            @input="asyncRequest($event, {url: 'search/stock', key_data: `materials.${i}.material_options`})"
                                            @select="updateMaterialQty($event, material)">
                                        <template slot-scope="props">
                                            <p>{{ props.option.description }}</p>
                                        </template>
                                    </b-autocomplete>
                                </td>
                                <td>
                                    <input type="text"
                                           :value="material.onHandQuantity"
                                           autocomplete="on"
                                           disabled="disabled"
                                           readonly="readonly"
                                           style="width:100px;cursor:default;"
                                           class="input is-small">
                                </td>
                                <td>
                                    <b-input type="number"
                                             size="is-small"
                                             style="width:100px;"
                                             :max="material.onHandQuantity"
                                             v-model="material.quantity">
                                    </b-input>
                                    <p class="has-text-danger is-small" v-if="material.onHandQuantity<material.quantity">
                                        Select {{ material.onHandQuantity }} or less.
                                    </p>
                                </td>
                                <td class="has-text-centered">
                                    <button v-if="materials.length == i+1" class="button is-primary is-small"
                                            :disabled="material.onHandQuantity<material.quantity"
                                            :class="{'is-loading': $isLoading('SAVING_MATERIAL')}"
                                            @click.prevent="addMaterial">
                                        <span class="icon is-small"><i class="fa fa-check"></i></span>
                                    </button>
                                    <button v-else
                                            class="button is-danger is-small "
                                            :class="{'is-loading': $isLoading('DELETING_ASSET_ISSUANCE')}"
                                            @click.prevent="materials.splice(i, 1)">
                                        <span class="icon is-small"><i class="fa fa-trash"></i></span>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="field">
                        <label class="label anb-label">Technicians</label>
                        <table class="table is-narrow is-size-7 is-fullwidth">
                            <thead>
                            <tr>
                                <th>Employee <span class="has-text-danger">*</span></th>
                                <th>Time start</th>
                                <th>Time end</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(tech, index) in technicians">
                                <td>{{ tech.addEmployees.name }}</td>
                                <td>{{ tech.time_start }}</td>
                                <td>{{ tech.time_end }}</td>
                            </tr>
                            <tr>
                                <td @keyup.enter="addTechnician">
                                    <input v-if="technicianForm.addEmployees"
                                           type="text"
                                           class="input is-small"
                                           :value="technicianForm.addEmployees.name"
                                           @click="clearTechnician"
                                           readonly>
                                    <b-autocomplete v-else
                                                    v-model="technicianFormSearchCode"
                                                    field="name"
                                                    :data="filteredEmployeesForAdd"
                                                    @select="option => technicianForm.addEmployees = option"
                                                    size="is-small"
                                                    :loading="$isLoading('FETCHING_EMPLOYEES')">
                                        <template slot="empty">No results found</template>
                                    </b-autocomplete>
                                </td>
                                <td>
                                    <b-input type="time"
                                             size="is-small"
                                             style="width:100px;"
                                             v-model="technicianForm.time_start"
                                             placeholder="Select start time">
                                    </b-input>
                                </td>
                                <td>
                                    <b-input type="time"
                                             size="is-small"
                                             style="width:100px;"
                                             v-model="technicianForm.time_end"
                                             placeholder="Select end time">
                                    </b-input>
                                </td>
                                <td class="has-text-centered">
                                    <button class="button is-primary is-small"
                                            :class="{'is-loading': $isLoading('SAVING_TECHNICIAN')}"
                                            @click.prevent="addTechnician">
                                        <span class="icon is-small"><i class="fa fa-check"></i></span>
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <div class="columns is-centered">
                <div class="column">
                    <button type="submit"
                            :class="{'is-loading': $isLoading('SAVING_JOB_ORDER')}"
                            class="button is-primary">Create job order</button>
                </div>
            </div>
        </form>
    </section>
</template>

<script>
  import BSelect from "buefy/src/components/select/Select";
  import moment from 'moment';
  import debounce from 'lodash/debounce';
  import vClickOutside from 'v-click-outside'


  export default {
    components: {BSelect},
    directives: {
      clickOutside: vClickOutside.directive
    },
    data() {
      return {
        date:  new Date(),
        technicians: [],
        name: '',
        ext: '',
        location: '',
        cost_center: '',
        requested_through_type: 'email',
        job_description: '',
        time_start: this.now(),
        time_end: null,
        remark: '',
        employee: null,
        costCenters: [],
        costCenterSearchCode: '',

        employees: [],
        addEmployees: [],
        employeeSearchCode: '',
        quotation_id: '',
        locations: [],
        locationSearchCode: '',

        technicianFormSearchCode: '',
        technicianForm: {
          time_start: this.now(),
          time_end: null,
        },

        showLocationsModal: false,

        materials: [
          {
            material_options: [],
            isFetching: false,
            description: '',
            stock_id: null,
            onHandQuantity: 0,
            quantity: 0,
            technician: '',
            technicianSearchCode: '',
          }
        ],
        materialSearchCode: '',

        isAddingMaterial: true,
        materialForm: {
          name: '',
          available: 0,
          quantity: 1,
          technician: ''
        },

        // Location search options.
        locationsResult: [],
        locationFuse: null,
        locationSearch: '',
        locationSearchOptions: {
          shouldSort: true,
          threshold: 0.6,
          location: 0,
          distance: 100,
          maxPatternLength: 32,
          minMatchCharLength: 1,
          keys: [
            "name",
          ],
        },
      }
    },
    watch: {
      locationSearch() {
        if (this.locationSearch.trim() === '')
          this.locationsResult = this.locations;
        else
          this.locationsResult = this.locationFuse.search(this.locationSearch.trim());
      },
      technicianFormSearchCode: {
        handler: function (valId) {
          this.employeeSearchCode = ''
          this.addEmployees = [];
          if (valId) {
            axios.get(this.apiUrl() + '/employees/' + valId).then(response => {
              this.addEmployees = response.data;
            })
          }
        }
      },
      employeeSearchCode: {
        handler: function (valId) {
          this.$startLoading('FETCHING_EMPLOYEES');
          axios.get(this.apiUrl() + '/employees').then(response => {
            this.employees = response.data;
            this.$endLoading('FETCHING_EMPLOYEES');
          })
        }
      }
    },
    computed: {
      newLocationEntry() {
        let locSearch = this.locationSearch;
        if (!locSearch) {
          return true; // don't allow the user to add.
        }

        let hits = this.locations.filter((option) => {
          return option.name === locSearch;
        })
        let dontAdd = hits.length;
        return dontAdd;
      },
      filteredLocations() {
        return this.locations.filter((option) => {
          return option.name
            .toString()
            .toLowerCase()
            .indexOf(this.locationSearchCode.toLowerCase()) >= 0
        })
      },
      filteredEmployees() {
        return this.employees.filter((option) => {
          return option.name
            .toString()
            .toLowerCase()
            .indexOf(this.employeeSearchCode.toLowerCase()) >= 0
        })
      },
      filteredEmployeesForAdd() {
        return this.addEmployees.filter((option) => {
          return option.name
            .toString()
            .toLowerCase()
            .indexOf(this.employeeSearchCode.toLowerCase()) >= 0
        })
      },
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
      this.loadLocations();
    },
    methods: {
      asyncRequest: debounce(function(q, data) {
        _.set(this, data.key_data, []);
        this.isFetching = true;
        axios.get(this.apiUrl() + `/${data.url}?q=${q}`).then(response => {
          this.isFetching = false;
          _.set(this, data.key_data, response.data);
        })
      }, 150),

      updateMaterialQty(option, material) {
        if (option) {
          _.set(material, 'onHandQuantity', option.on_hand_quantity);
          _.set(material, 'description', option.description);
          _.set(material, 'stock_id', option.id);
        }
      },
      now() {
        return moment().format('HH:mm');
      },
      loadCostCenters() {
        this.$startLoading('FETCHING_COST_CENTRES');
        axios.get(this.apiUrl() + '/departments').then(response => {
          this.costCenters = response.data;
          this.$endLoading('FETCHING_COST_CENTRES');
        })
      },
      loadLocations() {
        this.$startLoading('FETCHING_LOCATIONS');
        axios.get(this.apiUrl() + '/locations').then(response => {
          this.locations = response.data;
          this.locationsResult = response.data;
          this.locationFuse = new window.Fuse(this.locations, this.locationSearchOptions);
          this.$endLoading('FETCHING_LOCATIONS');
        })
      },
      emptyCostCenter() {
        this.cost_center = null;
        this.costCenterSearchCode = '';
      },
      emptyEmployee() {
        this.employee = null;
        this.employeeSearchCode = '';
      },
      emptyLocation() {
        this.location = null;
        this.locationSearchCode = '';
      },
      submitOrder() {
        this.$startLoading('SAVING_JOB_ORDER');

        let data = this.$data;
        if (this.location.id) {
          data.location_id = this.location.id;
        }

        // Only material requests that are selected
        let selectedMaterialRequests = data.materials.filter((x) => x.stock_id)
        data.materials = selectedMaterialRequests;

        if (this.employee) {
          data.employee_id = this.employee.id;
        } else {
          data.employeeName = this.employeeSearchCode;
        }

        data.cost_center_id = this.cost_center ? this.cost_center.id : null;

        axios.post(this.baseUrl()+'/job-orders', data)
          .then(response => {
            this.$toast.open({
              message: 'Success! Redirecting...',
            });
            window.location.href = this.baseUrl()+'/job-orders/'+response.data.job_order_number;
          })
          .catch(e => {
            alert(e.response.data.message);
            throw e;
          }).finally(() => {
          this.$endLoading('SAVING_JOB_ORDER');
        })
      },
      addTechnician() {
        if (!this.technicianForm.addEmployees) {
          alert('Please select an employee');
          return false;
        }

        let technician = {
          id: this.technicianForm.addEmployees.id,
          addEmployees: this.technicianForm.addEmployees,
          time_start: this.technicianForm.time_start,
          time_end: this.technicianForm.time_end,
        };

        this.technicians.push(technician);

        setTimeout(() => {
          this.clearTechnicianForm();
        }, 100);
      },
      addMaterial() {
        this.materials.push({
          material_options: [],
          isFetching: false,
          description: '',
          onHandQuantity: 0,
          quantity: 0,
          technician: '',
          technicianSearchCode: ''
        });
      },
      clearTechnicianForm() {
        this.technicianFormSearchCode = '';
        let technicianForm = {
          employee: '',
          technician_id: '',
          time_start: moment().format('HH:mm'),
          time_end: null,
        }
        this.technicianForm = technicianForm;
      },
      clearTechnician() {
        this.technicianFormSearchCode = '';
        this.technicianForm = {
          employee: '',
          time_start: moment().format('HH:mm'),
          time_end: null,
          technician_id: ''
        };
      },
      clearMaterialTechnician() {
        this.materialForm.technicianSearchCode = '';
      },
      emptyMaterial() {
        this.materialSearchCode = '';
      },
      selectLocation(loc) {
        this.location = loc;
        this.locationSearch = loc.name;
        this.showLocationsModal = false;
      },
      selectLocationField() {
        this.$refs.locationField.select();
        this.showLocationsModal = true;
      },
      selectFirstLocationList() {
        let list = document.getElementById('locations-list');
        let first = list.firstChild;
        first.firstChild.focus();
      },
      goUpLocation(e) {
        let list = document.getElementById('locations-list');
        let first = list.firstChild;
        let maininput = document.getElementById('location-field');
        if (document.activeElement == maininput) { first.firstChild.focus(); } // if the currently focused element is the main input --> focus the first <li>
        else { document.activeElement.parentNode.nextSibling.firstChild.focus(); }
      },
      goDownLocation(e) {
        if (this.showLocationsModal) {
          let list = document.getElementById('locations-list');
          let first = list.firstChild;
          let maininput = document.getElementById('location-field');
          if (e.keyCode === 38) { // up
            e.preventDefault();
            if (document.activeElement == (maininput || first)) {
              //
            } else {
              document.activeElement.parentNode.previousSibling.firstChild.focus();
            }
          }

          if (e.keyCode == 40) { // down
            e.preventDefault();
            if (document.activeElement == maininput) {
              debugger;
              first.firstChild.focus();
            } else {
              document.activeElement.parentNode.nextSibling.firstChild.focus();
            }
          }

          if (e.keyCode == 27) { // esc
            e.preventDefault();
            this.showLocationsModal = false;
          }
        }
      },
      addNewLocationEntry() {
        this.showLocationsModal = false;
        this.location = this.locationSearch;
      },
      loseLocationFocus() {
        this.showLocationsModal = false;

        if (!this.location) {
          this.locationSearch = '';
        }
      },
    }
  }
</script>

<style>
    .dropdown-box-container {
        position: absolute;
        width: 400px;
        margin-top: 10px;
        z-index: 1;
        border: 2px solid #28a0ff;
        border-top-left-radius: 0px;
        border-top-right-radius: 0px;
        max-height: 200px;
        padding: 10px;
        font-size: 12px;
    }

    .dropdown-box-container:before {
        content: "";
        position: absolute;
        left: 5px;
        top: -6px;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 0 5px 5px 5px;
        border-color: transparent transparent #28a0ff transparent;
        z-index: 9999;
    }

    .dropdown-box-container-list {
        margin-top: 5px;
        border-top: 1px solid #dedede;
    }

    .user-data > button {
        height: 21px;
        font-size: 12px;
        width: 100%;
        justify-content: left;
        background: transparent;
        border: none;
    }

    .user-data > button:hover {
        background: #28a0ff;
        color: white;
        border-radius: 0;
    }

    .user-data-add-new {
        font-weight: bold;
    }

    .user-data-add-new > button:hover {
        background: #ffffff;
    }
</style>
