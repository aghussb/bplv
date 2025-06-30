<script setup>
import { computed, ref } from 'vue';
import AppConfig from './AppConfig.vue';
import { useLayout } from './composables/layout';
import AppHeader from './AppHeader.vue';
import AppFooter from './AppFooter.vue';

const { layoutConfig } = useLayout();

const smoothScroll = (id) => {
    document.querySelector(id).scrollIntoView({
        behavior: 'smooth'
    });
};

const logoUrl = computed(() => {
    return `${window.origin}/layout/images/${layoutConfig.darkTheme.value ? 'logo-white' : 'logo-dark'}.svg`;
});

</script>

<template>
    <div class="surface-0 flex justify-content-center">
        <div id="home" class="landing-wrapper overflow-hidden w-full">
            <app-header></app-header>
            <slot/>

            <app-footer></app-footer>
        </div>
    </div>
    <AppConfig simple />
</template>
<style lang="scss" scoped>
.layout-topbar {
    box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.02), 0px 0px 0px rgba(0, 0, 0, 0.05), 0px 0px 3px rgba(0, 0, 0, 0.08);
}
</style>
<!-- <style scoped>
#hero {
    background: linear-gradient(0deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.2)), radial-gradient(77.36% 256.97% at 77.36% 57.52%, #eeefaf 0%, #c3e3fa 100%);
    height: 700px;
    overflow: hidden;
}

@media screen and (min-width: 768px) {
    #hero {
        -webkit-clip-path: ellipse(150% 87% at 93% 13%);
        clip-path: ellipse(150% 87% at 93% 13%);
        height: 530px;
    }
}

@media screen and (min-width: 1300px) {
    #hero > img {
        position: absolute;
    }

    #hero > div > p {
        max-width: 450px;
    }
}

@media screen and (max-width: 1300px) {
    #hero {
        height: 600px;
    }

    #hero > img {
        position: static;
        transform: scale(1);
        margin-left: auto;
    }

    #hero > div {
        width: 100%;
    }

    #hero > div > p {
        width: 100%;
        max-width: 100%;
    }
}
</style> -->
