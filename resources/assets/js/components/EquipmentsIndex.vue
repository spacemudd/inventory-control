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
                        @drop="onDropNode"
                        @drop-after="dropAfterNode"
                        @drop-before="dropBeforeNode"
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
                    <span class="icon-1" slot="editNode" ><span class="fa fa-pencil"></span> Edit</span>
                    <span class="icon-1" slot="delNode"><span class="fa fa-trash"></span> Delete</span>
                </vue-tree-list>
            </div>
        </div>
    </div>
</template>
<script>
  import { VueTreeList, Tree, TreeNode } from 'vue-tree-list';
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

      // This will fire when a node is dropped into another node.
      onDropNode(params) {
        if (params.target.id != params.src.id) {
          axios.post(this.apiUrl()+'/equipment/drop-node', {
          id: params.node.id, 
          pid: params.target.id ? params.target.id : '',
        })
          .then(response => {
            this.$toast.open({
              message: 'Moved: '+ params.node.name,
            });
          });
        }   
      },

      dropBeforeNode(params) {
        if (params.target.pid != params.src.id) {
          axios.post(this.apiUrl()+'/equipment/drop-node', {
          id: params.node.id, 
          pid: params.target.pid ? params.target.pid : '',
        })
          .then(response => {
            this.$toast.open({
              message: 'Moved: '+ params.node.name,
            });
          });
        }   
      },

      dropAfterNode(params) {
        if (params.target.pid != params.src.id) {
          axios.post(this.apiUrl()+'/equipment/drop-node', {
          id: params.node.id, 
          pid: params.target.pid ? params.target.pid : '',
        })
          .then(response => {
            this.$toast.open({
              message: 'Moved: '+ params.node.name,
            });
          });
        }   
      },

      saveChangeName() {
        axios.post(this.apiUrl() + '/equipment/change-node', {
          id: this.id,
          new_name: this.newName,
        })

      },

      onChangeName(params) {
        this.id = params.id;
        this.newName = params.newName;
        setTimeout(() => {
          this.saveChangeName();
        }, 3000);
      },

      onAddNode (params) {
        console.log(params.isLeaf)
        axios.post(this.apiUrl()+'/equipment/add-node', {
          id: params.id, 
          leaf: params.isLeaf,
          name: params.name,
          pid: params.pid ? params.pid : '',
        })
          .then(response => {
            this.$toast.open({
              message: 'Saved: '+params.name,
            });
          });
      },

      onClick (node) {
        console.log(node)
      },

      addNode () {
        var node = new TreeNode({ name: 'new location', isLeaf: false })
        if (!this.data.children) this.data.children = []
        this.data.addChildren(node)
        this.onAddNode({'parent': '', 'name': 'new location', 'isLeaf': false, 'id': node.id});
      },
      getTree() {
        this.data = null; 
        axios.get(this.apiUrl() + '/equipment/get-tree')
          .then(response => {
            console.log(response.data);
            this.data = new Tree(response.data);
          })
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
