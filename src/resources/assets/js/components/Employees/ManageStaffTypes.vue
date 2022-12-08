<template>
    <div class="modal is-active"
         @keyup.esc="closeModal">
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    Manage staff types
                </p>
                <button class="delete" aria-label="close" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">
                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode="out-in">
                    <div v-if="$isLoading('FETCHING_STAFF_TYPES')" key="loading" style="height:350px;">
                        <loading-screen size="is-small"></loading-screen>
                    </div>
                    <div v-else>
                        <error-bag v-if="errorBag" :error-bag="errorBag"></error-bag>
                        <button class="button is-small is-primary is-outlined"
                                style="margin-bottom:10px;"
                                @click="isAdding=!isAdding">New</button>
                        <table class="table is-fullwidth is-size-7 is-narrow is-striped">
                            <colgroup>
                                <col style="width:10px;">
                                <col>
                                <col style="width:140px">
                                <col style="width:130px;">
                            </colgroup>
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Created</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-if="isAdding">
                                <td></td>
                                <td>
                                    <input type="text" class="input is-small is-inline" v-model="newStaffTypeName">
                                    <button class="button is-small is-primary is-inline" @click="storeNewStaffType()">Save</button>
                                </td>
                            </tr>
                            <tr v-for="(type, index) in types">
                                <td>{{ type.id }}</td>
                                <td>
                                    <template v-if="currentlyEditing">
                                        <input v-if="currentlyEditing.id===type.id"
                                               :ref="type.id+'REF'"
                                               v-model="currentlyEditing.name"
                                               class="input is-small"
                                               type="text">
                                        <template v-else>
                                            {{ type.name }}
                                        </template>
                                    </template>
                                    <template v-else>
                                        {{ type.name }}
                                    </template>
                                </td>
                                <td>{{ type.created_at }}</td>
                                <td class="has-text-centered">
                                    <template v-if="currentlyEditing">
                                        <button v-if="currentlyEditing.id==type.id" @click="saveEditing(type)" class="button is-primary is-outlined is-small" :class="{'is-loading': currentlyEditing.is_saving}">Save</button>
                                        <button v-if="currentlyEditing.id==type.id" @click="cancelEditing(type)" class="button is-small">Cancel</button>
                                    </template>
                                    <template v-else>
                                        <button class="button is-small" @click="editType(type)">Edit</button>
                                        <button class="button is-small" @click="deleteLocation(type, index)">Delete</button>
                                    </template>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </transition>
            </section>
            <footer class="modal-card-foot">
                <button class="button" @click="closeModal">Close</button>
            </footer>
        </div>
    </div>
</template>

<script>
  import ErrorBag from './../ErrorBag/Bag';
  export default {
    components: {
      ErrorBag,
    },
    data() {
      return {
        errorBag: null,
        types: [],
        currentlyEditing: null,
        isAdding: false,
        newStaffTypeName: '',
      }
    },
    mounted() {
      this.loadTypes();
    },
    methods: {
      loadTypes() {
        this.$startLoading('FETCHING_STAFF_TYPES');
        axios.get(this.apiUrl() + '/employees/types/all').then(response => {
          response.data.map((type) => {
            type.is_editing = false;
            type.is_deleting = false;
            type.is_saving = false;
          });

          this.types = response.data;
          this.$endLoading('FETCHING_STAFF_TYPES');
        })
      },
      closeModal() {
        this.$store.commit('Employee/toggleManageStaffTypesModal');
      },
      editType(type) {
        this.currentlyEditing = type;
        this.currentlyEditing.old_name = type.name;

        let vm = this;
        setTimeout(function () {
          let refName = type.id+'REF';
          vm.$refs.refName.focus();
        }, 100);
      },
      cancelEditing() {
        this.currentlyEditing.name = this.currentlyEditing.old_name;
        this.currentlyEditing = null;
      },
      saveEditing(type) {
        this.errorBag = null;
        this.currentlyEditing.is_saving = true
        axios.put(this.apiUrl()+'/employees/types/'+type.id, type)
          .then(response => {
            this.currentlyEditing.is_saving = false;
            this.currentlyEditing = null;
            type = response.data;
          })
          .catch(error => {
            this.currentlyEditing.is_saving = false
            this.errorBag = error.response.data;
          })
          .finally(() => {

          })
      },
      deleteLocation(type, index)
      {
        axios.delete(this.apiUrl()+'/employees/types/'+type.id)
          .then(response => {
            this.$delete(this.types, index);
          })
      },
      storeNewStaffType()
      {
        if (!this.newStaffTypeName) {
          alert('Please complete the field');
          return;
        }

        axios.post(this.apiUrl()+'/employees/types', {
          name: this.newStaffTypeName,
        })
          .then(response => {
            this.newStaffTypeName = '';
            this.isAdding = false;
            this.loadTypes();
          })
      }
    }
  }
</script>

<style lang="sass">
    //
</style>
