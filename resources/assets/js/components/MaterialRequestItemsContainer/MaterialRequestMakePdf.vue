<template>
    <section>
        <button class="pdfButton" @click="downloadPDF">
            PDF
        </button>
    </section>
</template>
<script>
export default {
  props: {
    materialRequestNumber: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      //
    }
  },
  mounted() {
    console.log(this.materialRequestNumber)
  },
  methods: {
    downloadPDF() {
        window.print()

    }
  }
}
</script>