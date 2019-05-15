<template>
         <b-field>
                <b-datepicker
                        v-model="date"
                        placeholder="Click to select...">
                </b-datepicker>
             <input type="hidden" name="date" id="date" :value="newVal">
        </b-field>
</template>
<script>
    import moment from 'moment';

    export default {
      data() {
        return {
          date:  new Date(),
          newVal: ''
        }
      },
      mounted() {
        this.newVal = moment(this.date).format('DD-MM-YYYY');
      },
      watch: {
        date: {
         handler: function(val, oldVal) {
           this.newVal = moment(val).format('DD-MM-YYYY');
         }
        }
      },
    }
</script>