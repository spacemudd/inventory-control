<template>
    <div class="row">
        <div class="columns">
            <div class="column is-12 has-text-right">
                <div class="button is-small" @click="getTree">
                    Refresh
                </div>
                <button class="button is-primary is-small"
                        @click="addNode">
                    Add Location
                </button>
            </div>
        </div>
        <div class="box">
            <loading-screen v-if="!data"></loading-screen>
            <div v-else>
                <vue-tree-list
                        @click="onClick"
                        @change-name="onChangeName"
                        @delete-node="onDel"
                        @add-node="onAddNode"
                        :model="data"
                        default-tree-node-name="new location"
                        default-leaf-node-name="new equipment"
                        v-bind:default-expanded="false">
                    <span class="icon-1">Hello?</span>
                    <span class="icon-1" slot="addTreeNode">Add Location</span>
                    <span class="icon-1" slot="addLeafNode">Add Equipment</span>
                    <span class="icon-1" slot="editNode"><span class="fa fa-pencil"></span> Edit</span>
                    <span class="icon-1" slot="delNode"><span class="fa fa-trash"></span> Delete</span>
                </vue-tree-list>
            </div>
        </div>
    </div>
</template>
<script>
  import { VueTreeList, Tree, TreeNode } from 'vue-tree-list'
  export default {
    components: {
      VueTreeList
    },
    data () {
      return {
        newTree: {},
        oldName: '',
        newName: '',
        data: null,
      }
    },
    mounted() {
      this.getTree();
    },
    methods: {
      getTree() {
        this.data = null;
        axios.get(this.apiUrl() + '/equipment/get-tree')
          .then(response => {
            this.data = new Tree(response.data);
          })
      },
      onDel (node) {
        console.log(node)
        axios.delete(this.apiUrl() + '/equipment/'+node.name+'/delete')
          .then(response => {
            this.$toast.open({
              message: 'Deleted: '+node.name,
            });
            node.remove()
          })
      },

      saveChangeName() {
        axios.post(this.apiUrl() + '/equipment/change-node', {
          old_name: this.oldName,
          new_name: this.newName,
        })
          .then(response => {
            this.$toast.open({
              message: 'Saved: '+this.newName,
            });
          })
      },

      onChangeName(params) {
        this.oldName = params.oldName;
        this.newName = params.newName;
        debugger;
        setTimeout(() => {
          this.saveChangeName();
        }, 500);
      },

      onAddNode (params) {
        console.log(params)
        axios.post(this.apiUrl()+'/equipment/add-node', {
          name: params.name,
          parent: params.parent ? params.parent.name : '',
        })
          .then(response => {
            this.$toast.open({
              message: 'Saved: '+params.name,
            });
          })
      },

      onClick (params) {
        console.log(params)
      },

      addNode () {
        var node = new TreeNode({ name: 'new location', isLeaf: false })
        if (!this.data.children) this.data.children = []
        this.data.addChildren(node)
        this.onAddNode({'parent': '', 'name': 'new location'});
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

    }
  }
</script>
<style lang="less" rel="stylesheet/less">
    .vtl {
        .vtl-drag-disabled {
            background-color: #d0cfcf;
            &:hover {
                background-color: #d0cfcf;
            }
        }
        .vtl-disabled {
            background-color: #d0cfcf;
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
</style>
