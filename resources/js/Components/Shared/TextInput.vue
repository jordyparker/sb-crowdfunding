<template>
    <div :class="$attrs.class">
      <label v-if="label" class="form-label" :for="id">{{ label }}:</label>
      <input :id="id" ref="input" v-bind="{ ...$attrs, class: null }" class="form-input" :class="{ error: error }" :type="type" :value="value" @input="$emit('input', $event.target.value)" />
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
          return `text-input-${+new Date()+Math.random() * 65787}`
        },
      },
      type: {
        type: String,
        default: 'text',
      },
      value: {
        type: [String, Number, Boolean, Array],
    },
      error: String,
      label: String,
      modelValue: String,
    },
    methods: {
      focus() {
        this.$refs.input.focus()
      },
      select() {
        this.$refs.input.select()
      }
    },
  }
  </script>
