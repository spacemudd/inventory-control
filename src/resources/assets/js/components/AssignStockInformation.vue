<template>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Assign stock information</p>
        </header>
        <section class="modal-card-body">
            <form action="">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <!-- Form Errors -->
                        <div class="notification is-danger" v-if="form.errors.length > 0">
                            <strong>Something went wrong.</strong>
                            <br>
                            <ul>
                                <li v-for="error in form.errors">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="column is-12">
                        <div class="field">
                            <label class="label">Code</label>
                            <div class="control">
                                <b-input name="code" v-model="code" size="small" autofocus></b-input>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12">
                        <div class="field">
                            <label class="label">Rack number</label>
                            <div class="control">
                                <b-input type="number" name="rack_number" v-model="rack_number" size="small"></b-input>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12">
                        <div class="field">
                            <label class="label">Recommended quantity</label>
                            <div class="control">
                                <b-input name="recommended_qty" v-model="recommended_qty" size="small"></b-input>
                            </div>
                        </div>
                    </div>

                    <div class="column is-12">
                        <div class="field">
                            <label class="label">Category</label>
                            <div class="control">
                                <b-select :loading="isFetchingCategories" v-model="category_id" required expanded>
                                    <option
                                            v-for="option in categories"
                                            :value="option.id"
                                            :key="option.id">
                                        {{ option.name }}
                                    </option>
                                </b-select>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </section>
        <footer class="modal-card-foot">
            <button type="button"
                    class="button"
                    @click="closeModal">{{ $t('words.close') }}
            </button>
            <button type="button"
                    class="button is-success"
                    :class="{'is-loading': $isLoading('ASSIGNING_STOCK_INFORMATION')}"
                    @click="save">
                Save
            </button>
        </footer>
    </div>
</template>

<script>
  export default {
    data() {
      return {
        isFetchingCategories: false,
        categories: [],
        form: {
          errors: []
        },

        code: '',
        rack_number: '',
        recommended_qty: '',
        category_id: null,
      }
    },
    mounted() {
      this.getAvailableCategories();
    },
    methods: {
      getAvailableCategories() {
        this.isFetchingCategories = true;
        axios.get(this.apiUrl() + '/categories').then(response => {
          this.isFetchingCategories = false;
          this.categories = response.data;
        }).catch(error => {
          alert(error.response.data.message);
        })
      },
      closeModal() {
        this.$emit('close');
      },
      save() {
        this.$startLoading('ASSIGNING_STOCK_INFORMATION');

        let item_id = this.$store.getters['AssignStockInformation/item_id'];
        let description = this.$store.getters['AssignStockInformation/description'];

        axios.post(this.apiUrl() + '/stocks', {
          item_id: item_id,
          code: this.code,
          description: description,
          rack_number: this.rack_number,
          recommended_qty: this.recommended_qty,
          category_id: this.category_id,
        }).then(res => {
            this.$emit('assign-stock-information:saved', this.item_id);
            this.$emit('close');
          }).catch(err => {
              if (typeof err.response.data === 'object') {
                this.form.errors = _.flatten(_.toArray(err.response.data.errors));
              } else {
                this.form.errors = ['Something went wrong. Please try again.'];
              }
            }).finally(() => {
              this.$endLoading('ASSIGNING_STOCK_INFORMATION');
            })
      },
    }
  }
</script>

<style lang="sass">
    //
</style>
