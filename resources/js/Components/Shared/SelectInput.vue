<template>
    <div :class="$attrs.class">
      <label v-if="label" class="form-label" :for="id">{{ label }}:</label>
      <select :id="id" ref="input" v-model="selected" v-bind="{ ...$attrs, class: null }" class="form-select" :class="{ error: error }">
        <slot />
      </select>
      <div v-if="error" class="form-error">{{ error }}</div>
    </div>
  </template>

  <script>
  export default {
    inheritAttrs: false,
    props: {
      id: {
        type: String,
        default() {
          return `select-input-${+new Date()+Math.random() * 5000}`
        },
      },
      error: String,
      label: String,
      value: [String, Number, Boolean],
    },
    data() {
      return {
        selected: this.value,
      }
    },
    watch: {
      selected(selected) {
        this.$emit('input', selected)
      },
    },
    methods: {
      focus() {
        this.$refs.input.focus()
      },
      select() {
        this.$refs.input.select()
      },
    },
  }
  </script>
