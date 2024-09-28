<template>
    <div :class="$attrs.class">
      <label v-if="label" class="form-label" :for="id">{{ label }}:</label>
      <textarea :id="id" ref="input" v-bind="{ ...$attrs, class: null }" class="form-textarea" :class="{ error: error }" :value="value" @input="$emit('input', $event.target.value)" />
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
          return `textarea-input-${+new Date()+Math.random() * 5870}`
        },
      },
      error: String,
      label: String,
      value: String|Number,
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
