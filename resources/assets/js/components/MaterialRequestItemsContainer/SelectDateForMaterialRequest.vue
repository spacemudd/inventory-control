<template>
         <b-field>
             <p></p>
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
      props: {
        requestDate: {
          type: String,
          required: false,
        }
      },
      data() {
        return {
          date: new Date(),
        }
      },
      mounted() {
        this.date  = moment(this.requestDate, 'DD-MM-YYYY').toDate();
      },
      computed: {
        newVal() {
          return moment(this.date).format('DD-MM-YYYY');
        },
      }
    }
</script>
