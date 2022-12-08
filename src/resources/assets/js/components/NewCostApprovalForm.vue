<template>
    <section>
        <form class="form" method="post" style="margin-top:2rem" @submit.prevent="submitOrder">
            <div class="columns">
                <div class="column is-12">
                    <b-field label="Employee ID">
                        <!-- If selected. -->
                        <b-autocomplete v-if="!selectedEmployeeBy"
                                        v-model="employeeBySearchCode"
                                        field="code"
                                        :data="filteredDataObj"
                                        @select="option => selectedEmployeeBy = option"
                                        :loading="$isLoading('FETCHING_EMPLOYEES')"
                                        required>
                            <template slot="empty">No results found</template>
                        </b-autocomplete>
                        <!-- When selected -->
                        <input v-else
                               type="text"
                               class="input"
                               :value="selectedEmployeeBy.code + ' - ' + selectedEmployeeBy.name"
                               @click="emptyEmployeeBy"
                               required
                               readonly>
                    </b-field>

                    <!--Date-->
                    <div class="field">
                        <label class="label">Date <span class="has-text-danger">*</span></label>
                        <b-datepicker
                                v-model="date"
                                size="is-small"
                                placeholder="Click to select..."
                                required>
                        </b-datepicker>
                    </div>

                    <!--Okay-->

                </div>
            </div>
        </form>
    </section>
</template>

<script>
  export default {
    data() {
      return {
        employees: [],
        employeeBySearchCode: '',
        selectedEmployeeBy: '',
      }
    },
    mounted() {
      //
    },
    computed: {
      filteredDataObj() {
        return this.employees.filter((option) => {
          return option.code
            .toString()
            .toLowerCase()
            .indexOf(this.employeeBySearchCode.toLowerCase()) >= 0
        })
      },
    },
    methods: {
      emptyEmployeeBy() {
        this.selectedEmployeeBy = null;
        this.employeeBySearchCode = '';
      },
      submitOrder() {
        //
      }
    }
  }
</script>

<style lang="sass">
    //
</style>
