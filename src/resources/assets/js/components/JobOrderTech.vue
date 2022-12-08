<template>
    <tr>
        <td>{{ tech.display_name }}</td>
        <td>{{ tech.pivot.time_start }}</td>
        <td>{{ tech.pivot.time_end }}</td>
        <td class="has-text-right">
            <button class="button is-small is-success is-outlined"
                    :class="{'is-loading': $isLoading('FINISHING_TECHNICIAN_'+tech.id)}"
                    v-show="!tech.pivot.time_end"
                    @click.prevent="$emit('tech:finish-job', tech)">
                <span class="icon is-small"><i class="fa fa-check"></i></span>
            </button>
            <button v-if="!jobOrder.is_completed"
                    class="button is-danger is-small"
                    :class="{'is-loading': $isLoading('DELETING_TECHNICIAN_'+tech.id)}"
                    @click.prevent="$emit('tech:delete', tech)">
                <span class="icon is-small"><i class="fa fa-trash"></i></span>
            </button>
        </td>
    </tr>
</template>

<script>
  export default {
    props: {
      tech: {
        type: Object,
        required: true,
      },
      jobOrder: {
        type: Object,
        required: true,
      }
    },
    data() {
      return {
        //
      }
    },
    mounted() {
      //
    },
    methods: {
      //
    }
  }
</script>

<style lang="sass">
    //
</style>
