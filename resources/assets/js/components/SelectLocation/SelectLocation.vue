<template>
    <div>
        <b-modal :active="showNewModal" @close="close()">
            <div class="modal-card-head">
                <p class="modal-card-title">
                    {{ $t('words.new-location') }}
                </p>
            </div>
            <div class="modal-card">
                <form @submit.prevent="save()">
                    <section class="modal-card-body">
                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.location') }} <span class="has-text-danger">*</span></label>
                                <p class="control">
                                    <input type="text" class="input" v-model="locationName">
                                </p>
                                <p>
                                    Add new Location
                                </p>
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
      optionValueId: '',
      optionValue: '',
      options: [],
      staffTypesOptions: [],
      isLoading: false,
      createdSuccess: false,
      locationName: '',
    }
  }
  export default {
    data: () => initialState(),
    computed: {
      showNewModal: {
        get() {
          return this.$store.getters['Location/showNewModal'];
        }
      },
    },
    mounted() {
      this.getStaffTypes();
    },
    methods: {
      newDepartment() {
        this.close();
        this.$store.commit('Location/showNewModal', true);
      },
      close() {
        this.$store.commit('Location/showNewModal', false);
      },
      getStaffTypes() {
        axios.get(this.apiUrl() + '/employees/types').then(response => {
          this.staffTypesOptions = response.data.types;
        })
      },
      save() {
        this.isLoading = true;
        axios.post('addNewLocations', {
          name: this.locationName,
        }).then(response => {
          this.isLoading = false;
          this.close();
          console.log(response.data.name);
          this.optionValueId = response.data.id
          this.optionValue = response.data.name
          document.getElementById('location').innerHTML += ("<option value='"+this.optionValueId+"'>"+this.optionValue+"</option>")
        }).catch(error => {
          alert(error.response.data.message);
          this.isLoading = false;
        })
        console.log(this.options);
      },
    }
  }
</script>
