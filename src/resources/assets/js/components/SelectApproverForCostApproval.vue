<template>
  <div class="select is-fullwidth">
    <select class="select" v-model="approver_id">
    <option></option>
      <option v-for="approver in approvers" :value="approver.id">
        {{ approver.name }}
      </option>
    </select>
  </div>
</template>
<script>
  export default {
   props: {
    costApprovalId: {
     required: true,
    },
    selectedApproverId: {
      required: false,
    },
    fieldName: {
      required: true,
    },
  },
  data () {
    return {
      approvers: [],
      approver_id: null,
      completedLoading: false,
    }
  },
  mounted() {
    this.load();
  },
  watch: {
    approver_id: function(val) {
      if (this.completedLoading) {
        var dict = {};
        dict[this.fieldName] = val;
        axios.put(this.apiUrl()+'/cost-approvals/'+this.costApprovalId, dict).then(response => {
          console.log('Successfully set cost approval as: '+this.costApprovalId);
        })
      }
    }
  },
  methods: {
    load() {
      axios.get(this.apiUrl()+'/approvers')
      .then(response => {
        this.approvers = response.data;
        if (this.selectedApproverId) {
          this.approver_id = this.selectedApproverId;
        }
      this.completedLoading = true;
      })
    },
  },
}
</script>