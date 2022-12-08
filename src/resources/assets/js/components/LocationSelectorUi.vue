<!-- Created to use in Blade template -->
<template>
    <div>
        <location-selector :location="location"
                           :small="false"
                           @select:address="updateLocation">
        </location-selector>
        <input type="hidden" v-model="location_id" :name="fieldName">
    </div>
</template>

<script>
  export default {
    props: {
      fieldName: {
        type: String,
        required: true,
      }
    },
    data() {
      return {
        location_id: null,
        location: null,

      }
    },
    mounted() {
      //
    },
    methods: {
      updateLocation(loc) {
        this.location = loc;
        this.location_id = loc.id;
      }
    }
  }
</script>

<style lang="sass">
    //
</style>
