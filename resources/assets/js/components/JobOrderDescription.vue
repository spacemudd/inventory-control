<template>
    <div class="field"
         v-click-outside="loseDescriptionFocus"
         tabindex="1">
        <textarea class="textarea"
                  rows="5"
                  @input="selectDescriptionText"
                  v-model="descriptionSearch"
                  ref="descriptionField"
                  id="description-field"
                  autocomplete="off"
                  @click="selectDescriptionField"
                  @keydown="goDownDescription"
                  required
        ></textarea>
        <div class="dropdown-box-container box" style="width:640px;" v-if="showModal">
            <div class="is-flex" style="justify-content: space-between">
                <p><b>Job descriptions</b></p>
            </div>
            <ul class="dropdown-box-container-list" id="descriptions-list">
                <li class="user-data user-data-add-new" v-if="!newDescriptionEntry">
                    <button class="button has-text-success"
                            @click.prevent="addNewDescriptionEntry"
                            tabindex="1">
                        <i>+ Add '{{ descriptionSearch }}'</i>
                    </button>
                </li>
                <li class="user-data" v-for="option in descriptionsResult">
                    <button @click.prevent="selectDescription(option)"
                            tabindex="1"
                            class="button">
                        {{ option.job_description }}
                    </button>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
  import vClickOutside from 'v-click-outside'


  export default {
    directives: {
      clickOutside: vClickOutside.directive
    },
    props: {
      jobDescriptionExisting: {
        type: String,
        required: false,
      },
    },
    data() {
      return {
        descriptionSearch: '',
        showModal: false,
        descriptions: [],

        // Search options
        descriptionsResult: [],
        descriptionFuse: null,
        descriptionSearchOptions: {
          shouldSort: true,
          threshold: 0.6,
          location: 0,
          distance: 100,
          maxPatternLength: 32,
          minMatchCharLength: 1,
          keys: [
            "job_description",
          ],
        },
      }
    },
    computed: {
      newDescriptionEntry() {
        let descriptionSearch = this.descriptionSearch;
        if (!descriptionSearch) {
          return true; // don't allow the user to add.
        }

        let hits = this.descriptions.filter((option) => {
          return option.name === descriptionSearch;
        })
        let dontAdd = hits.length;
        return dontAdd;
      },
    },
    mounted() {
      this.loadDescriptions();
    },
    watch: {
      descriptionSearch() {
        if (this.descriptionSearch.trim() === '') {
          this.descriptionsResult = this.descriptions;
        } else {
          this.descriptionsResult = this.descriptionFuse.search(this.descriptionSearch.trim());
        }
      },
    },
    methods: {
      loseDescriptionFocus() {
        this.showModal = false;

        if (!this.description) {
          this.descriptionSearch = '';
        }
      },
      selectDescriptionField() {
        this.$refs.descriptionField.select();
        this.showModal = true;
      },
      selectDescriptionText(text) {
        this.$emit('job-description:selected', text.target.value);
      },
      goDownDescription(e) {
        if (this.showLocationsModal) {
          let list = document.getElementById('descriptions-list');
          let first = list.firstChild;
          let maininput = document.getElementById('description-field');
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
      loadDescriptions() {
        this.$startLoading('FETCHING_LOCATIONS');
        axios.get(this.apiUrl() + '/job-descriptions').then(response => {
          this.descriptions = response.data;
          this.descriptionsResult = response.data;
          this.descriptionFuse = new window.Fuse(this.descriptions, this.descriptionSearchOptions);
          this.descriptionSearch = this.jobDescriptionExisting; // Load existing job order. (this is cause' descriptionFuse needs to be loaded)
          this.$endLoading('FETCHING_LOCATIONS');
        })
      },
      addNewDescriptionEntry() {
        this.showModal = false;
        this.description = this.descriptionSearch;
      },
      selectDescription(option) {
        this.description = option;
        this.descriptionSearch = option.job_description;
        this.showModal = false;
        this.$emit('job-description:selected', option.job_description);
      },
    }
  }
</script>

<style lang="sass">
    //
</style>
