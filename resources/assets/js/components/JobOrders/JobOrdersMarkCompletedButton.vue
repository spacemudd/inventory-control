<template>
    <div v-if="jobOrder" class="is-inline">
        <button @click.prevent="markCompleted()"
                type="button"
                class="button"
                :class="{'is-loading': $isLoading('COMPLETING_JOB_ORDER')}"
        >
            Mark completed
        </button>
    </div>
</template>

<script>
  export default {
    data() {
      return {
        //
      }
    },
    mounted() {
      //
    },
    computed: {
      jobOrder() {
        return this.$store.getters['JobOrder/jobOrder'];
      },
    },
    methods: {
      markCompleted() {
        if (!this.jobOrder.time_end) {
          this.$dialog.alert({
            message: 'Please complete job duration (time end)',
            type: 'is-danger',
          });

          return false;
        }

        this.$startLoading('COMPLETING_JOB_ORDER'); // (ending of animation is in JobOrder/Show.vue under loadJobOrder()
        axios.post(this.apiUrl()+'/job-orders/'+this.jobOrder.id+'/complete')
          .then(response => {
            this.$emit('job-order:completed');
          })
      }
    }
  }
</script>
