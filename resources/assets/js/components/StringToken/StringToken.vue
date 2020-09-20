<template>
    <div>
        <p class="is-size-7" :class="{'transparent-highlighter-bg': canEdit}" @click="edit">
            {{ old_value }}
            <span v-if="!old_value"><i>[{{ placeholder }}]</i></span>
        </p>
        <div v-if="is_editing" style="margin-top:20px">
            <div  v-if="!isFieldDisabled()" class="field">
                <b-input :disabled="isFieldDisabled()" v-model="loadedValue" size="is-small" @keyup.enter="save"></b-input>
            </div>
            <template v-if="!autoSave">
                <div v-if="!isFieldDisabled()" class="field">
                    <div class="control has-text-right">
                        <button class="button is-small is-text" @click="clearValue">Clear</button>
                        <button class="button is-small is-text" @click="rollback">{{ $t('words.cancel') }}</button>
                        <button class="button is-small is-primary"
                                :class="{'is-loading': $isLoading('SAVING_QUOTE_REF_TOKEN')}"
                                @click="save">Save</button>
                    </div>
                </div>
            </template>

            <div v-else class="field">
                <p class="help">Insufficient Role access to edit/modify existing value. </p> <button class="button is-small is-text" @click="rollback">{{ $t('words.cancel') }}</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
          autoSave: {
            type: Boolean,
            default: false,
          },
          id: {
            type: Number,
            required: true,
          },
            value: {
                type: String,
                required: false,
            },
          /**
           * The name of the token/field/column-name of the resource.
           */
          name: {
            type: String,
            required: true,
          },

            /**
             * Saving endpoint.
             */
            url: {
                type: String,
                required: true,
            },
            canEdit: {
                type: Boolean,
                default: false,
            },

            /*
             * can edit multiple times
             */
             canMultipleEdit: {
                 type: Boolean,
                 default: false
             },
          placeholder: {
            type: String,
            default: '[]',
          }
        },
      watch: {
        loadedValue() {
          if (this.autoSave) {
            this.saveAuto();
          }
        }
      },
        data() {
            return {
                is_editing: false,
                old_value: this.value,
                loadedValue: false,
            }
        },
        mounted() {
          this.loadedValue = this.value;
        },
        methods: {

            isFieldDisabled() {
                if(this.old_value=="" || this.old_value==null) return false;
                else
                {
                    if(this.canMultipleEdit)
                    return false;
                    else return true;
                
                };

            },
            edit() {
                if(this.canEdit) {
                    this.is_editing = true;
                } else {
                  this.$toast.open({
                    type: 'is-warning',
                    message: 'PO cant be edited after saving',
                  })
                }
            },
          saveAuto: _.debounce(function() {
            this.$startLoading('SAVING_QUOTE_REF_TOKEN');

            axios.put(this.apiUrl() + '/purchase-orders/' + this.id + '/tokens', {
              'name': this.name,
              'value': this.loadedValue,
            })
              .then(response => {
                this.$endLoading('SAVING_QUOTE_REF_TOKEN');
                //this.is_editing = false;

                this.old_value = response.data[this.name] ? response.data[this.name] : '';

                this.$toast.open({
                  message: 'Saved',
                  type: 'is-success',
                });
              })
          }, 200),
            save() {
                this.$startLoading('SAVING_QUOTE_REF_TOKEN');

                  axios.put(this.apiUrl() + '/purchase-orders/' + this.id + '/tokens', {
                    'name': this.name,
                    'value': this.loadedValue,
                  })
                    .then(response => {
                        this.$endLoading('SAVING_QUOTE_REF_TOKEN');
                        this.is_editing = false;

                        this.old_value = response.data[this.name] ? response.data[this.name] : '';

                        this.$toast.open({
                            message: 'Saved',
                            type: 'is-success',
                        });
                    })
                    // .catch(error => {
                    //     this.$endLoading('SAVING_QUOTE_REF_TOKEN')
                    //
                    //     if (typeof error.response.data === 'object') {
                    //         this.form.errors = _.flatten(_.toArray(error.response.data.errors));
                    //     } else {
                    //         this.form.errors = ['Something went wrong. Please try again.'];
                    //     }
                    //
                    //     this.$dialog.alert({
                    //         message: this.form.errors,
                    //         type: 'is-danger',
                    //     });
                    //
                    //     throw error;
                    // });
            },
            rollback() {
                this.value = this.old_value;
                this.is_editing = false;
            },
            clearValue() {
                this.old_value = '';
                this.loadedValue = '';
                this.save();
            },
        }
    }
</script>
