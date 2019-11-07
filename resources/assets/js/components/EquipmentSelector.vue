<template>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Select equipment</p>
        </header>
        <section class="modal-card-body">
            <form action="">
                <div class="columns is-multiline">
                    <div class="column is-12 is-content">
                        <loading-screen v-if="!data"></loading-screen>
                        <div v-else>
                            <vue-tree-list
                                    @click="onClick"
                                    :model="data"
                                    default-tree-node-name="new location"
                                    default-leaf-node-name="new equipment"
                                    v-bind:default-expanded="false">
                            </vue-tree-list>
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
        </footer>
    </div>
</template>

<script>
  import { VueTreeList, Tree, TreeNode } from 'vue-tree-list'
  export default {
    components: {
      VueTreeList
    },
    data() {
      return {
        newTree: {},
        oldName: '',
        newName: '',
        data: null,

        isFetchingCategories: false,
        categories: [],
        form: {
          errors: []
        },
      }
    },
    mounted() {
      this.getTree();
    },
    methods: {
      getTree() {
        this.data = null;
        axios.get(this.apiUrl() + '/equipment/get-tree?disabled=true')
          .then(response => {
            this.data = new Tree(response.data);
          })
      },
      onClick (params) {
        if (params.isLeaf) {
          this.$emit('equipment:selected', params);
          this.closeModal();
        }
      },
      getNewTree () {
        var vm = this
        function _dfs (oldNode) {
          var newNode = {}

          for (var k in oldNode) {
            if (k !== 'children' && k !== 'parent') {
              newNode[k] = oldNode[k]
            }
          }

          if (oldNode.children && oldNode.children.length > 0) {
            newNode.children = []
            for (var i = 0, len = oldNode.children.length; i < len; i++) {
              newNode.children.push(_dfs(oldNode.children[i]))
            }
          }
          return newNode
        }

        vm.newTree = _dfs(vm.data)
        console.log(vm.newTree);
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

<style>
    li {
        margin-top: 15px;
    }
</style>

<style lang="less" rel="stylesheet/less">
    .vtl {
        .vtl-drag-disabled {
            background-color: white;
            &:hover {
                background-color: white;
            }
        }
        .vtl-disabled {
            background-color: white;
        }
    }
</style>

<style lang="less" rel="stylesheet/less" scoped>
    .icon-1 {
        margin-left: 20px;
        &:hover {
            cursor: pointer;
            color: #078af3;
        }
    }

    .vtl-tree-node .vtl-drag-disabled {
        background-color: white;
    }
</style>
