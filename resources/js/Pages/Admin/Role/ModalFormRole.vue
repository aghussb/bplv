<template>
    <div class="p-dialog-header">
        <span class="p-dialog-title">{{ (!this.thisInject.data.statusModal) ? "Tambah " : "Ubah " }} Role</span>
        <div class="p-dialog-header-icons">
            <button class="p-dialog-header-icon p-dialog-header-close p-link" type="button" @click="this.thisInject.close()">
                <i class="pi pi-times"></i>
                <span class="p-ink"></span>
            </button>
        </div>
    </div>

    <div class="p-dialog-content" style="overflow: auto;max-height: 412px;">
        <div class="p-fluid">
            <div class="formgrid grid">
                <div class="field col-12">
                    <label for="role">Role <small class="text-red-500">*</small></label>
                    <InputText id="role" type="text" placeholder="Role" v-model="form.name" />
                </div>
                <div class="field col-12">
                    <label for="role">Menu Layout <small class="text-red-500">*</small></label>
                    <div class="flex flex-wrap gap-4 justify-content-center">
                        <div class="flex align-items-center">
                            <RadioButton v-model="form.layout" inputId="admin" name="layout" value="admin" @change="methods.change.layout" />
                            <label for="admin" class="ml-2"> <i class="pi pi-user"></i> Admin</label>
                        </div>
                        <div class="flex align-items-center">
                            <RadioButton v-model="form.layout" inputId="public" name="layout" value="public" @change="methods.change.layout" />
                            <label for="public" class="ml-2"><i class="pi pi-globe"></i> Public</label>
                        </div>
                    </div>
                </div>
                <div class="field col-12">
                    <label for="label">Hak Akses Tambahan </label>
                    <Chips v-model="form.permissions" placeholder="Hak Akses Tambahan" :allowDuplicate="false" @remove="methods.chips.removeHakAksesTambahan($event, null)" />
                </div>
                <div class="field col-12" v-if="form.layout != null">
                    <label for="label">Pilih Menu <small class="text-red-500">*</small></label>
                    <Tree :pt="{
                        root: { style: { border: 'none', padding: 0 } }
                    }" :value="data.menus" selectionMode="single" v-model:expandedKeys="data.expandedKeys" v-model:selectionKeys="data.selectedKey" :filter="true" filterPlaceholder="Cari Menu" filterMode="lenient" class="w-full">
                        <template #default="slotProps" class="test">
                            <div class="grid my-1 gap-1">
                                <div class="flex align-content-center col-12">
                                    <Checkbox v-model="form.checked" :value="slotProps.node.id" @input="methods.input.checkedMenu(slotProps.node,$event)" />
                                    <span><i :class="slotProps.node.iconMenu"></i> {{ slotProps.node.label }}</span>
                                </div>
                                <div class="col-12" v-if="slotProps.node.children == undefined">
                                    <Chips v-model="slotProps.node.permissions" :allowDuplicate="false" placeholder="Isi Hak Akses" @remove="methods.chips.removeHakAksesTambahan($event, slotProps.node)" />
                                </div>
                            </div>
                        </template>
                    </Tree>
                </div>
            </div>
        </div>
    </div>

    <div class="p-dialog-footer">
        <Button type="button" label="Batal" icon="pi pi-times" @click="this.thisInject.close()" text></Button>
        <Button type="button" label="Simpan" icon="pi pi-check" @click="methods.click.save"></Button>
    </div>
    <ConfirmDialog group="templating">
        <template #message="slotProps">
            <div class="grid" style="width: 450px;">
                <div class="flex align-content-center col-12 justify-content-center">
                    <h4 class="pl-2">{{ slotProps.message.message }}</h4>
                </div>
                <div class="col-12">
                    <InlineMessage severity="warn" class="border-primary w-full justify-content-start">
                        <div class="flex align-items-center">
                            <div class="ml-2"><b>Menu</b> yang dipilih sebelumnya akan terhapus dan <b>Hak Akses</b> yang didalamnya dari layout <b>{{ thisInject.data.dataModal.layout }}.</b></div>
                        </div>
                    </InlineMessage>
                </div>
            </div>
        </template>
    </ConfirmDialog>
</template>

<script>
import { defineEmits, toRaw, ref, reactive, watch, onMounted, computed, inject, defineAsyncComponent, getCurrentInstance } from 'vue';
import { router } from '@inertiajs/vue3'

