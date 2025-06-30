<template>
  <draggable class="p-tree-container list-none" tag="ul" :list="data" :group="{ name: 'tree' }" item-key="name" ghost-class="drop-hover">
    <template #item="{ element, index }">
      <li class="p-treenode" v-if="element.deleted == undefined">
        <div class="p-treenode-content p-treenode-selectable flex">
          <span class="flex align-content-center flex-wrap p-treenode-icon pi pi-fw" :class="element.icon"></span>
          <span class="flex align-content-center flex-wrap p-treenode-label">{{ element.label }}</span>
          <div class="ml-auto">
            <!-- <Button icon="pi pi-plus" v-tooltip.bottom="'Tambah Sub Menu'" @click="$emit('openCloseModal', 2, element, true)" size="small" severity="info" class="mr-2" /> -->
            <Button icon="pi pi-plus" v-tooltip.bottom="'Tambah Sub Menu'" @click="methods.click.openCloseModal(2, element, index)" size="small" severity="info" class="mr-2" />
            <Button icon="pi pi-pencil" v-tooltip.bottom="'Ubah Menu'" @click="methods.click.openCloseModal(3, element, index)" size="small" severity="warning" class="mr-2" />
            <Button v-if="element.items.length == 0 || element.items.filter(val => val.deleted == undefined).length == 0" icon="pi pi-trash" v-tooltip.bottom="'Hapus Menu'" @click="methods.click.hapusMenu(index)" size="small" severity="danger" />
            <!-- <Button v-if="element.items.length == 0 && element.parent_id != 0" icon="pi pi-trash" v-tooltip.bottom="'Hapus Menu'" @click="$emit('deleteMenu')" size="small" severity="danger" /> -->
          </div>
        </div>
        <TreeView :data="element.items" class="drop-area p-treenode-children" @delete-menu="methods.click.hapusMenu(index)" v-if="element.deleted == undefined"/>
      </li>
    </template>
  </draggable>
</template>
<script>
import draggable from "vuedraggable";
import { toRaw, markRaw, watch, ref, reactive, computed, onMounted, defineAsyncComponent, inject, getCurrentInstance } from 'vue';

const ModalFormMenu = defineAsyncComponent(() => import('./ModalFormMenu.vue'));

export default {
  props: {
    data: {
      // required: true,
      type: Array
    },
  },
  components: {
    draggable
  },
  setup(props, { emit }) {
    const globalVariable = getCurrentInstance().appContext.config.globalProperties;
    
    const methods = {
      click: {
        openCloseModal: (statusModal, dataModal, index) => {
          globalVariable.$dialog.open(ModalFormMenu, {
            props: {
              showHeader: false,
              style: {
                width: '50vw',
              },
              breakpoints: {
                '960px': '75vw',
                '640px': '90vw'
              },
              modal: true,
              contentClass: 'p-dialog',
              contentStyle: 'padding:0 !important;max-height:100%;'
            },
            data: {
              statusModal: statusModal,
              layout: (new URL(document.location)).searchParams.get('layout') ?? "admin",
              dataModal: dataModal
            },
            onClose: (options) => {
              const dataResultModal = options.data;
              if (dataResultModal) {
                if (statusModal == 2) {
                  props.data[index].items.push(dataResultModal);
                }
                else {
                  Object.assign(props.data[index], dataResultModal);
                }
              }
            }
          });

        },
        hapusMenu: (index) => {
          if (props.data[index].id != undefined) {
            globalVariable.$confirm.require({
              message: 'Apakah yakin ingin menghapus menu ini?',
              header: 'Konfirmasi',
              icon: 'pi pi-exclamation-triangle',
              acceptLabel:'Ya',
              rejectLabel:'Tidak',
              accept: () => {
                // globalVariable.$toast.add({ severity: 'info', summary: 'Confirmed', detail: 'You have accepted', life: 3000 });
                props.data[index].deleted = true;
                // console.log(toRaw(props.data[index]));
                // props.data.splice(index, 1);
              },
              // reject: () => {
                // globalVariable.$toast.add({ severity: 'error', summary: 'Rejected', detail: 'You have rejected', life: 3000 });
              // }
            });
          }
          else {
            props.data.splice(index, 1);
          }
        }
      }
    }

    return {
      methods
    };

  },
};
</script>

<style lang="scss" scoped>
.drop-hover {
  opacity: 0.5;
  // background: #c8ebfb;
  border: 1px dashed black;
}

.drop-area:after {
  content: "Drop Area";
  text-align: center;
  display: block;
}

.drop-area {
  // min-height: 25px;
  outline: 1px dashed;
}
</style>