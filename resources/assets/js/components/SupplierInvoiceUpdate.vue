<template>
    <b-modal :active="showEditInvoiceUpdateModal" @close="close()">
          <div class="modal-card-head">
                <p class="modal-card-title">
                    Edit Invoice No.: {{getID.number}} 
                </p>
            </div>

            <div class="modal-card">
                <form @submit.prevent="save()">
                    <section class="modal-card-body" style="min-height:unset;">
                        <div class="column is-6">
                            <div class="field">
                                <label class="label">Invoice No. <span class="has-text-danger">*</span></label>
                                <p class="control">
                                    <input type="text" class="input" v-model="newInvoice">
                                </p>
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <button class="button" type="button" @click="close">{{ $t('words.cancel') }}</button>
                        <button :class="{'is-loading': isLoading}"
                                type="submit"
                                class="button is-success">
                            {{ $t('words.save') }}
                        </button>
                    </footer>
                </form>
            </div>

    </b-modal>
</template>

<script>
export default {
    
    data() {
        return {
            newInvoice: null,
            isLoading: false,
        }
    },

 computed: {
      showEditInvoiceUpdateModal: {
        get() {
          return this.$store.getters['SupplierInvoices/showEditInvoiceUpdateModal'];
        }
      },

      getID: {
          get(){
              let selected_row = this.$store.getters['SupplierInvoices/id'];
              this.newInvoice = selected_row.number;
              return selected_row;
          }
      }
    



    },

 methods: {

    close() {
        this.$store.commit('SupplierInvoices/showEditInvoiceUpdateModal', false);
    },

    save() {
        this.isLoading = true
        let vm = this;
        let data = {
            number: this.newInvoice
        }
         axios.post(this.apiUrl()+'/cost-approvals/'+this.getID.uid+'/changeinvoiceno', data)
          .then(response => {
            vm.isLoading = false
            console.log(response.data)
            if(response.data.status=='saved')
            {
               this.$toast.open({
                            message: 'Invoice Updated. You will be redirected shortly.',
                            type: 'is-success',
                        });

               setTimeout(function(){ window.location.reload(); }, 2000);
            }

            else
            {
                 this.$toast.open({
                            message: response.data.message,
                            type: 'is-danger',
                        });
            }

            document.getElementById('current-number-' + vm.getID.uid).innerHTML = data.number
          }).catch(e=>{
              this.isLoading = false;
              this.$buefy.toast.open({
                    message: `Something's not good, also I'm on bottom`,
                    type: 'is-danger'
                })
          })
    }
 }
    
}
</script>
