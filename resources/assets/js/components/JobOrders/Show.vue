<template>
    <section v-if="jobOrder">
        <div class="is-flex" style="justify-content:space-between">
            <p>
                <span class="is-uppercase" style="font-size: 12px;color: #585050;"><b>Job Order details</b></span>
                <br/>
                <b>No. {{ jobOrder.job_order_number }}</b>
                <span class="tag is-small is-success is-uppercase" style="font-size:12px;" v-if="jobOrder.is_completed">{{ jobOrder.status }}</span>
                <span class="tag is-small is-uppercase is-uppercase" style="font-size:12px;" v-else>{{ jobOrder.status }}</span>
            </p>
            <div class="buttons">
                <button class="button is-small"
                        @click.prevent="$store.commit('JobOrders/togglePreviewPdf')">
                    <span class="icon"><i class="fa fa-print"></i></span>
                    <span>Print</span>
                </button>
            </div>
        </div>
        <preview-pdf-container style="margin-top:1rem;"
                               :url="baseUrl()+'/job-orders/'+jobOrder.job_order_number+'/pdf'"
                               show-type="JobOrders/previewPdf">
        </preview-pdf-container>
        <form class="form" method="post" style="margin-top:2rem" @submit.prevent="updateOrder">
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
                        <textarea class="textarea" size="is-small" v-model="remark" rows="2" cols="2"></textarea>
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

                    <div class="field">
                        <label class="label">Location <span class="has-text-danger">*</span></label>
                        <!-- If selected. -->
                        <b-autocomplete v-if="!location"
                                        v-model="locationSearchCode"
                                        field="name"
                                        size="is-small"
                                        :data="filteredLocations"
                                        @select="option => location = option"
                                        :loading="$isLoading('FETCHING_LOCATIONS')"
                                        required>
                            <template slot="empty">No results found</template>
                        </b-autocomplete>
                        <!-- When selected -->
                        <input v-else
                               type="text"
                               class="input is-small"
                               :value="location.name"
                               @click="emptyLocation"
                               required
                               readonly>
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
                        <table class="table is-narrow is-size-7 is-fullwidth is-striped">
                            <thead>
                                <tr>
                                    <th>Name <span class="has-text-danger">*</span></th>
                                    <th class="has-text-right">Quantity</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody v-if="materials.length">
                                <tr v-for="(material, i) in materials">
                                <td>
                                    {{ material.stock.description }}
<!--                                    <input type="text"-->
<!--                                           :value="material.stock.description"-->
<!--                                           autocomplete="on"-->
<!--                                           disabled="disabled"-->
<!--                                           readonly="readonly"-->
<!--                                           style="width:100px;cursor:default;"-->
<!--                                           class="input is-small">-->
<!--                                    <b-autocomplete-->
<!--                                            size="is-small"-->
<!--                                            :data="material.material_options"-->
<!--                                            placeholder="Material"-->
<!--                                            field="description"-->
<!--                                            :loading="material.isFetching"-->
<!--                                            @input="asyncRequest($event, {url: 'search/stock', key_data: `materials.${i}.material_options`})"-->
<!--                                            @select="updateMaterialQty($event, material)">-->
<!--                                        <template slot-scope="props">-->
<!--                                            <p>{{ props.option.description }}</p>-->
<!--                                        </template>-->
<!--                                    </b-autocomplete>-->
                                </td>
                                <td class="has-text-right">
                                    {{ material.qty }}
                                </td>
                                <td class="has-text-centered">
                                    <!--
                                    TODO: Editing items in JO.
                                    <button v-if="materials.length == i+1" class="button is-primary is-small"
                                            :disabled="jobOrder.is_completed"
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
                                    -->
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
                            <tr v-for="(tech, index) in jobOrder.technicians">
                                <td>{{ tech.display_name }}</td>
                                <td>{{ tech.pivot.time_start }}</td>
                                <td>{{ tech.pivot.time_end }}</td>
                            </tr>
                            <!--
                            TODO: Adding new technician when viewing a created JO.
                            <tr>
                                <td>
                                    //
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
                            -->
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <div class="columns is-centered" v-if="!jobOrder.is_completed">
                <div class="column">
                    <button type="submit"
                            :class="{'is-loading': $isLoading('UPDATING_JOB_ORDER')}"
                            class="button is-primary">
                        Update
                    </button>
                    <job-orders-mark-completed-button
                            @job-order:completed="completedJobOrder()"
                    ></job-orders-mark-completed-button>
                </div>
            </div>
        </form>
    </section>
</template>

<script>
  import BSelect from "buefy/src/components/select/Select";
  import moment from 'moment';
  import debounce from 'lodash/debounce';

  export default {
    components: {BSelect},
    props: {
      /**
       * Job order ID.
       */
      id: {
        type: Number,
        required: true,
      }
    },
    data() {
      return {
        jobOrder: null,

        date: null,
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
      }
    },
    watch: {
      // time_end() {
      //   let jo = this.jobOrder;
      //   jo.time_end = this.time_end;
      //   this.$store.commit('JobOrder/jobOrder', jo);
      // },
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
      this.loadJobOrder();
    },
    methods: {
      loadJobOrder() {
        axios.get(this.apiUrl()+`/job-orders/${this.id}`)
          .then(response => {
            this.$store.commit('JobOrder/jobOrder', response.data);
            this.$endLoading('COMPLETING_JOB_ORDER'); // To make sure it's done loading...

            let jo = response.data;

            this.date = moment(jo.date, 'YYYY-MM-DD HH:mm:ss').toDate();
            this.cost_center = jo.department;
            this.ext = jo.ext;
            this.job_description = jo.job_description;
            this.location = jo.location;
            this.remark = jo.remark;
            this.employee = jo.employee;
            this.requested_through_type = jo.requested_through_type;
            this.time_start = jo.time_start ? moment(jo.time_start, 'YYYY-MM-DD HH:mm:ss').format('HH:mm') : null;
            this.time_end = jo.time_end ? moment(jo.time_end, 'YYYY-MM-DD HH:mm:ss').format('HH:mm') : null;
            this.materials = jo.items;

            jo.technicians.map((tech) => {
              if (tech.pivot.time_start) {
                tech.pivot.time_start = moment(tech.pivot.time_start, 'HH:mm:ss').format('hh:mm A');
              }

              if (tech.pivot.time_end) {
                tech.pivot.time_end = moment(tech.pivot.time_end,'YYYY-MM-DD HH:mm:ss').format('hh:mm A');
              }
            });
            this.technicians = jo.technicians;

            this.jobOrder = response.data;
          })
      },
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
      updateOrder() {
        this.$startLoading('UPDATING_JOB_ORDER');

        let data = this.$data;
        data.location_id = this.location.id;

        // Only material requests that are selected
        let selectedMaterialRequests = data.materials.filter((x) => x.stock_id)
        data.materials = selectedMaterialRequests;

        if (this.employee) {
          data.employee_id = this.employee.id;
        } else {
          data.employeeName = this.employeeSearchCode;
        }

        data.cost_center_id = this.cost_center ? this.cost_center.id : null;

        axios.put(this.apiUrl()+'/job-orders/'+this.jobOrder.id, data)
          .then(response => {
            this.$toast.open({
              message: 'Success! Redirecting...',
            });
            this.loadJobOrder();
          })
          .catch(e => {
            alert(e.response.data.message);
            throw e;
          }).finally(() => {
            this.$endLoading('UPDATING_JOB_ORDER');
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
      completedJobOrder() {
        this.loadJobOrder();
        // ending of animation is under loadJobOrder()
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

    .dropdown-content {
        width: 460px;
    }
</style>
