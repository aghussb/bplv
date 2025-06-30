<template>
  <draggable class="list-group" :list="tasks" group="people" @change="log" itemKey="name" ghost-class="ghost">
    <template #item="{ element : elementChild,index :indexChild }">
      <div class="list-group-item">{{ elementChild.name }} {{ indexChild }}</div>
    </template>
  </draggable>
</template>
<script>
import draggable from "vuedraggable";

export default {
  props: {
    tasks: {
      required: true,
      type: Array
    }
  },
  components: {
    draggable
  },
  // data() {
  //   return {
  //   }
  // },
  methods: {
    log: function (evt) {
      window.console.log(evt);
    }
  }
};
</script>
<style lang="scss" scoped>
.ghost {
    opacity: 0.5;
    background: #c8ebfb;
  }
</style>