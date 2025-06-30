<template>
    <draggable :list="list" item-key="name" class="grid" ghost-class="ghost" :move="checkMove" @start="dragging = true" @end="dragging = false">
        <template #item="{ element : item,index :itemIndex }">
            <div class="col-3">
                <div class="card">
                    <h3>Draggable {{ item.id }} {{ item.name }}</h3>
                    <TreeView :tasks="item.child" />
                </div>
            </div>
        </template>
    </draggable>
</template>

<script>
import AppLayout from '@/Layouts/Admin/AppLayout.vue';
import { toRaw, watch, ref, reactive, computed, onMounted } from 'vue'
import draggable from "vuedraggable";
import TreeView from "./TreeView.vue";

export default {
    layout: AppLayout,
    components: {
        draggable,
        TreeView
    },
    setup() {
        const treeValue = ref(null);
        const selectedTreeValue = ref(null);

        onMounted(() => {
            
        });

        const list = reactive([
            {
                name: "John",
                id: 0,
                child: [
                    { name: "Juan", id: 5 },
                    { name: "Edgard", id: 6 },
                    { name: "Johnson", id: 7 }
                ]
            },
            {
                name: "Joao",
                id: 1,
                child: [
                    { name: "John", id: 1 },
                    { name: "Joao", id: 2 },
                    { name: "Jean", id: 3 },
                    { name: "Gerard", id: 4 }
                ]
            }
        ]);

        const checkMove = function (e) {
            window.console.log("Future index: " + e.draggedContext.futureIndex);
        }

        return {
            selectedTreeValue,
            treeValue,
            list,
            checkMove
        };
    }
};


</script>
<style lang="scss" scoped>
.ghost {
    opacity: 0.5;
    background: #c8ebfb;
  }
</style>