e<template>
    <section>
        <form class="form" method="post" style="margin-top:2rem" @submit.prevent="submitOrder">
                <div class="columns">
                    <div class="column">
                        <b-field label="Select a date">
                            <b-datepicker
                                v-model="date"
                                placeholder="Click to select...">
                            </b-datepicker>
                        </b-field>

                        <b-field label="Cost Center">
                            <!-- If selected. -->
                            <b-autocomplete v-if="!cost_center"
                                            v-model="costCenterSearchCode"
                                            field="code"
                                            :data="filteredCostCenters"
                                            @select="option => cost_center = option"
                                            :loading="$isLoading('FETCHING_COST_CENTERS')">
                                <template slot="empty">No results found</template>
                            </b-autocomplete>
                            <!-- When selected -->
                            <input v-else
                                   type="text"
                                   class="input"
                                   :value="cost_center.code + ' - ' + cost_center.description"
                                   @click="emptyCostCenter"
                                   required
                                   readonly>
                        </b-field>

                        <b-field label="Ext">
                            <b-input v-model="ext"></b-input>
                        </b-field>
                        <b-field label="Quotation">
                            <b-select placeholder="Select Quotation" expanded="" required v-model="quotation">
                                <option
                                        v-for="(item, index) in quotations"
                                        :value="index"

                                >
                                    {{ item }}
                                </option>

                            </b-select>
                        </b-field>

                       <b-field label="Job description">
                            <b-input v-model="job_description" maxlength="200" type="textarea"></b-input>
                        </b-field>

                        <b-field label="Remark">
                            <b-input v-model="remark" maxlength="200" type="textarea"></b-input>
                        </b-field>
                    </div>

                    <div class="column">
                        <b-field label="Requester">
                            <!-- If selected. -->
                            <b-autocomplete v-if="!employee"
                                            v-model="employeeSearchCode"
                                            field="code"
                                            :data="filteredEmployees"
                                            @select="option => employee = option"
                                            :loading="$isLoading('FETCHING_EMPLOYEES')">
                                <template slot="empty">No results found</template>
                            </b-autocomplete>
                            <!-- When selected -->
                            <input v-else
                                   type="text"
                                   class="input"
                                   :value="employee.code + ' - ' + employee.name"
                                   @click="emptyEmployee"
                                   readonly>
                        </b-field>


                        <b-field label="Location">
                            <!-- If selected. -->
                            <b-autocomplete v-if="!location"
                                            v-model="locationSearchCode"
                                            field="name"
                                            :data="filteredLocations"
                                            @select="option => location = option"
                                            :loading="$isLoading('FETCHING_LOCATIONS')">
                                <template slot="empty">No results found</template>
                            </b-autocomplete>
                            <!-- When selected -->
                            <input v-else
                                   type="text"
                                   class="input"
                                   :value="location.name"
                                   @click="emptyLocation"
                                   readonly>
                        </b-field>

                        <b-field label="Requested Through">
                            <div class="block">
                                <b-radio v-model="requested_through_type"
                                    native-value="email">
                                    Email
                                </b-radio>
                                <b-radio v-model="requested_through_type"
                                    native-value="phone_call">
                                    Phone Call
                                </b-radio>
                                <b-radio v-model="requested_through_type"
                                    native-value="breakdown">
                                    Breakdown
                                </b-radio>
                                <b-radio v-model="requested_through_type"
                                    native-value="ppm">
                                    PPM
                                </b-radio>
                            </div>
                        </b-field>

                         <b-field label="Job duration">
                            <div class="columns">
                                <div class="column">
                                    <b-input type="time"
                                       v-model="time_start"
                                        placeholder="Select start time">
                                    </b-input>
                                </div>
                                <div class="column">
                                    <b-input type="time"
                                       v-model="time_end"
                                        placeholder="Select end time">
                                    </b-input>
                                </div>
                            </div>
                        </b-field>

                        <b-field label="Materials Used">
                            <table class="table is-narrow is-size-7 is-fullwidth">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Available</th>
                                        <th>Quantity</th>
                                        <th>Technician</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <!--<tr v-for="(material, i) in materials">
                                    <td>{{ material.description }}</td>
                                    <td>{{ material.onHandQuantity }}</td>
                                    <td>{{ material.quantity }}</td>
                                    <td>{{ material.technician }}</td>
                                </tr>-->
                                <tr v-for="(material, i) in materials">
                                    <td>
                                        <b-autocomplete
                                                size="is-small"
                                                :data="material.material_options"
                                                placeholder="Material"
                                                field="description"
                                                :loading="material.isFetching"
                                                @input="asyncRequest($event, {url: 'search/stock', key_data: `materials.${i}.material_options`})"
                                                @select="updateMaterialQty($event, i)">
                                            <template slot-scope="props">
                                                <p>{{ props.option.description }}</p>
                                            </template>
                                        </b-autocomplete>
                                    </td>
                                    <td>
                                        <p v-text="materialForm.available"></p>
                                    </td>
                                    <td>
                                        <b-input type="number"
                                                min="1"
                                                size="is-small"
                                                v-model="materialForm.quantity"
                                                placeholder="Select end time">
                                        </b-input>
                                    </td>
                                    <td @keyup.enter="addTechnician">
                                        <input v-if="materialForm.technician"
                                               type="text"
                                               class="input is-small"
                                               :value="materialForm.technician.code + ' - ' + materialForm.technician.name"
                                               @click="clearMaterialTechnician"
                                               readonly>
                                        <b-autocomplete v-else
                                                        v-model="materialTechnicianSearchCode"
                                                        field="code"
                                                        :data="filteredEmployees"
                                                        @select="option => materialForm.technician = option"
                                                        size="is-small"
                                                        :loading="$isLoading('FETCHING_EMPLOYEES')">
                                            <template slot="empty">No results found</template>
                                        </b-autocomplete>
                                    </td>
                                    <td class="has-text-centered">
                                        <button class="button is-primary is-small"
                                                :class="{'is-loading': $isLoading('SAVING_MATERIAL')}"
                                                @click.prevent="addMaterial">
                                            Add
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </b-field>

                        <b-field label="Technicians">
                            <table class="table is-narrow is-size-7 is-fullwidth">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Time start</th>
                                        <th>Time end</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(tech, index) in technicians">
                                    <td>{{ tech.employee.code+' '+tech.employee.name }}</td>
                                    <td>{{ tech.time_start }}</td>
                                    <td>{{ tech.time_end }}</td>
                                </tr>
                                <tr>
                                    <td @keyup.enter="addTechnician">
                                        <input v-if="technicianForm.employee"
                                               type="text"
                                               class="input is-small"
                                               :value="technicianForm.employee.code + ' - ' + technicianForm.employee.name"
                                               @click="clearTechnician"
                                               readonly>
                                        <b-autocomplete v-else
                                                        v-model="technicianFormSearchCode"
                                                        field="code"
                                                        :data="filteredEmployees"
                                                        @select="option => technicianForm.employee = option"
                                                        size="is-small"
                                                        :loading="$isLoading('FETCHING_EMPLOYEES')">
                                            <template slot="empty">No results found</template>
                                        </b-autocomplete>
                                    </td>
                                    <td>
                                        <b-input type="time"
                                                size="is-small"
                                                v-model="technicianForm.time_start"
                                                placeholder="Select start time">
                                        </b-input>
                                    </td>
                                    <td>
                                        <b-input type="time"
                                                size="is-small"
                                                v-model="technicianForm.time_end"
                                                placeholder="Select end time">
                                        </b-input>
                                    </td>
                                    <td class="has-text-centered">
                                        <button class="button is-primary is-small"
                                                :class="{'is-loading': $isLoading('SAVING_TECHNICIAN')}"
                                                @click.prevent="addTechnician">
                                            Add
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </b-field>

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

    export default {
        components: {BSelect},
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
                quotation: '',
                costCenters: [],
                costCenterSearchCode: '',

                employees: [],
                employeeSearchCode: '',

                locations: [],
                locationSearchCode: '',

                technicianFormSearchCode: '',
                materialTechnicianSearchCode: '',
                technicianForm: {
                    employee: '',
                    time_start: this.now(),
                    time_end: null,
                },

                materials: [
                    {
                        material_options: [],
                        isFetching: false,
                        description: '',
                        onHandQuantity: 0,
                        quantity: 1,
                        technician: ''
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
                quotations:[]
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
                     return option.code
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
            this.loadEmployees();
            this.loadLocations();
            this.loadQuotations();
        },
        methods: {
            asyncRequest: debounce(function(q, data) {
                if (!q.length) {
                    this[data.key_data] = [];
                    return;
                }
                this.isFetching = true;
                axios.get(this.apiUrl() + `/${data.url}?q=${q}`).then(response => {
                    this[data.key_data] = response.data;
                    this.isFetching = false;
                })
            }, 500),

            updateMaterialQty(option) {
                console.log(option)
            },

            now() {
                return moment().format('HH:mm');
            },
            loadEmployees() {
                this.$startLoading('FETCHING_EMPLOYEES');
                axios.get(this.apiUrl() + '/employees').then(response => {
                    this.employees = response.data;
                    this.$endLoading('FETCHING_EMPLOYEES');
                })
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

            loadQuotations() {
                this.$startLoading('FETCHING_LOCATIONS');
                axios.get(this.apiUrl() + '/quotations').then(response => {
                    console.log(response.data);
                    this.quotations = response.data;
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
                data.location_id = this.location.id;
                data.employee_id = this.employee.id;
                data.cost_center_id = this.cost_center.id;
                data.quotation_id = this.quotation;

                axios.post(this.baseUrl()+'/job-orders', data)
                    .then(response => {
                        this.$toast.open({
                            message: 'Success!',
                        });
                        window.location.href = this.baseUrl()+'/job-orders';
                    })
                    .catch(e => {
                        throw e;
                    }).finally(() => {
                    this.$endLoading('SAVING_JOB_ORDER');
                })
            },
            addTechnician() {
                if (!this.technicianForm.employee) {
                    alert('Please select an employee');
                    return false;
                }
                let technician = this.technicianForm;
                technician.technician_id = technician.employee.id;
                this.technicians.push(technician);

                setTimeout(() => {
                    this.clearTechnicianForm();
                }, 200);
            },
            clearTechnicianForm() {
                this.technicianFormSearchCode = '';
                let technicianForm = {
                    employee: '',
                    technician_id: '',
                    time_start:  null,
                    time_end: null,
                }
                this.technicianForm = technicianForm;
            },
            clearTechnician() {
                this.technicianFormSearchCode = '';
                this.technicianForm = {
                    ...this.technicianForm,
                    employee: '',
                    technician_id: ''
                };
            },
            clearMaterialTechnician() {
                this.materialTechnicianSearchCode = '';
                this.materialForm.technician = '';
            },
            emptyMaterial() {
                this.materialSearchCode = '';
            },
        }
    }
</script>