export default {
    setup(props) {
        const thisInject = inject('dialogRef');
        const globalVariable = getCurrentInstance().appContext.config.globalProperties;

        const fn = {
            findParentIds: (data, keyToFind, result = []) => {
                for (const item of data) {
                    if (item.key === keyToFind) {
                        result.push(item.id);
                        return result;
                    } else if (item.children) {
                        const found = fn.findParentIds(item.children, keyToFind, result);
                        if (found) {
                            result.push(item.id);
                            return result;
                        }
                    }
                }
                return null;
            },
            flattenProcess: (arr) => {
                const flattenedArray = [];

                for (const obj of arr) {
                    flattenedArray.push(obj);
                    if (obj.children) {
                        const childrenArray = fn.flattenProcess(obj.children);
                        flattenedArray.push(...childrenArray);
                    }
                }

                return flattenedArray;
            },
            collectIds: (arr) => {
                const result = [];

                for (const item of arr) {
                    result.push(item.id);
                    if (item.children) {
                        result.push(...fn.collectIds(item.children));
                    }
                }

                return result;
            },
            saveRole: () => {
                // router.post(route("storeRole"), {
                router.post("./role/storeRole", {
                    form: form,
                    menu: fn.flattenProcess(data.menus),
                    id: thisInject.value.data.dataModal != null ? thisInject.value.data.dataModal.id : null
                }, {
                    onSuccess: () => {
                        globalVariable.$toast.add({ severity: 'success', summary: 'SUKSES', detail: 'Berhasil Simpan Role', life: 3000 });
                        thisInject.value.close(true);
                        // Swal.fire({
                        //     title: 'Success!',
                        //     text: 'Menu berhasil disimpan.',
                        //     icon: 'success',
                        //     showConfirmButton: false,
                        //     timer: 2000
                        // });
                    },
                    onError: (error) => {
                        globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: Object.values(error)[0], life: 3000 });
                    }
                });
            }
        };
        // console.log(toRaw(thisInject.value.data.dataModal));
        const data = reactive({
            menus: thisInject.value.data.statusModal ? thisInject.value.data.dataModal.menu : [],
            expandedKeys: thisInject.value.data.statusModal ? thisInject.value.data.dataModal.expanded : {},
            deletePermissions: []
        });

        const status = reactive({
            disabled: {
                config: true
            }
        });

        const form = reactive({
            name: null,
            layout: null,
            permissions: [],
            checked: []
        });

        if (thisInject.value.data.statusModal) {
            Object.assign(form, thisInject.value.data.dataModal);
        }

        const methods = {
            chips: {
                removeHakAksesTambahan: ($event) => {
                    if (thisInject.value.data.statusModal) {
                        if (thisInject.value.data.dataModal.permissions.includes($event.value[0])) {
                            data.deletePermissions.push($event.value[0]);
                            data.deletePermissions = [...new Set(data.deletePermissions)];
                        }
                    }
                }
            },
            input: {
                checkedMenu: (dataMenu,event) => {
                    if (dataMenu.is_parent == 1) {
                        if (form.checked.includes(dataMenu.id)) {
                            form.checked = form.checked.concat(fn.collectIds(dataMenu.children));
                        }
                        else {
                            form.checked = form.checked.filter(item => fn.collectIds(dataMenu.children).indexOf(item) === -1);
                        }
                    }

                                        
                    if (dataMenu.key.split("-").length > 1 && event.includes(dataMenu.id)) {
                        form.checked = [...new Set(form.checked.concat(fn.findParentIds(thisInject.value.data.dataMenu[form.layout].menu,dataMenu.key)))];
                    }

                }
            },
            click: {
                save: () => {
                    if (form.name == null || form.name == "") {
                        globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: 'Role belum diisi', life: 3000 });
                        return
                    }

                    if (form.layout == null) {
                        globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: 'Menu Layout belum dipilih', life: 3000 });
                        return
                    }

                    if (form.checked.length == 0) {
                        globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: 'Menu belum ada yang dipilih', life: 3000 });
                        return
                    }


                    if (thisInject.value.data.statusModal) {
                        if (form.layout != thisInject.value.data.dataModal.layout) {
                            globalVariable.$confirm.require({
                                group: 'templating',
                                message: 'Apakah yakin ingin mengganti menu layout?',
                                header: 'Konfirmasi',
                                // icon: 'pi pi-exclamation-triangle',
                                acceptLabel: 'Ya',
                                rejectLabel: 'Tidak',
                                accept: () => {
                                    form.checked = form.checked.filter(item => !thisInject.value.data.dataMenu[thisInject.value.data.dataModal.layout].checked.includes(item));
                                    fn.saveRole();
                                },
                            });
                        }
                        else {
                            fn.saveRole();
                        }
                    }
                    else {
                        fn.saveRole();
                    }
                },
            },
            change: {
                layout: () => {
                    if (thisInject.value.data.statusModal) {
                        if (form.layout == thisInject.value.data.dataModal.layout) {
                            data.menus = thisInject.value.data.dataModal.menu;
                            data.expandedKeys = thisInject.value.data.dataModal.expanded;
                        }
                        else {
                            data.menus = thisInject.value.data.dataMenu[form.layout].menu;
                            data.expandedKeys = thisInject.value.data.dataMenu[form.layout].expanded;
                        }
                    }
                    else {
                        data.menus = thisInject.value.data.dataMenu[form.layout].menu;
                        data.expandedKeys = thisInject.value.data.dataMenu[form.layout].expanded;
                    }
                    // console.log(toRaw(thisInject.value.data.statusModal));
                }
            }
        };

        return {
            thisInject,
            data,
            status,
            form,
            methods,
        }
    }
};
</script>