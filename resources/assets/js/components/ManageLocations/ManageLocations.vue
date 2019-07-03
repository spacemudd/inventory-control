<template>
    <div class="modal is-active"
         @keyup.esc="closeModal">
        <div class="modal-background" @click="closeModal"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    Manage locations
                </p>
                <button class="delete" aria-label="close" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">
                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode="out-in">
                    <div v-if="$isLoading('FETCHING_LOCATIONS')" key="loading" style="height:350px;">
                        <loading-screen size="is-small"></loading-screen>
                    </div>
                    <div v-else>
                        <error-bag v-if="errorBag" :error-bag="errorBag"></error-bag>

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
                        			<tr v-for="(location, index) in locations">
                                        <td>{{ location.id }}</td>
                        				<td>
                                            <template v-if="currentlyEditing">
                                                <input v-if="currentlyEditing.id===location.id"
                                                       :ref="location.id+'REF'"
                                                       v-model="currentlyEditing.name"
                                                       class="input is-small"
                                                       type="text">
                                                <template v-else>
                                                    {{ location.name }}
                                                </template>
                                            </template>
                                            <template v-else>
                                                {{ location.name }}
                                            </template>
                                        </td>
                                        <td>{{ location.created_at }}</td>
                                        <td class="has-text-centered">
                                            <template v-if="currentlyEditing">
                                                <button v-if="currentlyEditing.id==location.id" @click="saveEditing(location)" class="button is-primary is-outlined is-small" :class="{'is-loading': currentlyEditing.is_saving}">Save</button>
                                                <button v-if="currentlyEditing.id==location.id" @click="cancelEditing(location)" class="button is-small">Cancel</button>
                                            </template>
                                            <template v-else>
                                                <button class="button is-small" @click="editLocation(location)">Edit</button>
                                                <button class="button is-small" @click="deleteLocation(location, index)">Delete</button>
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
        locations: [],
        currentlyEditing: null,
      }
    },
    mounted() {
      this.loadLocations();
    },
    methods: {
      loadLocations() {
        this.$startLoading('FETCHING_LOCATIONS');
        axios.get(this.apiUrl() + '/locations').then(response => {
          response.data.map((loc) => {
            loc.is_editing = false;
            loc.is_deleting = false;
            loc.is_saving = false;
          });

          this.locations = response.data;
          // this.locationsResult = response.data;
          // this.locationFuse = new window.Fuse(this.locations, this.locationSearchOptions);
          this.$endLoading('FETCHING_LOCATIONS');
        })
      },
      closeModal() {
        this.$store.commit('Location/toggleManageLocationsModal');
      },
      editLocation(location) {
        this.currentlyEditing = location;
        this.currentlyEditing.old_name = location.name;

        let vm = this;
        setTimeout(function () {
          let refName = location.id+'REF';
          vm.$refs.refName.focus();
        }, 100);
      },
      cancelEditing() {
        this.currentlyEditing.name = this.currentlyEditing.old_name;
        this.currentlyEditing = null;
      },
      saveEditing(location) {
        this.errorBag = null;
        this.currentlyEditing.is_saving = true
        axios.put(this.apiUrl()+'/locations/'+location.id, location)
          .then(response => {
            this.currentlyEditing.is_saving = false;
            this.currentlyEditing = null;
            location = response.data;
          })
          .catch(error => {
            this.currentlyEditing.is_saving = false
            this.errorBag = error.response.data;
          })
          .finally(() => {

          })
      },
      deleteLocation(location, index)
      {
        axios.delete(this.apiUrl()+'/locations/'+location.id)
          .then(response => {
            this.$delete(this.locations, index);
          })
      },
      submitForm() {
        debugger;
      }
    }
  }
</script>

<style lang="sass">
    //
</style>
