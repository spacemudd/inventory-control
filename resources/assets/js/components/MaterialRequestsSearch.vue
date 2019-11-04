<template>
    <div class="w-100">
        <div class="w-100">
            <v-select
                    v-model="selectedMaterialRequest"
                    label="number"
                    :reduce="materialRequest => materialRequest.id"
                    :options="materialRequests"
            >
            </v-select>
            <input type="hidden" name="material_request_id" :value="selectedMaterialRequestId">
        </div>
    </div>
</template>

<script>
  import vSelect from 'vue-select';
  export default {
    components: {
      vSelect,
    },
    data() {
      return {
        fuse: null,
        search: '',
        list: [],
        result: [],
        materialRequests: [],
        selectedMaterialRequest: null,
      }
    },
    mounted() {
      this.initiateSearchList();
    },
    methods: {
      initiateSearchList() {
        axios.get(this.apiUrl() + '/material-requests/pending').then((response) => {
          this.materialRequests = response.data;
        })
      },
      searchMaterials() {

      },
    },
    computed: {
      selectedMaterialRequestId() {
        if (this.selectedMaterialRequest) {
          return this.selectedMaterialRequest.id;
        }

        return '';
      }
    }
  }
</script>

<style lang="sass">
    //
</style>
