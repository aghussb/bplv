<template>
    <div class="p-dialog-header">
        <span class="p-dialog-title">Tambah Menu</span>
        <div class="p-dialog-header-icons">
            <button class="p-dialog-header-icon p-dialog-header-close p-link" type="button" @click="methods.click.maximizedMinimized">
                <i :class="{'pi pi-window-minimize': status.icon.maximizedMinimized,  'pi pi-window-maximize': !status.icon.maximizedMinimized}"></i>
                <span class="p-ink"></span>
            </button>
            <button class="p-dialog-header-icon p-dialog-header-close p-link" type="button">
                <i class="pi pi-times"></i>                
                <span class="p-ink"></span>
            </button>
        </div>
    </div>

    <div class="p-dialog-content">
        <Button type="button" label="No" icon="pi pi-times" @click="selectProduct({ buttonType: 'No' })" text></Button>
    </div>

    <div class="p-dialog-footer">
        <Button type="button" label="Yes" icon="pi pi-check" @click="closeDialog({ buttonType: 'Yes' })" autofocus></Button>
    </div>

<!-- <template #footer>
        <div class="p-dialog-footer">
            <Button type="button" label="Yes" icon="pi pi-check" @click="closeDialog({ buttonType: 'Yes' })" autofocus></Button>
        </div>
    </template> -->
</template>

<script>
// document.getElementsByClassName('p-dialog p-component')[document.getElementsByClassName('p-dialog p-component').length].classList.add("p-dialog-maximized");
// document.getElementsByClassName('p-dialog p-component')[document.getElementsByClassName('p-dialog p-component').length].classList.remove("p-dialog-maximized");
// import { ProductService } from '@/Services/ProductService';
import { defineEmits, toRaw, ref, reactive, watch, onMounted, computed, inject } from 'vue';
export default {
    setup() {
        const thisInject = inject('dialogRef');
        // console.log(thisInject);
        const selectProduct = (data) => {
            thisInject.value.close(data);
        }

        const status = reactive({
            icon: {
                maximizedMinimized: false,
            }
        });

        const methods = {
            click:{
                maximizedMinimized : () => {
                    if (document.getElementsByClassName('p-dialog p-component')[document.getElementsByClassName('p-dialog p-component').length - 1].classList.contains("p-dialog-maximized")) {
                        document.getElementsByClassName('p-dialog p-component')[document.getElementsByClassName('p-dialog p-component').length - 1].classList.remove("p-dialog-maximized");
                        status.icon.maximizedMinimized = false;
                    }
                    else{
                        status.icon.maximizedMinimized = true;
                        document.getElementsByClassName('p-dialog p-component')[document.getElementsByClassName('p-dialog p-component').length - 1].classList.add("p-dialog-maximized");
                    }
                }
            }
        }

        return {
            selectProduct,
            status,
            methods
        }
    }
    // data() {
    //     return {
    //         products: null
    //     };
    // },
    // mounted() {
    //     ProductService.getProductsSmall().then((data) => (this.products = data.slice(0, 5)));
    // },
    // methods: {
    //     selectProduct(data) {
    //         this.dialogRef.close(data);
    //     },
    // }
};
</script>
<style scoped>
.container {
    padding: 3rem 1.5rem 2rem 1.5rem;
}

.footer {
    border-top: 0;
    background: white;
    padding: 1rem;
    text-align: right;
    border-bottom-right-radius: 2px;
    border-bottom-left-radius: 2px;
    display: flex;
    justify-content: flex-end;
}

.footer button {
    margin: 0 .5rem 0 0;
    width: auto;
}
</style>