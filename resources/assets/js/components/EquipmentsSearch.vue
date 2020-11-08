<template>
    <div class="w-100">
        <div class="w-100">
            <v-select
                    v-model="selectedEquipment"
                    label="name"
                    :reduce="equipment => equipment.id"
                    :options="equipments"
            >
            </v-select>
            <input type="hidden" name="equipment_id" :value="selectedEquipmentId">
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
        equipments: [],
        selectedEquipment: null,
      }
    },
    mounted() {
      this.initiateSearchList();
    },
    methods: {
      initiateSearchList() {
        axios.get(this.apiUrl() + '/equipments/all').then((response) => {
          console.log(response.data)
          this.equipments = response.data;
        })
      },
      searchMaterials() {

      },
    },
    computed: {
      selectedEquipmentId() {
        if (this.selectedEquipment) {
          return this.selectedEquipment.id;
        }

        return '';
      }
    }
  }
</script>

<style lang="sass">
    //
</style>
