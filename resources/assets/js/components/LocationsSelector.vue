<template>
    <div class="field"
         v-click-outside="loseLocationFocus"
         tabindex="1">
        <label class="label">Location <span class="has-text-danger">*</span></label>
        <input type="text"
               class="input is-small"
               v-model="locationSearch"
               ref="locationField"
               id="location-field"
               autocomplete="off"
               @click="selectLocationField"
               @keydown.tab="addNewLocationEntry"
               @blur="blurredLocationInput"
               @keydown="goDownLocation"
               required>
        <div class="dropdown-box-container box" v-if="showLocationsModal">
            <div class="is-flex" style="justify-content: space-between">
                <p><b>Locations</b></p>
                <a @click="$store.commit('Location/toggleManageLocationsModal')">Manage locations</a>
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
</template>

<script>
  export default {
    props: {
      location: {
        type: Object,
        required: false,
      }
    },
    data() {
      return {
        showLocationsModal: false,

        locationSearch: '',
        locations: [],
        locationsResult: [],
        locationFuse: null,
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
    mounted() {
      this.loadLocations();
      if (this.location) {
        this.locationSearch = this.location.name;
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
    },
    methods: {
      loseLocationFocus() {
        this.showLocationsModal = false;

        if (!this.location) {
          this.locationSearch = '';
        }
      },
      selectLocationField() {
        this.$refs.locationField.select();
        this.showLocationsModal = true;
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
      loadLocations() {
        this.$startLoading('FETCHING_LOCATIONS');
        axios.get(this.apiUrl() + '/locations').then(response => {
          this.locations = response.data;
          this.locationsResult = response.data;
          this.locationFuse = new window.Fuse(this.locations, this.locationSearchOptions);
          this.$endLoading('FETCHING_LOCATIONS');
        })
      },
      addNewLocationEntry() {
        this.showLocationsModal = false;
        //this.location = this.locationSearch;
        this.$emit('select:address', this.locationSearch);
      },
      selectLocation(loc) {
        //this.location = loc;
        this.locationSearch = loc.name;
        this.showLocationsModal = false;
        this.$emit('select:address', loc);
      },
      blurredLocationInput() {
        // todo.
      }
    }
  }
</script>
