<template>
    <div  class="modal-card">
        <b-modal :active="showNewModal" @close="close()">
            <div class="modal-card-head">
                <p class="modal-card-title">
                    {{ $t('words.new-purchase-order') }}
                </p>
            </div>
            <div>
                    <section class="modal-card-body" >
                        <div class="column is-12">
                            <a :href="apiUrl() + '/purchase-orders/general-po/new'" class="button btn fullwidth">
                                General PO
                            </a>
                        </div>
                        <div class="column is-12">
                            <a href="#" class="button btn fullwidth">
                                Multiple Branches PO
                            </a>
                        </div>
                        <div class="column is-12">
                            <a href="#" class="button btn fullwidth">
                                ATM PO
                            </a>
                        </div>
                        <div class="column is-12">
                            <a href="#" class="button btn fullwidth">
                              VPC PO
                            </a>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <button class="button" type="button" @click="close">{{ $t('words.cancel') }}</button>
                    </footer>
            </div>
        </b-modal>
    </div>
</template>

<script>
  function initialState() {
    return {
      isLoading: false,
      createdSuccess: false,
    }
  }
  export default {
    data: () => initialState(),
    computed: {
      showNewModal: {
        get() {
          return this.$store.getters['Location/showNewModal'];
        }
      },
    },
    mounted() {

    },
    methods: {
      newDepartment() {
        this.close();
        this.$store.commit('Location/showNewModal', true);
      },
      close() {
        this.$store.commit('Location/showNewModal', false);
      },
    }
  }
</script>
