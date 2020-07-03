<template>
    <div>
        <b-modal :active="showModal" @close="close()">
        <div class="modal-card" style="height: auto">
            <div class="modal-card-head">
                <p class="modal-card-title">
                   {{ $t('words.new-department') }}
                </p>
            </div>
            <form @submit.prevent="save()">
                <section class="modal-card-body">
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.code') }} <span class="has-text-danger">*</span></label>
                                <p class="control">
                                    <input v-model="code" class="input" required>
                                </p>
                                <p class="help">An identity of the department, e.g. 001.</p>
                            </div>
                        </div>

                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.description') }} <span class="has-text-danger">*</span></label>

                                <p class="control">
                                    <input type="text" class="input"
                                           v-model="description" required>
                                </p>

                                <p class="help">Displayed with the code, e.g. in reports and forms.</p>
                            </div>
                        </div>

                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.head-of-department') }}</label>
                                <div class="control">
                                    <input type="text" class="input" v-model="head_department">
                                </div>
                            </div>
                        </div>

                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.location') }} <span class="has-text-danger">*</span></label>
                                <div class="select is-fullwidth">
                                    <select name="location_id" @onchange="getLocationValue(this.value)" id="locationId">
                                        <option value=""></option>
                                        <option v-for="(location, key) in locations " :key="key" :value="location.id" class="input">{{ location.name }}</option>
                                    </select>
                                    <span class="help" v-if="!isAddingLocation">
                                        Click here to <a target="_blank" @click="isAddingLocation=true;">add new locations.</a>
                                    </span>
                                </div>
                                
                            </div>

                            <div v-if="isAddingLocation">
                                    <div class="field">
                                        <b-input v-model="locationName" placeholder="New Location" size="is-small"></b-input>
                                    </div>
                                    <div class="field">
                                        <div class="control has-text-right">
                                            <button class="button is-small is-text" @click="isAddingLocation=false;">{{ $t('words.cancel') }}</button>
                                            <button class="button is-small is-primary"
                                                    :class="{'is-loading': isLoadingNewLoc}"
                                                    @click.prevent="saveNewLocation">Save</button>
                                        </div>
                                    </div>
                            </div>

                        </div>

                    </div>
                </section>
                <footer class="modal-card-foot">
                    <button class="button" type="button" @click="close">{{ $t('words.cancel') }}</button>
                    <button :class="{'is-loading': isLoading}"
                            type="submit"
                            class="button is-success">
                        {{ $t('words.save') }}
                    </button>
                </footer>
            </form>
        </div>
    </b-modal>
    </div>
</template>

<script>
    function initialState() {
        return {
            code: '',
            description: '',
            head_department: '',
            locations: [],
            // Handled by Vue.
            isLoading: false,
            createdSuccess: false,
            
            //added new variable 7/2/2020 for adding Locations
            isAddingLocation: false,
            locationName: '', //model for the new location
            isLoadingNewLoc: false
        }
    }
    export default {
        data: () => initialState(),
        computed: {
            showModal: {
                get() {
                  axios.get(this.apiUrl() + "/locations").then(response => {
                    this.locations = response.data;
                  });
                    return this.$store.getters['Department/showNewModal'];
                }
            },
        },
        mounted() {
            // this.getLocation();
        },
        methods: {
            close() {
                this.$store.commit('Department/showNewModal', false);
                this.reset();
            },
            reset() {
                Object.assign(this.$data, initialState());
            },

            saveNewLocation() {
                this.isLoadingNewLoc = true;
                let vm = this;
                axios.post('addNewLocations', {
                name: this.locationName,
                }).then(response => {
                this.isLoadingNewLoc = false;
               
               // console.log(response.data.name);
               // this.optionValueId = response.data.id
               // this.optionValue = response.data.name
                
                vm.locations.push({
                    id: response.data.id,
                    name: response.data.name
                })

                this.$toast.open({
                            message: 'Saved',
                            type: 'is-success',
                        });
                
                vm.isAddingLocation = false
                
                
                document.getElementById('location').innerHTML += ("<option value='"+response.data.id+"'>"+response.data.name+"</option>")
                document.getElementById('location').value = response.data.id;
                }).catch(error => {
                alert(error.response.data.message);
                vm.isLoadingNewLoc = false;
                })
               // console.log(this.options);
            },


            save() {
              var locationId = Number($("#locationId").val());
              this.isLoading = true;
                axios.post(this.apiUrl() + '/departments', {
                    code: this.code,
                    description: this.description,
                    head_department: this.head_department,
                    location_id: locationId,
                }).then(response => {
                    // this.isLoading = false;
                    // this.createdSuccess = true;
                    // window.location = response.data.link;
                  this.$toast.open({
                    type: 'is-success',
                    message: 'New department successfully created',
                  })
                  this.close();
                }).catch(error => {
                    alert(error.response.data.message);
                    this.isLoading = false;
                })

            },
        }
    }
</script>
